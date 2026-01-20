<?php
namespace App\Models;

use Core\Model;

class Attribute extends Model {

    // Récupérer tous les attributs d'une table (categories, types, etc.)
    public function getAll($table) {
        // On vérifie que la table est autorisée pour la sécurité
        $allowed = ['categories', 'types', 'sizes', 'colors'];
        if (!in_array($table, $allowed)) return [];

        return $this->db->query("SELECT * FROM $table ORDER BY id DESC")->fetchAll();
    }

    // Ajouter un attribut
    public function create($table, $name, $name_en) {
        $allowed = ['categories', 'types', 'sizes', 'colors'];
        if (!in_array($table, $allowed)) return;

        $sql = "INSERT INTO $table (name, name_en) VALUES (:name, :name_en)";
        $this->db->query($sql, [
            'name' => $name,
            'name_en' => $name_en
        ]);
    }

    // Supprimer un attribut
    public function delete($table, $id) {
        $allowed = ['categories', 'types', 'sizes', 'colors'];
        if (!in_array($table, $allowed)) return;

        $this->db->query("DELETE FROM $table WHERE id = :id", ['id' => $id]);
    }
}