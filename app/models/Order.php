<?php
class Order {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }
 
 

  
 
    // Créer une commande (Côté Client)
   

  // CRÉER COMMANDE (Mise à jour pour Paystack)
    public function createOrder($userId, $total, $cartItems, $shippingData, $paymentMethod = 'cod', $transactionRef = null){
        
        $orderNumber = rand(10000, 99999);
        
        // Déterminer le statut initial
        // Si Paystack et référence validée => 'paid'
        // Si COD => 'pending'
        $status = ($paymentMethod == 'paystack' && $transactionRef) ? 'paid' : 'pending';

        $sql = 'INSERT INTO orders (order_number, user_id, total_amount, status, payment_method, transaction_ref, shipping_phone, shipping_region, shipping_city, shipping_address) 
                VALUES (:ordernum, :uid, :total, :status, :method, :ref, :phone, :region, :city, :address)';
        
        $this->db->query($sql);
        $this->db->bind(':ordernum', $orderNumber);
        $this->db->bind(':uid', $userId);
        $this->db->bind(':total', $total);
        $this->db->bind(':status', $status);
        $this->db->bind(':method', $paymentMethod);
        $this->db->bind(':ref', $transactionRef);
        
        $this->db->bind(':phone', $shippingData['phone']);
        $this->db->bind(':region', $shippingData['region']);
        $this->db->bind(':city', $shippingData['city']);
        $this->db->bind(':address', $shippingData['address']);
        
        if($this->db->execute()){
            $orderId = $this->db->lastInsertId();

            foreach($cartItems as $item){
                $this->db->query('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:oid, :pid, :qty, :price)');
                $this->db->bind(':oid', $orderId);
                $this->db->bind(':pid', $item['id']);
                $this->db->bind(':qty', $item['qty']);
                $this->db->bind(':price', $item['price']);
                $this->db->execute();
                
                // Décrémenter Stock
                $this->db->query('UPDATE products SET stock = stock - :qty WHERE id = :pid');
                $this->db->bind(':qty', $item['qty']);
                $this->db->bind(':pid', $item['id']);
                $this->db->execute();
            }
            return true;
        }
        return false;
    }
    
    // Ajoute aussi cette méthode pour charger les régions dans le formulaire
    public function getGhanaRegions(){
        $this->db->query('SELECT * FROM ghana_regions ORDER BY name ASC');
        return $this->db->resultSet();
    }

    // --- STATISTIQUES GRAPHIQUE ---

    

    


    public function getOrders(){
        $this->db->query('SELECT orders.*, users.full_name, users.email 
                          FROM orders 
                          JOIN users ON orders.user_id = users.id 
                          ORDER BY orders.created_at DESC');
        return $this->db->resultSet();
    }

    public function getOrderById($id){
        $this->db->query('SELECT orders.*, users.full_name, users.email, users.role
                          FROM orders 
                          JOIN users ON orders.user_id = users.id 
                          WHERE orders.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

     
// RÉCUPÉRER LES COMMANDES D'UN CLIENT SPÉCIFIQUE
    public function getOrdersByUserId($userId){
        $this->db->query('SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC');
        $this->db->bind(':uid', $userId);
        return $this->db->resultSet();
    }
    public function updateStatus($id, $status){
        $this->db->query('UPDATE orders SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    // --- SUIVI DE COMMANDE (TRACK) ---
    public function getOrderByTrack($orderNumber, $email){
        // Recherche par order_number (le 5 chiffres) au lieu de l'ID interne
        $this->db->query('SELECT orders.*, users.full_name, users.email 
                          FROM orders 
                          JOIN users ON orders.user_id = users.id 
                          WHERE orders.order_number = :ordernum AND users.email = :email');
        
        $this->db->bind(':ordernum', $orderNumber);
        $this->db->bind(':email', $email);
        
        return $this->db->single();
    }
    
    // STATS
    public function getYearlySales($year){
        $sql = "SELECT MONTH(created_at) as month, SUM(total_amount) as total 
                FROM orders 
                WHERE YEAR(created_at) = :year 
                AND status != 'cancelled' 
                GROUP BY MONTH(created_at)";

        $this->db->query($sql);
        $this->db->bind(':year', $year);
        $results = $this->db->resultSet();

        $monthlySales = array_fill(0, 12, 0);
        foreach($results as $row){
            $monthlySales[$row->month - 1] = (float)$row->total;
        }
        return $monthlySales;
    }
    // Trouver une commande par numéro et email
public function trackOrder($orderNumber, $email){
    $this->db->query('SELECT orders.* FROM orders 
                      JOIN users ON orders.user_id = users.id 
                      WHERE orders.order_number = :num AND users.email = :email');
    $this->db->bind(':num', $orderNumber);
    $this->db->bind(':email', $email);
    return $this->db->single();
}

// Récupérer les produits d'une commande
public function getOrderItems($orderId){
    $this->db->query('SELECT order_items.*, products.name, products.image 
                      FROM order_items 
                      JOIN products ON order_items.product_id = products.id 
                      WHERE order_id = :oid');
    $this->db->bind(':oid', $orderId);
    return $this->db->resultSet();
}
}