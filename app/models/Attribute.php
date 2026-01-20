<?php
namespace App\Models;

use Core\Model;

class Attribute extends Model
{
    private $allowedTables = ['categories', 'colors', 'sizes'];

    public function getAll($table)
    {
        if (!in_array($table, $this->allowedTables)) return [];
        
        $orderBy = ($table === 'sizes') ? 'sort_order ASC' : 'name ASC';
        return $this->db->query("SELECT * FROM $table ORDER BY $orderBy")->fetchAll();
    }

    public function findById($table, $id)
    {
        if (!in_array($table, $this->allowedTables)) return null;
        return $this->db->query("SELECT * FROM $table WHERE id = :id", ['id' => $id])->fetch();
    }

    public function add($table, $name, $name_en)
    {
        if (!in_array($table, $this->allowedTables)) return false;

        // Vérifier doublon
        $exists = $this->db->query("SELECT id FROM $table WHERE name = :name", ['name' => $name])->fetch();
        
        if (!$exists) {
            $this->db->query("INSERT INTO $table (name, name_en) VALUES (:n, :ne)", ['n' => $name, 'ne' => $name_en]);
            return true;
        }
        return false;
    }

    public function update($table, $id, $name, $name_en)
    {
        if (!in_array($table, $this->allowedTables)) return;
        $this->db->query("UPDATE $table SET name=:n, name_en=:ne WHERE id=:id", ['n' => $name, 'ne' => $name_en, 'id' => $id]);
    }

    public function delete($table, $id)
    {
        if (!in_array($table, $this->allowedTables)) return;
        $this->db->query("DELETE FROM $table WHERE id = :id", ['id' => $id]);
    }
}