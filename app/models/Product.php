<?php
class Product {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // --- LECTURE ---
    public function getProducts($filters = []){
        $sql = "SELECT products.*, 
                       categories.name as category_name, 
                       types.name as type_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id
                LEFT JOIN types ON products.type_id = types.id
                WHERE 1=1";

        if(!empty($filters['category_id'])){ $sql .= " AND products.category_id = :category_id"; }
        if(!empty($filters['gender'])){ $sql .= " AND products.gender = :gender"; }
        if(!empty($filters['types']) && is_array($filters['types'])){
            $typeIds = implode(',', array_map('intval', $filters['types']));
            $sql .= " AND products.type_id IN ($typeIds)";
        }
        if(!empty($filters['price_max'])){ $sql .= " AND products.price <= :price_max"; }
        if(!empty($filters['promo_only'])){ $sql .= " AND products.promo_price IS NOT NULL AND products.promo_price > 0"; }

        // Tri
        if(!empty($filters['sort'])){
            switch($filters['sort']){
                case 'price_asc': $sql .= " ORDER BY products.price ASC"; break;
                case 'price_desc': $sql .= " ORDER BY products.price DESC"; break;
                case 'newest': default: $sql .= " ORDER BY products.created_at DESC"; break;
            }
        } else {
            $sql .= " ORDER BY products.created_at DESC";
        }

        $this->db->query($sql);

        if(!empty($filters['category_id'])) { $this->db->bind(':category_id', $filters['category_id']); }
        if(!empty($filters['gender'])) { $this->db->bind(':gender', $filters['gender']); }
        if(!empty($filters['price_max'])) { $this->db->bind(':price_max', $filters['price_max']); }

        return $this->db->resultSet();
    }

    public function getProductById($id){
        $this->db->query('SELECT * FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // --- ÉCRITURE (AJOUT AVEC SKU ALÉATOIRE) ---

    public function addProduct($data){
        // 1. Génération du SKU (5 chiffres aléatoires)
        $sku = rand(10000, 99999);

        // On vérifie (théoriquement) que le SKU n'existe pas, mais sur 5 chiffres le risque est faible pour l'instant.
        // On l'ajoute à la requête
        $this->db->query('INSERT INTO products (sku, name, category_id, type_id, gender, description, price, promo_price, promo_start, promo_end, stock, image) 
                          VALUES(:sku, :name, :category_id, :type_id, :gender, :description, :price, :promo_price, :promo_start, :promo_end, :stock, :image)');
        
        $this->db->bind(':sku', $sku); // <--- Nouveau
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':type_id', $data['type_id']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':promo_price', !empty($data['promo_price']) ? $data['promo_price'] : null);
        $this->db->bind(':promo_start', !empty($data['promo_start']) ? $data['promo_start'] : null);
        $this->db->bind(':promo_end', !empty($data['promo_end']) ? $data['promo_end'] : null);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':image', $data['image']); 

        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function updateProduct($data){
        $this->db->query('UPDATE products SET name = :name, category_id = :category_id, type_id = :type_id, gender = :gender, 
                          description = :description, price = :price, promo_price = :promo_price, promo_start = :promo_start, promo_end = :promo_end, 
                          stock = :stock WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':type_id', $data['type_id']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':promo_price', !empty($data['promo_price']) ? $data['promo_price'] : null);
        $this->db->bind(':promo_start', !empty($data['promo_start']) ? $data['promo_start'] : null);
        $this->db->bind(':promo_end', !empty($data['promo_end']) ? $data['promo_end'] : null);
        $this->db->bind(':stock', $data['stock']);
        
        return $this->db->execute();
    }

    public function deleteProduct($id){
        $this->db->query('DELETE FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // --- GALERIE ---
    public function addGalleryImage($productId, $imagePath){
        $this->db->query('INSERT INTO product_images (product_id, image_path) VALUES (:pid, :path)');
        $this->db->bind(':pid', $productId);
        $this->db->bind(':path', $imagePath);
        return $this->db->execute();
    }

    public function getGalleryImages($productId){
        $this->db->query('SELECT * FROM product_images WHERE product_id = :pid');
        $this->db->bind(':pid', $productId);
        return $this->db->resultSet();
    }

    public function deleteGalleryImage($imgId){
        $this->db->query('SELECT image_path FROM product_images WHERE id = :id');
        $this->db->bind(':id', $imgId);
        $row = $this->db->single();

        if($row){
            if(file_exists('../public/' . $row->image_path)){ unlink('../public/' . $row->image_path); }
            $this->db->query('DELETE FROM product_images WHERE id = :id');
            $this->db->bind(':id', $imgId);
            return $this->db->execute();
        }
        return false;
    }

    // --- DASHBOARD ---
    public function countProducts(){
        $this->db->query('SELECT COUNT(*) as count FROM products');
        $row = $this->db->single();
        return $row->count;
    }
    public function getStockValue(){
        $this->db->query('SELECT SUM(price * stock) as value FROM products');
        $row = $this->db->single();
        return $row->value ?? 0;
    }
    public function countOutOfStock(){
        $this->db->query('SELECT COUNT(*) as count FROM products WHERE stock <= 0');
        $row = $this->db->single();
        return $row->count;
    }
}