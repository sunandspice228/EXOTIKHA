<?php
class Review {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. LECTURE (ADMIN & FRONT)
    // =========================================================

    // 1. Récupérer TOUS les avis (Pour la liste Admin)
    // MODIF : J'ai ajouté un JOIN pour récupérer le nom du produit
    public function getAllReviews(){
        $this->db->query("SELECT reviews.*, products.name as product_name, products.image as product_image
                          FROM reviews 
                          LEFT JOIN products ON reviews.product_id = products.id 
                          ORDER BY reviews.created_at DESC");
        return $this->db->resultSet();
    }

    // 2. Récupérer les avis EN ATTENTE (Pour le compteur du Dashboard Admin)
    public function getPendingReviews(){
        $this->db->query("SELECT * FROM reviews WHERE status = 'pending'");
        return $this->db->resultSet();
    }

    // 3. Récupérer les avis APPROUVÉS d'un produit spécifique (Pour la fiche produit)
    public function getReviewsByProductId($product_id){
        $this->db->query("SELECT * FROM reviews WHERE product_id = :pid AND status = 'approved' ORDER BY created_at DESC");
        $this->db->bind(':pid', $product_id);
        return $this->db->resultSet();
    }

    // 4. Récupérer les avis d'un UTILISATEUR (Pour le compte client)
    public function getReviewsByUserId($user_id){
        // On récupère aussi le nom du produit pour que le client sache de quoi il parle
        $this->db->query("SELECT reviews.*, products.name as product_name, products.sku 
                          FROM reviews 
                          LEFT JOIN products ON reviews.product_id = products.id 
                          WHERE user_id = :uid 
                          ORDER BY reviews.created_at DESC");
        $this->db->bind(':uid', $user_id);
        return $this->db->resultSet();
    }

    // =========================================================
    // 2. ÉCRITURE
    // =========================================================

    // 5. Ajouter un avis
    public function addReview($data){
        // MODIF : Ajout de product_id indispensable
        $this->db->query("INSERT INTO reviews (product_id, user_id, full_name, email, rating, comment, status, created_at) 
                          VALUES (:pid, :uid, :name, :email, :rating, :comment, 'pending', NOW())");
        
        $this->db->bind(':pid', $data['product_id']);
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':name', $data['full_name']); // Nom affiché (ex: Jean D.)
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment']);
        
        return $this->db->execute();
    }

    // 6. Mettre à jour le statut (Approuver / Rejeter)
    // MODIF : Renommé en updateStatus pour matcher le contrôleur Admin
    public function updateStatus($id, $status){
        $this->db->query("UPDATE reviews SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // 7. Supprimer un avis (Admin)
    public function deleteReview($id){
        $this->db->query("DELETE FROM reviews WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    // Dans app/Models/Review.php

// Récupérer les avis APPROUVÉS (Pour le site public / Accueil)
public function getApprovedReviews(){
    // On joint pour avoir le nom du produit si besoin
    $this->db->query("SELECT reviews.*, products.name as product_name 
                      FROM reviews 
                      LEFT JOIN products ON reviews.product_id = products.id 
                      WHERE reviews.status = 'approved' 
                      ORDER BY reviews.created_at DESC LIMIT 6");
    return $this->db->resultSet();
}
}