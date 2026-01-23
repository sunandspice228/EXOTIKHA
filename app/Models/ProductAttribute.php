<?php
class ProductAttribute {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Récupérer tous les attributs définis
    public function getAttributes(){
        $this->db->query("SELECT * FROM product_attributes ORDER BY name ASC");
        return $this->db->resultSet();
    }

    // Ajouter un attribut
    public function addAttribute($data){
        $this->db->query("INSERT INTO product_attributes (name, value) VALUES (:name, :value)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':value', $data['value']); // Ex: "Rouge, Bleu" ou "S, M, L" (si stocké en texte)
        return $this->db->execute();
    }

    // Supprimer un attribut
    public function deleteAttribute($id){
        $this->db->query("DELETE FROM product_attributes WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}