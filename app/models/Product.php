<?php
namespace App\Models;

use Core\Model;

class Product extends Model
{
    // Récupérer tout (Admin)
    public function findAll()
    {
        return $this->db->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
    }

    // Récupérer un seul
    public function findById($id)
    {
        return $this->db->query("SELECT * FROM products WHERE id = :id", ['id' => $id])->fetch();
    }

    // --- MOTEUR DE RECHERCHE & FILTRES (PUBLIC) ---
    public function filter($filters)
    {
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];

        // Filtre Catégorie
        if (!empty($filters['category']) && $filters['category'] !== 'all') {
            $sql .= " AND category = :cat";
            $params['cat'] = $filters['category'];
        }

        // Filtre Genre
        if (!empty($filters['gender'])) {
            $sql .= " AND gender = :gender";
            $params['gender'] = $filters['gender'];
        }

        // Filtre Taille (LIKE car stocké "S, M, L")
        if (!empty($filters['size'])) {
            $sql .= " AND sizes LIKE :size";
            $params['size'] = '%' . $filters['size'] . '%';
        }

        // Filtre Couleur
        if (!empty($filters['color'])) {
            $sql .= " AND colors LIKE :color";
            $params['color'] = '%' . $filters['color'] . '%';
        }

        // Barre de recherche
        if (!empty($filters['q'])) {
            $sql .= " AND (name LIKE :q OR name_en LIKE :q OR description LIKE :q)";
            $params['q'] = '%' . $filters['q'] . '%';
        }

        $sql .= " ORDER BY created_at DESC";

        return $this->db->query($sql, $params)->fetchAll();
    }

    // --- CRUD ADMIN ---
    
    public function create($data)
    {
        $sql = "INSERT INTO products (name, name_en, description, description_en, price, stock, category, gender, sizes, colors, image, created_at) 
                VALUES (:name, :name_en, :description, :description_en, :price, :stock, :category, :gender, :sizes, :colors, :image, NOW())";
        
        $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE products SET 
                name=:name, name_en=:name_en, 
                description=:description, description_en=:description_en, 
                price=:price, stock=:stock, 
                category=:category, gender=:gender, 
                sizes=:sizes, colors=:colors, 
                image=:image 
                WHERE id=:id";
        
        $data['id'] = $id;
        $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        $this->db->query("DELETE FROM products WHERE id = :id", ['id' => $id]);
    }
}