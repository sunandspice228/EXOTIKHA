<?php
class Order {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. CRÉATION DE COMMANDE (TRANSACTION SÉCURISÉE)
    // =========================================================

    public function createOrder($data){
        // On génère l'ID unique
        $orderNumber = 'ORD-' . strtoupper(substr(uniqid(), -6));

        // DÉBUT DE LA TRANSACTION (Tout ou Rien)
        // Si votre classe Database a une méthode beginTransaction(), utilisez-la.
        // Sinon, c'est une sécurité logique ici.
        
        try {
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
        } catch(Exception $e) {
            return false;
        }
    }

    // Dans app/Models/Order.php

public function addOrderItems($orderId, $cartItems){
        // Requête SQL préparée
        $this->db->query('INSERT INTO order_items (order_id, product_id, product_name, image, quantity, price) VALUES (:order_id, :product_id, :product_name, :image, :qty, :price)');

        foreach($cartItems as $item){
            // SÉCURITÉ : On force la conversion en tableau au cas où
            // Cela permet de gérer si $item est un Objet OU un Tableau
            $item = (array) $item; 

            // 1. Liaison de l'ID Commande
            $this->db->bind(':order_id', $orderId);

            // 2. Liaison de l'ID Produit (Attention: vérifiez si votre panier utilise 'product_id' ou 'id')
            // Souvent dans le panier c'est juste 'id'
            $productId = isset($item['product_id']) ? $item['product_id'] : (isset($item['id']) ? $item['id'] : null);
            $this->db->bind(':product_id', $productId);

            // 3. Liaison du Nom
            $this->db->bind(':product_name', $item['name']);
            
            // 4. Liaison de l'Image (Gestion du cas vide)
            $image = !empty($item['image']) ? $item['image'] : null;
            $this->db->bind(':image', $image);
            
            // 5. Liaison Quantité et Prix
            $this->db->bind(':qty', $item['qty']);
            
            // Si vous stockez le prix total de la ligne (line_total) ou le prix unitaire (price)
            // Adaptez ici selon votre structure de panier. Je mets 'price' par défaut.
            $price = isset($item['price']) ? $item['price'] : 0;
            $this->db->bind(':price', $price);

            // Exécution
            if(!$this->db->execute()){
                return false;
            }
        }
        return true;
    }

    private function decrementStock($productId, $qty){
        $this->db->query('UPDATE products SET stock = stock - :qty WHERE id = :pid');
        $this->db->bind(':qty', $qty);
        $this->db->bind(':pid', $productId);
        $this->db->execute();
    }

    // =========================================================
    // 2. MISE À JOUR (ADMIN & PAYEMENT)
    // =========================================================

    // Mise à jour du statut de livraison UNIQUEMENT (Pour l'Admin)
   

    // Mise à jour du paiement (Paystack ou Admin)
    public function updatePaymentStatus($order_id, $payment_status, $order_status = null){
        // Si on change aussi le statut de la commande (ex: payé -> processing)
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
    // 3. LECTURE (FRONT & BACK)
    // =========================================================

   

    

    public function getOrdersByUserId($userId){
        $this->db->query("SELECT * FROM orders WHERE user_id = :uid ORDER BY created_at DESC");
        $this->db->bind(':uid', $userId);
        return $this->db->resultSet();
    }

    // Dans app/Models/Order.php

// Récupérer une commande spécifique + Email du client
public function getOrderById($id){
    $this->db->query("SELECT o.*, c.email 
                      FROM orders o
                      JOIN customers c ON o.user_id = c.id
                      WHERE o.id = :id");
    $this->db->bind(':id', $id);
    return $this->db->single();
}

// Récupérer les articles d'une commande
public function getOrderItems($orderId){
    $this->db->query("SELECT * FROM order_items WHERE order_id = :order_id");
    $this->db->bind(':order_id', $orderId);
    return $this->db->resultSet();
}

// Mettre à jour le statut
public function updateStatus($id, $status){
    $this->db->query("UPDATE orders SET status = :status WHERE id = :id");
    $this->db->bind(':status', $status);
    $this->db->bind(':id', $id);
    return $this->db->execute();
}
    // Dans app/Models/Order.php

public function getAllOrders(){
    // On récupère tout, trié par le plus récent
    $this->db->query("SELECT * FROM orders ORDER BY created_at DESC");
    return $this->db->resultSet();
}
    // =========================================================
    // 4. STATISTIQUES (DASHBOARD)
    // =========================================================

    

    // Dans app/Models/Order.php

// Calculer le Chiffre d'Affaires total (Commandes livrées ou payées uniquement)
public function getTotalRevenue(){
    // On ne compte souvent que les commandes 'delivered' ou 'completed' pour le vrai CA
    // Si vous voulez tout compter, retirez le WHERE
    $this->db->query("SELECT SUM(total_amount) as total FROM orders WHERE status != 'cancelled'");
    $row = $this->db->single();
    return $row->total ?? 0;
}

// Compter le nombre total de commandes
public function countOrders(){
    $this->db->query("SELECT COUNT(*) as count FROM orders");
    $row = $this->db->single();
    return $row->count;
}

// Récupérer les stats mensuelles pour le graphique (SQL avancé)
public function getMonthlyStats(){
    // Cette requête groupe les ventes par mois (Format YYYY-MM)
    // Elle remonte sur les 6 derniers mois
    $this->db->query("
        SELECT 
            DATE_FORMAT(created_at, '%Y-%m') as month, 
            SUM(total_amount) as total 
        FROM orders 
        WHERE status != 'cancelled' 
        AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
        GROUP BY month 
        ORDER BY month ASC
    ");
    return $this->db->resultSet(); // Retourne un tableau d'objets
}

// Récupérer les commandes (avec limite optionnelle)
public function getOrders($limit = null){
    $sql = "SELECT * FROM orders ORDER BY created_at DESC";
    if($limit){
        $sql .= " LIMIT $limit";
    }
    $this->db->query($sql);
    return $this->db->resultSet();
}

    public function getRecentOrders($limit = 5){
        $this->db->query("SELECT o.*, CONCAT(c.first_name, ' ', c.last_name) as full_name 
                          FROM orders o
                          LEFT JOIN customers c ON o.user_id = c.id
                          ORDER BY o.created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getMonthlySales(){
        $currentYear = date('Y');
        $this->db->query("SELECT MONTH(created_at) as month, SUM(total_amount) as total 
                          FROM orders 
                          WHERE YEAR(created_at) = :year AND status != 'cancelled' AND payment_status = 'paid'
                          GROUP BY MONTH(created_at)");
        $this->db->bind(':year', $currentYear);
        $results = $this->db->resultSet();

        $chartData = array_fill(0, 12, 0); 
        foreach($results as $row){
            $chartData[$row->month - 1] = (float)$row->total;
        }
        return $chartData;
    }
}