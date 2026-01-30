<?php
class Review {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. LECTURE (ADMIN & FRONT)
    // =========================================================

    // Récupérer TOUS les avis (Admin - Liste complète)
    public function getAllReviews(){
        $this->db->query("SELECT reviews.*, 
                                 products.name as product_name, 
                                 products.image as product_image,
                                 CONCAT(customers.first_name, ' ', customers.last_name) as full_name
                          FROM reviews 
                          LEFT JOIN products ON reviews.product_id = products.id 
                          LEFT JOIN customers ON reviews.customer_id = customers.id 
                          ORDER BY reviews.created_at DESC");
        return $this->db->resultSet();
    }

    // Récupérer les avis EN ATTENTE (Admin - Dashboard)
    public function getPendingReviews(){
        $this->db->query("SELECT reviews.*, 
                                 products.name as product_name,
                                 CONCAT(customers.first_name, ' ', customers.last_name) as full_name
                          FROM reviews 
                          LEFT JOIN products ON reviews.product_id = products.id 
                          LEFT JOIN customers ON reviews.customer_id = customers.id
                          WHERE reviews.status = 'pending'
                          ORDER BY reviews.created_at DESC");
        return $this->db->resultSet();
    }

    // Récupérer les avis APPROUVÉS d'un produit (Front - Page Détails Produit)
    public function getReviewsByProductId($product_id){
        $this->db->query("SELECT reviews.*, 
                                 CONCAT(customers.first_name, ' ', customers.last_name) as full_name
                          FROM reviews 
                          LEFT JOIN customers ON reviews.customer_id = customers.id
                          WHERE product_id = :pid AND status = 'approved' 
                          ORDER BY created_at DESC");
        $this->db->bind(':pid', $product_id);
        return $this->db->resultSet();
    }

    // Récupérer les avis d'un CLIENT (Front - Espace Mon Compte)
    public function getReviewsByUserId($customer_id){
        // Note : On utilise 'customer_id' dans la clause WHERE
        $this->db->query("SELECT reviews.*, 
                                 products.name as product_name, 
                                 products.slug as product_slug,
                                 products.image as product_image
                          FROM reviews 
                          LEFT JOIN products ON reviews.product_id = products.id 
                          WHERE customer_id = :uid 
                          ORDER BY reviews.created_at DESC");
        $this->db->bind(':uid', $customer_id);
        return $this->db->resultSet();
    }

    // Récupérer les derniers avis APPROUVÉS (Front - Page d'accueil / Témoignages)
    public function getApprovedReviews(){
        // Correction : On ne demande plus 'customers.image' car la colonne n'existe pas.
        // On récupère juste les infos de l'avis et le nom complet du client.
        
        $this->db->query("SELECT reviews.*, 
                                 CONCAT(customers.first_name, ' ', customers.last_name) as customer_name
                          FROM reviews
                          JOIN customers ON reviews.customer_id = customers.id
                          WHERE reviews.status = 'approved'
                          ORDER BY reviews.created_at DESC");
        
        return $this->db->resultSet();
    }

    // =========================================================
    // 2. ÉCRITURE (AJOUT / MODIFICATION / SUPPRESSION)
    // =========================================================

    // Ajouter un avis (Front)
    public function addReview($data){
        // Attention : La colonne s'appelle 'customer_id'
        $this->db->query("INSERT INTO reviews (product_id, customer_id, rating, comment, status, created_at) 
                          VALUES (:pid, :uid, :rating, :comment, 'pending', NOW())");
        
        $this->db->bind(':pid', $data['product_id']);
        // On mappe 'user_id' (session) vers 'customer_id' (bdd)
        $this->db->bind(':uid', $data['user_id']); 
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment']);
        
        return $this->db->execute();
    }

    // Mettre à jour le statut (Admin - Approuver/Rejeter)
    public function updateStatus($id, $status){
        $this->db->query("UPDATE reviews SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Supprimer un avis (Admin ou Client)
    public function deleteReview($id){
        $this->db->query("DELETE FROM reviews WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}