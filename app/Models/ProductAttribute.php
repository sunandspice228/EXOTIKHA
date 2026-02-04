<?php
class ProductAttribute {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // LECTURE
    // =========================================================

    public function getAllAttributes(){
        $this->db->query("SELECT * FROM attributes ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function getAttributeById($id){
        $this->db->query("SELECT * FROM attributes WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // =========================================================
    // ÉCRITURE
    // =========================================================

    public function addAttribute($data){
        $this->db->query("INSERT INTO attributes (name, name_fr, values_en, values_fr, created_at) 
                          VALUES (:name, :name_fr, :values_en, :values_fr, NOW())");
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':name_fr', $data['name_fr']);
        $this->db->bind(':values_en', $data['values_en']);
        $this->db->bind(':values_fr', $data['values_fr']);

        return $this->db->execute();
    }

    public function updateAttribute($data){
        $this->db->query("UPDATE attributes SET 
                          name = :name, 
                          name_fr = :name_fr,
                          values_en = :values_en,
                          values_fr = :values_fr
                          WHERE id = :id");

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':name_fr', $data['name_fr']);
        $this->db->bind(':values_en', $data['values_en']);
        $this->db->bind(':values_fr', $data['values_fr']);

        return $this->db->execute();
    }

    public function deleteAttribute($id){
        $this->db->query("DELETE FROM attributes WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}