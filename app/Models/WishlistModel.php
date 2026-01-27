<?php
class WishlistModel {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Ajouter aux favoris
    public function add($user_id, $product_id){
        // 1. Vérifier si existe déjà pour éviter doublon
        if($this->check($user_id, $product_id)) return true;

        // 2. Insérer (avec la date actuelle pour le tri)
        $this->db->query("INSERT INTO wishlists (user_id, product_id, created_at) VALUES (:user_id, :product_id, NOW())");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        return $this->db->execute();
    }

    // Retirer des favoris
    public function remove($user_id, $product_id){
        $this->db->query("DELETE FROM wishlists WHERE user_id = :user_id AND product_id = :product_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        return $this->db->execute();
    }

    // Vérifier si un produit est déjà liké (pour colorer le cœur en rouge)
    public function check($user_id, $product_id){
        $this->db->query("SELECT id FROM wishlists WHERE user_id = :user_id AND product_id = :product_id");
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':product_id', $product_id);
        $row = $this->db->single();
        return ($row) ? true : false;
    }

    // Récupérer tous les produits favoris d'un utilisateur (avec infos produit)
    public function getUserWishlist($user_id){
        $this->db->query("SELECT p.*, w.created_at as liked_at 
                          FROM wishlists w 
                          JOIN products p ON w.product_id = p.id 
                          WHERE w.user_id = :user_id 
                          ORDER BY w.created_at DESC");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }
    
    // Compter les favoris (Pour afficher un badge dans le header par exemple)
    public function countWishlist($user_id){
        $this->db->query("SELECT COUNT(*) as count FROM wishlists WHERE user_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        return $row->count;
    }
}