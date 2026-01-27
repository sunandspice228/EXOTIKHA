<?php
class Category {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. LECTURE (READ)
    // =========================================================

    // Récupérer toutes les catégories (pour les listes déroulantes et l'admin)
    public function getCategories(){
        $this->db->query('SELECT * FROM categories ORDER BY name ASC');
        return $this->db->resultSet();
    }

    // Récupérer une catégorie par ID
    public function getCategoryById($id){
        $this->db->query('SELECT * FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Pour le Front-End : Catégories avec image de couverture (dernier produit) et compteur
    public function getCategoriesWithCover(){
        $sql = "SELECT c.*, 
                (SELECT image FROM products p WHERE p.category_id = c.id AND image != '' ORDER BY p.created_at DESC LIMIT 1) as cover_image,
                (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) as product_count
                FROM categories c
                ORDER BY name ASC";
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    // Récupérer les sous-types liés à une catégorie (ex: Vêtements -> Robes, Jupes...)
    // Note : Vérifie si ta table s'appelle 'types' ou 'product_types' dans ta BDD
    public function getTypesByCategory($categoryId){
        $this->db->query('SELECT * FROM product_types WHERE category_id = :id ORDER BY name ASC');
        $this->db->bind(':id', $categoryId);
        return $this->db->resultSet();
    }

    // =========================================================
    // 2. ÉCRITURE (CREATE / UPDATE / DELETE)
    // =========================================================

    public function addCategory($data){
        $this->db->query('INSERT INTO categories (name, description) VALUES (:name, :description)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        
        return $this->db->execute();
    }

    public function updateCategory($data){
        $this->db->query('UPDATE categories SET name = :name, description = :description WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        
        return $this->db->execute();
    }

    public function deleteCategory($id){
        // Optionnel : Tu peux vérifier s'il y a des produits avant de supprimer
        // $this->db->query('SELECT COUNT(*) as count FROM products WHERE category_id = :id'); ...
        
        $this->db->query('DELETE FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}