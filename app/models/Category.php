<?php
class Category {
    public $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Récupérer toutes les catégories
    public function getCategories(){
        $this->db->query('SELECT * FROM categories ORDER BY name ASC');
        return $this->db->resultSet();
    }

    // NOUVEAU : Récupérer les catégories AVEC une image de couverture (basée sur le dernier produit ajouté)
    public function getCategoriesWithCover(){
        $sql = "SELECT c.*, 
                (SELECT image FROM products p WHERE p.category_id = c.id AND image != '' ORDER BY p.created_at DESC LIMIT 1) as cover_image,
                (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as product_count
                FROM categories c
                ORDER BY name ASC";
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getCategoryById($id){
        $this->db->query('SELECT * FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Pour l'admin (AJAX ou Select)
    public function getTypesByCategory($categoryId){
        $this->db->query('SELECT * FROM types WHERE category_id = :id');
        $this->db->bind(':id', $categoryId);
        return $this->db->resultSet();
    }
}