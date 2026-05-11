<?php
class Category {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // LISTE
    public function getCategories(){
        $this->db->query("SELECT *, 
                         (SELECT COUNT(*) FROM products WHERE category_id = categories.id) as product_count 
                         FROM categories ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    // UNITAIRE
    public function getCategoryById($id){
        $this->db->query("SELECT * FROM categories WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // AJOUT (Sans image)
    public function addCategory($data){
        $this->db->query("INSERT INTO categories (name, name_fr, description, description_fr, created_at) 
                          VALUES (:name, :name_fr, :description, :description_fr, NOW())");
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':name_fr', $data['name_fr']);
        // $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':description_fr', $data['description_fr']);

        return $this->db->execute();
    }

    // MODIFICATION (Sans image)
    public function updateCategory($data){
        $this->db->query("UPDATE categories SET 
                          name = :name, 
                          name_fr = :name_fr,
                        --   slug = :slug, 
                          description = :description, 
                          description_fr = :description_fr
                          WHERE id = :id");

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':name_fr', $data['name_fr']);
        // $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':description_fr', $data['description_fr']);

        return $this->db->execute();
    }

    // SUPPRESSION
    public function deleteCategory($id){
        $this->db->query("DELETE FROM categories WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    // UTILS
    public function getAllCategories(){
        return $this->getCategories();
    }
}