<?php
namespace App\Models;

use Core\Model;

class Category extends Model {
    
    public function getAll() {
        return $this->db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
    }

    public function create($name) {
        // On vérifie si ça existe déjà pour éviter les doublons
        $exists = $this->db->query("SELECT id FROM categories WHERE name = ?", [$name])->fetch();
        if(!$exists) {
            $this->db->query("INSERT INTO categories (name) VALUES (?)", [$name]);
        }
    }

    public function delete($id) {
        $this->db->query("DELETE FROM categories WHERE id = ?", [$id]);
    }
}