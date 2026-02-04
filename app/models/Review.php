<?php
class Review {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // LISTE (Avec jointures Produits + Users)
    public function getAllReviews(){
        // On suppose que votre table users s'appelle 'users' et products 'products'
        $this->db->query("SELECT r.*, 
                                 p.name as product_name, 
                                 p.image as product_image,
                                 u.name as user_name,
                                 u.email as user_email
                          FROM reviews r
                          LEFT JOIN products p ON r.product_id = p.id
                          LEFT JOIN users u ON r.user_id = u.id
                          ORDER BY r.created_at DESC");
        return $this->db->resultSet();
    }

    // CHANGER LE STATUT (Approve / Pending)
    public function updateStatus($id, $status){
        $this->db->query("UPDATE reviews SET status = :status WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    // SUPPRIMER
    public function deleteReview($id){
        $this->db->query("DELETE FROM reviews WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    // COMPTER LES AVIS EN ATTENTE (Pour le dashboard par exemple)
    public function countPending(){
        $this->db->query("SELECT COUNT(*) as count FROM reviews WHERE status = 'pending'");
        $row = $this->db->single();
        return $row->count;
    }
}