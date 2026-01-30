<?php
class Wishlist {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Récupérer tous les produits de la wishlist d'un client
    public function getUserWishlist($customerId){
        $this->db->query("SELECT p.*, w.id as wishlist_id 
                          FROM wishlist w 
                          JOIN products p ON w.product_id = p.id 
                          WHERE w.customer_id = :id");
        
        $this->db->bind(':id', $customerId);
        return $this->db->resultSet();
    }

    // Vérifier si un produit est déjà liké
    public function check($customerId, $productId){
        $this->db->query("SELECT id FROM wishlist WHERE customer_id = :customer_id AND product_id = :product_id");
        $this->db->bind(':customer_id', $customerId);
        $this->db->bind(':product_id', $productId);
        
        $row = $this->db->single();
        return ($row) ? true : false;
    }

    // Ajouter à la wishlist
    public function add($customerId, $productId){
        $this->db->query("INSERT INTO wishlist (customer_id, product_id) VALUES (:customer_id, :product_id)");
        $this->db->bind(':customer_id', $customerId);
        $this->db->bind(':product_id', $productId);
        
        return $this->db->execute();
    }

    // Retirer de la wishlist
    public function remove($customerId, $productId){
        $this->db->query("DELETE FROM wishlist WHERE customer_id = :customer_id AND product_id = :product_id");
        $this->db->bind(':customer_id', $customerId);
        $this->db->bind(':product_id', $productId);
        
        return $this->db->execute();
    }
}