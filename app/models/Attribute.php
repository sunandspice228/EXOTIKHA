<?php
namespace App\Models;

use Core\Model;

class Attribute extends Model {

    // Récupérer tous les attributs (Triés par type puis par nom)
    public function getAll() {
        return $this->db->query("SELECT * FROM attributes ORDER BY type DESC, name ASC")->fetchAll();
    }

    /**
     * Créer un attribut avec vérification de doublon
     * Retourne TRUE si créé, FALSE si existe déjà
     */
    public function create($name, $type, $value = null) {
        
        // 1. NETTOYAGE
        $name = trim($name); // Enlève les espaces avant/après (" XL " devient "XL")

        // 2. VÉRIFICATION ANTI-DOUBLON
        // On regarde si une ligne a déjà ce nom ET ce type
        $sqlCheck = "SELECT id FROM attributes WHERE name = :name AND type = :type";
        $exists = $this->db->query($sqlCheck, [
            'name' => $name,
            'type' => $type
            ])->fetch();

        // Si on trouve un résultat, c'est un doublon -> On refuse
        if ($exists) {
            return false;
        }

        // 3. INSERTION (Si pas de doublon)
        $sqlInsert = "INSERT INTO attributes (name, type, value) VALUES (:name, :type, :value)";
        $this->db->query($sqlInsert, [
            'name' => $name, 
            'type' => $type, 
            'value' => $value
        ]);

        return true;
    }

    // Supprimer un attribut
    public function delete($id) {
        $this->db->query("DELETE FROM attributes WHERE id = :id", ['id' => $id]);
    }
}