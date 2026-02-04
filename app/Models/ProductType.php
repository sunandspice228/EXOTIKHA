<?php
class ProductType {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // LISTE
    public function getAllTypes(){
        $this->db->query("SELECT * FROM product_types ORDER BY name ASC");
        return $this->db->resultSet();
    }

    // UNITAIRE
    public function getTypeById($id){
        $this->db->query("SELECT * FROM product_types WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // AJOUT
    public function addType($data){
        $this->db->query("INSERT INTO product_types (name, name_fr, created_at) 
                          VALUES (:name, :name_fr, NOW())");
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':name_fr', $data['name_fr']);

        return $this->db->execute();
    }

    // MODIFICATION
    public function updateType($data){
        $this->db->query("UPDATE product_types SET 
                          name = :name, 
                          name_fr = :name_fr
                          WHERE id = :id");

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':name_fr', $data['name_fr']);

        return $this->db->execute();
    }

    // SUPPRESSION
    public function deleteType($id){
        $this->db->query("DELETE FROM product_types WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}