<?php
namespace App\Models;

use Core\Model;

class Product extends Model {

    // ============================================================
    // PARTIE PUBLIQUE (SITE)
    // ============================================================

    // 1. Nouveautés (Pour la Home)
    public function getNewArrivals($limit = 8) {
        return $this->db->query("SELECT * FROM products ORDER BY created_at DESC LIMIT $limit")->fetchAll();
    }

    // 2. Promotions (C'est cette méthode qu'il te manquait !)
    public function getPromotions($limit = 4) {
        // On cherche les produits où is_promo = 1 
        // ET la date du jour est comprise entre début et fin
        $sql = "SELECT * FROM products 
                WHERE is_promo = 1 
                AND (promo_start_date IS NULL OR promo_start_date <= NOW())
                AND (promo_end_date IS NULL OR promo_end_date >= NOW())
                ORDER BY created_at DESC LIMIT $limit";
        return $this->db->query($sql)->fetchAll();
    }

    // 3. Produit par ID
    public function findById($id) {
        return $this->db->query("SELECT * FROM products WHERE id = :id", ['id' => $id])->fetch();
    }

    // 4. Galerie Images
    public function getGallery($id) {
        return $this->db->query("SELECT * FROM product_images WHERE product_id = ?", [$id])->fetchAll();
    }


    // ============================================================
    // PARTIE ADMIN (BACK-OFFICE)
    // ============================================================

    // Tous les produits (avec Recherche)
    public function getAll($search = '') {
        if (!empty($search)) {
            $sql = "SELECT * FROM products WHERE name LIKE :search OR id = :search ORDER BY created_at DESC";
            return $this->db->query($sql, ['search' => "%$search%"])->fetchAll();
        } else {
            return $this->db->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
        }
    }

    // Création
    public function create($data) {
        $sql = "INSERT INTO products (name, description, price, promo_price, promo_start_date, promo_end_date, stock, category, type, is_promo, image, sizes, colors, created_at) 
                VALUES (:name, :description, :price, :promo_price, :promo_start_date, :promo_end_date, :stock, :category, :type, :is_promo, :image, :sizes, :colors, NOW())";
        $this->db->query($sql, $data);
    }

    // Mise à jour
    public function update($data) {
        $sql = "UPDATE products SET 
                name = :name, description = :description, 
                price = :price, promo_price = :promo_price, 
                promo_start_date = :promo_start_date, promo_end_date = :promo_end_date,
                stock = :stock, category = :category, type = :type, 
                is_promo = :is_promo, image = :image, sizes = :sizes, colors = :colors
                WHERE id = :id";
        $this->db->query($sql, $data);
    }

    // Suppression
    public function delete($id) {
        $this->db->query("DELETE FROM products WHERE id = ?", [$id]);
    }

    // Bulk Delete
    public function deleteBulk($ids) {
        if(empty($ids)) return;
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $this->db->query("DELETE FROM products WHERE id IN ($placeholders)", $ids);
    }

    // Bulk Promo
    public function updatePromoBulk($ids, $start, $end) {
        if(empty($ids)) return;
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "UPDATE products SET is_promo = 1, promo_start_date = ?, promo_end_date = ? WHERE id IN ($placeholders)";
        $params = array_merge([$start, $end], $ids);
        $this->db->query($sql, $params);
    }

    // Galerie Admin
    public function addGalleryImage($id, $imageName) {
        $this->db->query("INSERT INTO product_images (product_id, image) VALUES (?, ?)", [$id, $imageName]);
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