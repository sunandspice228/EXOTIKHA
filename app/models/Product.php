<?php
namespace App\Models;

use Core\Model;

class Product extends Model {

    // Récupérer tous les produits
    public function findAll() {
        return $this->db->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
    }

    // Trouver un produit par ID
    public function findById($id) {
        return $this->db->query("SELECT * FROM products WHERE id = :id", ['id' => $id])->fetch();
    }

    // Créer un produit (CORRIGÉ : Retourne l'ID maintenant)
    public function create($data) {
        $sql = "INSERT INTO products (name, name_en, description, description_en, price, promo_price, is_promo, stock, image, category, type, sizes, colors, created_at) 
                VALUES (:name, :name_en, :desc, :desc_en, :price, :promo_price, :is_promo, :stock, :image, :cat, :type, :sizes, :colors, NOW())";
        
        $this->db->query($sql, [
            'name' => $data['name'],
            'name_en' => $data['name_en'] ?? '',
            'desc' => $data['description'],
            'desc_en' => $data['description_en'] ?? '',
            'price' => $data['price'],
            'promo_price' => $data['promo_price'] ?? 0,
            'is_promo' => $data['is_promo'] ?? 0,
            'stock' => $data['stock'],
            'image' => $data['image'],
            'cat' => $data['category'],
            'type' => $data['type'] ?? '',
            'sizes' => $data['sizes'] ?? '',
            'colors' => $data['colors'] ?? ''
        ]);

        // ✅ CORRECTION ICI : On retourne l'ID du nouveau produit
        return $this->db->lastInsertId();
    }

    // Mettre à jour un produit
    public function update($id, $data) {
        $sql = "UPDATE products SET 
                name = :name, name_en = :name_en, 
                description = :desc, description_en = :desc_en,
                price = :price, promo_price = :promo_price, is_promo = :is_promo,
                stock = :stock, image = :image, category = :cat, type = :type,
                sizes = :sizes, colors = :colors 
                WHERE id = :id";

        $this->db->query($sql, [
            'id' => $id,
            'name' => $data['name'],
            'name_en' => $data['name_en'] ?? '',
            'desc' => $data['description'],
            'desc_en' => $data['description_en'] ?? '',
            'price' => $data['price'],
            'promo_price' => $data['promo_price'] ?? 0,
            'is_promo' => $data['is_promo'] ?? 0,
            'stock' => $data['stock'],
            'image' => $data['image'],
            'cat' => $data['category'],
            'type' => $data['type'] ?? '',
            'sizes' => $data['sizes'] ?? '',
            'colors' => $data['colors'] ?? ''
        ]);
    }

    // Supprimer un produit
    public function delete($id) {
        $this->db->query("DELETE FROM products WHERE id = :id", ['id' => $id]);
    }

    // --- FILTRES & FRONT-OFFICE ---

    public function getNewArrivals($limit = 8) {
        return $this->db->query("SELECT * FROM products ORDER BY created_at DESC LIMIT $limit")->fetchAll();
    }

    public function getPromotions($limit = 8) {
        return $this->db->query("SELECT * FROM products WHERE is_promo = 1 ORDER BY created_at DESC LIMIT $limit")->fetchAll();
    }

    public function filter($filters) {
        $sql = "SELECT * FROM products WHERE 1=1";
        $params = [];

        if (!empty($filters['category']) && $filters['category'] !== 'all') {
            $sql .= " AND category = :cat";
            $params['cat'] = $filters['category'];
        }
        if (!empty($filters['type'])) {
            $sql .= " AND type = :type";
            $params['type'] = $filters['type'];
        }
        if (!empty($filters['color'])) {
            $sql .= " AND colors LIKE :color";
            $params['color'] = '%' . $filters['color'] . '%';
        }
        if (!empty($filters['q'])) {
            $sql .= " AND (name LIKE :q OR description LIKE :q)";
            $params['q'] = '%' . $filters['q'] . '%';
        }

        return $this->db->query($sql, $params)->fetchAll();
    }

    // --- GESTION GALERIE ---

    public function getGallery($productId) {
        return $this->db->query("SELECT * FROM product_images WHERE product_id = ?", [$productId])->fetchAll();
    }

    public function addGalleryImage($productId, $filename) {
        $this->db->query("INSERT INTO product_images (product_id, image) VALUES (?, ?)", [$productId, $filename]);
    }

    public function deleteGalleryImage($imageId) {
        $img = $this->db->query("SELECT image FROM product_images WHERE id = ?", [$imageId])->fetch();
        if ($img) {
            $path = ROOT_PATH . '/public/uploads/' . $img['image'];
            if (file_exists($path)) unlink($path);
            $this->db->query("DELETE FROM product_images WHERE id = ?", [$imageId]);
        }
    }
}