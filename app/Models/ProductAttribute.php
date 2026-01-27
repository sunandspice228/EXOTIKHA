<?php
class ProductAttribute { // <-- Changement de nom ici
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // LISTE
    public function getAttributes(){
        $this->db->query("SELECT a.*, GROUP_CONCAT(av.value SEPARATOR ', ') as values_list 
                          FROM attributes a 
                          LEFT JOIN attribute_values av ON a.id = av.attribute_id 
                          GROUP BY a.id 
                          ORDER BY a.name ASC");
        return $this->db->resultSet();
    }

    // LECTURE
    public function getAttributeById($id){
        $this->db->query("SELECT * FROM attributes WHERE id = :id");
        $this->db->bind(':id', $id);
        $attribute = $this->db->single();

        if($attribute){
            $this->db->query("SELECT * FROM attribute_values WHERE attribute_id = :id ORDER BY id ASC");
            $this->db->bind(':id', $id);
            $attribute->values = $this->db->resultSet();
        }
        return $attribute;
    }

    // CRÉATION
    public function addAttribute($name, $values_array){
        $this->db->query("INSERT INTO attributes (name) VALUES (:name)");
        $this->db->bind(':name', $name);
        
        if($this->db->execute()){
            $attribute_id = $this->db->lastInsertId();
            foreach($values_array as $val){
                $val = trim($val);
                if(!empty($val)){
                    $this->db->query("INSERT INTO attribute_values (attribute_id, value) VALUES (:aid, :val)");
                    $this->db->bind(':aid', $attribute_id);
                    $this->db->bind(':val', $val);
                    $this->db->execute();
                }
            }
            return true;
        }
        return false;
    }

    // MISE À JOUR
    public function updateAttribute($id, $name, $values_array){
        $this->db->query("UPDATE attributes SET name = :name WHERE id = :id");
        $this->db->bind(':name', $name);
        $this->db->bind(':id', $id);
        $this->db->execute();

        // On supprime et on recrée les valeurs
        $this->db->query("DELETE FROM attribute_values WHERE attribute_id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();

        foreach($values_array as $val){
            $val = trim($val);
            if(!empty($val)){
                $this->db->query("INSERT INTO attribute_values (attribute_id, value) VALUES (:aid, :val)");
                $this->db->bind(':aid', $id);
                $this->db->bind(':val', $val);
                $this->db->execute();
            }
        }
        return true;
    }

    // SUPPRESSION
    public function deleteAttribute($id){
        $this->db->query("DELETE FROM attributes WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}