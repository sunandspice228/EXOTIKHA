<?php
class Type {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Récupérer tous les types organisés par catégorie
    public function getTypes(){
        $this->db->query('SELECT types.*, categories.name as category_name 
                          FROM types 
                          JOIN categories ON types.category_id = categories.id 
                          ORDER BY categories.name, types.name');
        return $this->db->resultSet();
    }
    
    // Récupérer les types liés à une catégorie spécifique
    public function getTypesByCategoryId($catId){
        $this->db->query('SELECT * FROM types WHERE category_id = :catId ORDER BY name ASC');
        $this->db->bind(':catId', $catId);
        return $this->db->resultSet();
    }
}