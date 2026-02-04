<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}

class Order {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. CRÉATION DE COMMANDE (TRANSACTION)
    // =========================================================

    public function createOrder($data){
        // Génère un numéro de commande unique (ex: ORD-X7A9B2)
        $orderNumber = 'ORD-' . strtoupper(substr(uniqid(), -6));

        $this->db->query('INSERT INTO orders (
            user_id, order_number, total_amount, shipping_cost, payment_method, 
            status, payment_status, 
            shipping_address, shipping_city, shipping_region, shipping_phone, gps_coordinates, 
            created_at
        ) VALUES (
            :user_id, :order_number, :total, :shipping, :payment, 
            "pending", "unpaid", 
            :address, :city, :region, :phone, :gps, 
            NOW()
        )');

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':order_number', $orderNumber);
        $this->db->bind(':total', $data['total_amount']);
        $this->db->bind(':shipping', $data['shipping_cost']);
        $this->db->bind(':payment', $data['payment_method']);
        $this->db->bind(':address', $data['shipping_address']);
        $this->db->bind(':city', $data['shipping_city']);
        $this->db->bind(':region', $data['shipping_region']);
        $this->db->bind(':phone', $data['shipping_phone']);
        $this->db->bind(':gps', $data['gps_coordinates']);

        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function addOrderItems($orderId, $cartItems){
        $this->db->query('INSERT INTO order_items (order_id, product_id, product_name, image, quantity, price) 
                          VALUES (:order_id, :product_id, :product_name, :image, :qty, :price)');

        foreach($cartItems as $item){
            $item = (array) $item; 

            // Sécurisation des données produit
            $productId = isset($item['product_id']) ? $item['product_id'] : (isset($item['id']) ? $item['id'] : null);
            $image = !empty($item['image']) ? $item['image'] : null;
            $price = isset($item['price']) ? $item['price'] : 0;

            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':product_id', $productId);
            $this->db->bind(':product_name', $item['name']);
            $this->db->bind(':image', $image);
            $this->db->bind(':qty', $item['qty']);
            $this->db->bind(':price', $price);

            if(!$this->db->execute()){
                return false;
            }
            
            // Décrémentation du stock
            $this->decrementStock($productId, $item['qty']);
        }
        return true;
    }

    private function decrementStock($productId, $qty){
        if($productId){
            $this->db->query('UPDATE products SET stock = stock - :qty WHERE id = :pid');
            $this->db->bind(':qty', $qty);
            $this->db->bind(':pid', $productId);
            $this->db->execute();
        }
    }

    // =========================================================
    // 2. LECTURE & GESTION (ADMIN & FRONT)
    // =========================================================

    public function getOrderById($id){
        // Jointure pour récupérer l'email du client
        $this->db->query("SELECT o.*, c.email, c.first_name, c.last_name 
                          FROM orders o
                          JOIN customers c ON o.user_id = c.id
                          WHERE o.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getOrderItems($orderId){
        $this->db->query("SELECT * FROM order_items WHERE order_id = :order_id");
        $this->db->bind(':order_id', $orderId);
        return $this->db->resultSet();
    }

    public function getOrdersByUserId($userId){
        $this->db->query("SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC");
        $this->db->bind(':uid', $userId);
        return $this->db->resultSet();
    }

    public function getAllOrders(){
        $this->db->query("SELECT o.*, CONCAT(c.first_name, ' ', c.last_name) as customer_name 
                          FROM orders o
                          LEFT JOIN customers c ON o.user_id = c.id
                          ORDER BY o.created_at DESC");
        return $this->db->resultSet();
    }

    // =========================================================
    // 3. MISE À JOUR STATUTS
    // =========================================================

    public function updateStatus($id, $status){
        $this->db->query("UPDATE orders SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updatePaymentStatus($order_id, $payment_status, $order_status = null){
        if($order_status){
            $this->db->query('UPDATE orders SET payment_status = :pstatus, status = :ostatus WHERE id = :id');
            $this->db->bind(':ostatus', $order_status);
        } else {
            $this->db->query('UPDATE orders SET payment_status = :pstatus WHERE id = :id');
        }
        $this->db->bind(':pstatus', $payment_status);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    // =========================================================
    // 4. STATISTIQUES (POUR LE DASHBOARD)
    // =========================================================

    // Compte les commandes (avec filtre optionnel par statut)
    public function countOrders($status = null) {
        if ($status) {
            $this->db->query("SELECT COUNT(*) as count FROM orders WHERE status = :status");
            $this->db->bind(':status', $status);
        } else {
            $this->db->query("SELECT COUNT(*) as count FROM orders");
        }
        $row = $this->db->single();
        return $row->count;
    }

    // Calcule le revenu total (uniquement payé et non annulé)
    public function getTotalRevenue() {
        $this->db->query("SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid' AND status != 'cancelled'");
        $row = $this->db->single();
        return $row->total ?? 0;
    }

    // Récupère les dernières commandes avec le nom du client (Fusion des deux versions)
    public function getRecentOrders($limit = 5) {
        $this->db->query("SELECT o.*, CONCAT(c.first_name, ' ', c.last_name) as full_name 
                          FROM orders o
                          LEFT JOIN customers c ON o.user_id = c.id
                          ORDER BY o.created_at DESC 
                          LIMIT :limit");
        
        // IMPORTANT: Pour LIMIT avec PDO, il faut forcer le type entier
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // Récupère les données pour le graphique (6 derniers mois)
    public function getMonthlySales() {
        $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_amount) as total 
            FROM orders 
            WHERE payment_status = 'paid' AND status != 'cancelled'
            GROUP BY month 
            ORDER BY month DESC 
            LIMIT 6
        ");
        return $this->db->resultSet();
    }
}