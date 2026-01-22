<?php
namespace App\Models;

use Core\Model;

class Order extends Model {

    // 1. Récupérer toutes les commandes (avec le nom du client)
    public function getAll() {
        $sql = "SELECT o.*, u.name as user_name, u.email 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // 2. Récupérer une seule commande par ID
    public function getById($id) {
        $sql = "SELECT o.*, u.name as user_name, u.email, u.address 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.id = :id";
        return $this->db->query($sql, ['id' => $id])->fetch();
    }

    // 3. Récupérer les articles d'une commande
    public function getItems($orderId) {
        $sql = "SELECT oi.*, p.name, p.image 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.id 
                WHERE oi.order_id = :id";
        return $this->db->query($sql, ['id' => $orderId])->fetchAll();
    }

    // 4. Mettre à jour le statut
    public function updateStatus($id, $status) {
        $sql = "UPDATE orders SET status = :status WHERE id = :id";
        $this->db->query($sql, ['status' => $status, 'id' => $id]);
    }

    // 5. Compter les commandes (pour le dashboard)
    public function countAll() {
        return $this->db->query("SELECT COUNT(*) as c FROM orders")->fetch()['c'];
    }
    
    // 6. Récupérer les commandes récentes (pour le dashboard)
    public function getRecent($limit = 5) {
        $sql = "SELECT o.*, u.name as user_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.id 
                ORDER BY o.created_at DESC LIMIT $limit";
        return $this->db->query($sql)->fetchAll();
    }
}