<?php
class ProductType {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Récupérer tous les types (Pour la liste déroulante Admin)
    public function getTypes(){
        $this->db->query("SELECT * FROM product_types ORDER BY name ASC");
        return $this->db->resultSet();
    }

    // Récupérer les types filtrés par catégorie (Optionnel, pour le futur)
    public function getTypesByCategory($categoryId){
        // Assure-toi d'avoir une colonne category_id dans ta table product_types si tu veux utiliser ça
        $this->db->query("SELECT * FROM product_types WHERE category_id = :cid ORDER BY name ASC");
        $this->db->bind(':cid', $categoryId);
        return $this->db->resultSet();
    }

    // Ajouter un type
    public function addType($data){
        // Si ta table a une colonne category_id, ajoute-la ici :
        // INSERT INTO product_types (name, category_id) VALUES (:name, :cid)
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