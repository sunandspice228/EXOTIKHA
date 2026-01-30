<?php
class ProductType {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // LECTURE
    // =========================================================

    // Récupérer tous les types
    public function getAllTypes(){
        // IMPORTANT : On utilise 'product_types' pour être cohérent avec le modèle Product
        $this->db->query("SELECT * FROM product_types ORDER BY name ASC");
        return $this->db->resultSet();
    }

    // Récupérer un type par ID (Celle-ci manquait pour l'édition)
    public function getTypeById($id){
        $this->db->query("SELECT * FROM product_types WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // =========================================================
    // ÉCRITURE
    // =========================================================

    // Ajouter un type
    public function addType($data){
        $this->db->query("INSERT INTO product_types (name) VALUES (:name)");
        $this->db->bind(':name', $data['name']);
        return $this->db->execute();
    }

    // Mettre à jour un type (Celle-ci manquait)
    public function updateType($data){
        $this->db->query("UPDATE product_types SET name = :name WHERE id = :id");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    // Supprimer un type
    public function deleteType($id){
        $this->db->query("DELETE FROM product_types WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}