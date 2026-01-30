<?php
class Category {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // LECTURE
    // =========================================================

    // Récupérer toutes les catégories (Avec le nombre de produits liés)
    public function getAllCategories(){
        // Cette requête compte aussi combien de produits sont dans chaque catégorie
        $this->db->query('SELECT c.*, 
                                 (SELECT COUNT(*) FROM products WHERE category_id = c.id) as product_count 
                          FROM categories c 
                          ORDER BY c.name ASC');
        return $this->db->resultSet();
    }

    // Récupérer une catégorie par ID
    public function getCategoryById($id){
        $this->db->query('SELECT * FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // =========================================================
    // ÉCRITURE
    // =========================================================

    // Ajouter une catégorie
    public function addCategory($data){
        $this->db->query('INSERT INTO categories (name, description, created_at) VALUES (:name, :description, NOW())');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Mettre à jour une catégorie (Celle-ci manquait !)
    public function updateCategory($data){
        $this->db->query('UPDATE categories SET name = :name, description = :description WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Supprimer une catégorie
    public function deleteCategory($id){
        // Optionnel : Vous pourriez vouloir vérifier s'il y a des produits avant de supprimer
        // Mais pour l'instant, on supprime direct.
        $this->db->query('DELETE FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
}