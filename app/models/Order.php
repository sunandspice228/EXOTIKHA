<?php
class Order {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. ORDER PROCESS (FRONT-END)
    // =========================================================

    // CREATE ORDER (Complete Logic with Paystack & Stock)
    // Remarque : J'ai ajouté $shippingCost dans les arguments
    // Créer une commande complète
    public function createOrder($userId, $totalAmount, $shippingCost, $cartItems, $shippingData, $paymentMethod, $paystackRef = null){
        
        try {
            // 1. Démarrer une transaction (Vital pour l'intégrité des stocks)
            $this->db->beginTransaction();

            // Génération numéro de commande
            $orderNumber = rand(10000, 99999);
            
            // Définition des statuts
            $status = ($paymentMethod == 'paystack' && $paystackRef) ? 'processing' : 'pending';
            $paymentStatus = ($paymentMethod == 'paystack' && $paystackRef) ? 'paid' : 'pending';

            // 2. Insertion dans la table ORDERS
            $sql = 'INSERT INTO orders (
                        order_number, user_id, total_amount, shipping_cost, 
                        status, payment_status, payment_method, paystack_ref, 
                        full_name, shipping_phone, shipping_region, shipping_city, 
                        shipping_address, gps_coordinates, created_at
                    ) VALUES (
                        :ordernum, :uid, :total, :ship_cost, 
                        :status, :pay_status, :method, :ref, 
                        :fname, :phone, :region, :city, 
                        :address, :gps, NOW()
                    )';

            $this->db->query($sql);

            // Liaison des paramètres (Bind)
            $this->db->bind(':ordernum', $orderNumber);
            $this->db->bind(':uid', $userId);
            $this->db->bind(':total', $totalAmount);       // Total Final (Produits + Livraison)
            $this->db->bind(':ship_cost', $shippingCost); // Coût livraison seul (pour stats)
            
            $this->db->bind(':status', $status);
            $this->db->bind(':pay_status', $paymentStatus);
            $this->db->bind(':method', $paymentMethod);
            $this->db->bind(':ref', $paystackRef);
            
            // Infos Livraison
            $this->db->bind(':fname', $shippingData['full_name']);
            $this->db->bind(':phone', $shippingData['phone']);
            $this->db->bind(':region', $shippingData['region']);
            $this->db->bind(':city', $shippingData['city']);
            $this->db->bind(':address', $shippingData['address']);
            $this->db->bind(':gps', $shippingData['gps']);

            $this->db->execute();
            
            // Récupération de l'ID de la commande
            $orderId = $this->db->lastInsertId();

            // 3. Insertion des ARTICLES (Order Items)
            foreach($cartItems as $item){
                // Gestion Hybride (Tableau ou Objet) pour éviter les crashs
                $id = is_array($item) ? $item['id'] : $item->id;
                $variantId = is_array($item) ? ($item['variant_id'] ?? null) : ($item->variant_id ?? null);
                $qty = is_array($item) ? $item['qty'] : $item->qty;
                $price = is_array($item) ? $item['price'] : $item->price;
                $name = is_array($item) ? $item['name'] : $item->name;

                $this->db->query('INSERT INTO order_items (order_id, product_id, product_name, variant_id, quantity, price) 
                                  VALUES (:oid, :pid, :pname, :vid, :qty, :price)');
                
                $this->db->bind(':oid', $orderId);
                $this->db->bind(':pid', $id);
                $this->db->bind(':pname', $name);
                $this->db->bind(':vid', $variantId); 
                $this->db->bind(':qty', $qty);
                $this->db->bind(':price', $price);
                
                $this->db->execute();
                
                // 4. Mise à jour des stocks
                $this->decrementStock($id, $variantId, $qty);
            }

            // Tout s'est bien passé, on valide !
            $this->db->commit();
            return true;

        } catch(Exception $e){
            // Problème ? On annule tout (pas de commande créée, pas de stock débité)
            $this->db->rollBack();
            // Optionnel : logger l'erreur
            // error_log($e->getMessage());
            return false;
        }
    }

    // Helper privé pour décrémenter le stock
    private function decrementStock($productId, $variantId, $qty){
        // Si c'est une variante (Taille/Couleur)
        if(!empty($variantId)){
            // 1. On baisse le stock de la variante
            $this->db->query('UPDATE product_variants SET stock = stock - :qty WHERE id = :vid');
            $this->db->bind(':qty', $qty);
            $this->db->bind(':vid', $variantId);
            $this->db->execute();

            // 2. On baisse AUSSI le stock global du produit parent
            $this->db->query('UPDATE products SET stock = stock - :qty WHERE id = :pid');
            $this->db->bind(':qty', $qty);
            $this->db->bind(':pid', $productId);
            $this->db->execute();
        } else {
            // Si c'est un produit simple sans variante
            $this->db->query('UPDATE products SET stock = stock - :qty WHERE id = :pid');
            $this->db->bind(':qty', $qty);
            $this->db->bind(':pid', $productId);
            $this->db->execute();
        }
    }

    

    // =========================================================
    // 2. READ ORDERS (ADMIN & CLIENT)
    // =========================================================

    // List all orders (Admin)
    public function getAllOrders(){
        $this->db->query('SELECT orders.*, users.full_name, users.email 
                          FROM orders 
                          LEFT JOIN users ON orders.user_id = users.id 
                          ORDER BY orders.created_at DESC');
        return $this->db->resultSet();
    }

    // Single Order by ID (Details)
    public function getOrderById($id){
        $this->db->query('SELECT orders.*, users.full_name, users.email, users.role
                          FROM orders 
                          LEFT JOIN users ON orders.user_id = users.id 
                          WHERE orders.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get Items for an Order
    public function getOrderItems($orderId){
        // Join with products & variants
        $this->db->query("SELECT oi.*, oi.product_name, p.image, p.sku, 
                                 pv.size as variant_size, pv.color as variant_color 
                          FROM order_items oi
                          LEFT JOIN products p ON oi.product_id = p.id
                          LEFT JOIN product_variants pv ON oi.variant_id = pv.id
                          WHERE oi.order_id = :id");
        $this->db->bind(':id', $orderId);
        $results = $this->db->resultSet();

        // Format variant info string
        foreach($results as $item){
            $item->variant_info = '';
            if(isset($item->variant_size) || isset($item->variant_color)){
                $item->variant_info = trim(($item->variant_size ?? '') . ' / ' . ($item->variant_color ?? ''), ' / ');
            }
        }
        return $results;
    }

    // Client Orders
    public function getOrdersByUserId($userId){
        $this->db->query("SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC");
        $this->db->bind(':uid', $userId);
        return $this->db->resultSet();
    }

    // Track Order
    public function trackOrder($orderNumber, $email){
        $this->db->query('SELECT orders.*, users.full_name 
                          FROM orders 
                          LEFT JOIN users ON orders.user_id = users.id 
                          WHERE orders.order_number = :num'); 
        // Logic simplified for now, can add email check if needed
        $this->db->bind(':num', $orderNumber);
        return $this->db->single();
    }

    // =========================================================
    // 3. ADMIN ACTIONS
    // =========================================================

    public function updateStatus($id, $status){
        $this->db->query("UPDATE orders SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        
        // Exécute et retourne VRAI ou FAUX
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function updatePaymentStatus($id, $status){
        $this->db->query("UPDATE orders SET payment_status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // =========================================================
    // 4. STATS
    // =========================================================

    public function getTotalRevenue(){
        $this->db->query("SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'");
        $row = $this->db->single();
        return $row->total ?? 0;
    }

    public function countOrders(){
        $this->db->query("SELECT COUNT(*) as count FROM orders");
        $row = $this->db->single();
        return $row->count;
    }

    public function getLatestOrders($limit = 5){
        $this->db->query("SELECT orders.*, users.full_name 
                          FROM orders 
                          LEFT JOIN users ON orders.user_id = users.id 
                          ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getYearlySales($year){
        $this->db->query("SELECT 
                            MONTH(created_at) as month, 
                            SUM(total_amount) as total 
                          FROM orders 
                          WHERE YEAR(created_at) = :year AND status != 'cancelled'
                          GROUP BY MONTH(created_at)
                          ORDER BY month ASC");
        
        $this->db->bind(':year', $year);
        $results = $this->db->resultSet();

        $chartData = array_fill(0, 12, 0); 

        foreach($results as $row){
            $chartData[$row->month - 1] = (float)$row->total;
        }

        return $chartData;
    }

    // Get Last Order for Success Page
    public function getLastOrder($user_id){
        $this->db->query("SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC LIMIT 1");
        $this->db->bind(':uid', $user_id);
        return $this->db->single();
    }
}