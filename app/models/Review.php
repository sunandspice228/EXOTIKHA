<?php
namespace App\Models;

use Core\Model;

class Review extends Model {

    // Récupérer les avis d'un produit
    public function getByProduct($productId) {
        $sql = "SELECT r.*, u.name as user_name 
                FROM reviews r 
                JOIN users u ON r.user_id = u.id 
                WHERE r.product_id = :pid 
                ORDER BY r.created_at DESC";
        return $this->db->query($sql, ['pid' => $productId])->fetchAll();
    }

    // Récupérer la moyenne des notes
    public function getStats($productId) {
        $sql = "SELECT AVG(rating) as average, COUNT(*) as count 
                FROM reviews WHERE product_id = :pid";
        $result = $this->db->query($sql, ['pid' => $productId])->fetch();
        return [
            'average' => round($result['average'] ?? 0, 1),
            'count' => $result['count'] ?? 0
        ];
    }

    // Vérifier si l'utilisateur a acheté le produit (Statut 'completed' ou 'shipped')
    public function hasBoughtProduct($userId, $productId) {
        $sql = "SELECT COUNT(*) as c FROM orders o 
                JOIN order_items oi ON o.id = oi.order_id 
                WHERE o.user_id = :uid 
                AND oi.product_id = :pid 
                AND (o.status = 'completed' OR o.status = 'shipped')";
        $res = $this->db->query($sql, ['uid' => $userId, 'pid' => $productId])->fetch();
        return $res['c'] > 0;
    }

    // Vérifier s'il a déjà noté
    public function hasAlreadyReviewed($userId, $productId) {
        $sql = "SELECT COUNT(*) as c FROM reviews 
                WHERE user_id = :uid AND product_id = :pid";
        $res = $this->db->query($sql, ['uid' => $userId, 'pid' => $productId])->fetch();
        return $res['c'] > 0;
    }

    // Ajouter un avis
    public function add($userId, $productId, $rating, $comment) {
        $sql = "INSERT INTO reviews (user_id, product_id, rating, comment, created_at) 
                VALUES (:uid, :pid, :rating, :comment, NOW())";
        $this->db->query($sql, [
            'uid' => $userId, 
            'pid' => $productId, 
            'rating' => $rating, 
            'comment' => $comment
        ]);
    }
}