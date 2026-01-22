<?php
namespace App\Models;

use Core\Model;

class Type extends Model {
    
    public function getAll() {
        return $this->db->query("SELECT * FROM types ORDER BY name ASC")->fetchAll();
    }

    public function create($name) {
        $exists = $this->db->query("SELECT id FROM types WHERE name = ?", [$name])->fetch();
        if(!$exists) {
            $this->db->query("INSERT INTO types (name) VALUES (?)", [$name]);
        }
    }

    public function delete($id) {
        $this->db->query("DELETE FROM types WHERE id = ?", [$id]);
    }
}