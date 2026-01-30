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
        $this->db->query("SELECT * FROM attributes ORDER BY name ASC");
        return $this->db->resultSet();
    }

    public function getAttributeById($id){
        $this->db->query("SELECT * FROM attributes WHERE id = :id");
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();

        // Petite astuce : on prépare les valeurs pour la vue d'édition
        if($row){
            // Si values_list est "S,M,L", on crée un faux objet pour simuler la structure attendue
            // Ou on laisse tel quel si votre vue gère la chaîne.
            // Ici, on retourne l'objet brut de la BDD.
        }
        return $row;
    }

    // =========================================================
    // ÉCRITURE (Aligné avec Admin.php)
    // =========================================================

    // Ajouter un attribut
    // NOTE : On accepte $name et $values séparément comme envoyé par le contrôleur
    public function addAttribute($name, $values){
        
        // Si $values est un tableau (envoyé par explode dans le contrôleur), on le transforme en string pour la BDD
        if(is_array($values)){
            $valuesStr = implode(',', $values);
        } else {
            $valuesStr = $values;
        }

        $this->db->query("INSERT INTO attributes (name, values_list, created_at) VALUES (:name, :vals, NOW())");
        $this->db->bind(':name', $name);
        $this->db->bind(':vals', $valuesStr);
        
        return $this->db->execute();
    }

    // Mettre à jour un attribut
    public function updateAttribute($id, $name, $values){
        
        // Même conversion Array -> String
        if(is_array($values)){
            $valuesStr = implode(',', $values);
        } else {
            $valuesStr = $values;
        }

        $this->db->query("UPDATE attributes SET name = :name, values_list = :vals WHERE id = :id");
        $this->db->bind(':name', $name);
        $this->db->bind(':vals', $valuesStr);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    // Supprimer un attribut
    public function deleteAttribute($id){
        $this->db->query("DELETE FROM attributes WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}