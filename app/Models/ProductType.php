<?php
class ProductType {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Récupérer tous les types
    public function getTypes(){
        $this->db->query("SELECT * FROM product_types ORDER BY name ASC");
        return $this->db->resultSet();
    }

    // Ajouter un type
    public function addType($data){
        $this->db->query("INSERT INTO product_types (name) VALUES (:name)");
        $this->db->bind(':name', $data['name']);
        return $this->db->execute();
    }

    // Supprimer un type
    public function deleteType($id){
        $this->db->query("DELETE FROM product_types WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}