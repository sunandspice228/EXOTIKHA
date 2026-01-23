<?php
class Review {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Ajouter un avis (Statut 'pending' par défaut)
    public function addReview($data){
        $this->db->query("INSERT INTO reviews (user_id, rating, comment, status) VALUES (:uid, :rating, :comment, 'pending')");
        $this->db->bind(':uid', $_SESSION['user_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment']);
        return $this->db->execute();
    }

    // Pour l'Admin : Voir TOUS les avis (avec le nom du client)
    public function getAllReviews(){
        $this->db->query("SELECT reviews.*, users.full_name, users.email 
                          FROM reviews 
                          JOIN users ON reviews.user_id = users.id 
                          ORDER BY reviews.created_at DESC");
        return $this->db->resultSet();
    }

    // Pour le Dashboard Admin : Compter les avis en attente
    public function getPendingReviews(){
        $this->db->query("SELECT * FROM reviews WHERE status = 'pending'");
        return $this->db->resultSet();
    }

    // Pour la Page d'Accueil : Voir seulement les avis APPROUVÉS
    public function getApprovedReviews(){
        $this->db->query("SELECT reviews.*, users.full_name 
                          FROM reviews 
                          JOIN users ON reviews.user_id = users.id 
                          WHERE reviews.status = 'approved' 
                          ORDER BY reviews.created_at DESC 
                          LIMIT 6");
        return $this->db->resultSet();
    }

    // Admin : Approuver un avis
    public function updateStatus($id, $status){
        $this->db->query("UPDATE reviews SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Admin : Supprimer un avis
    public function deleteReview($id){
        $this->db->query("DELETE FROM reviews WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}