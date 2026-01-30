<?php
class Product {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }
// Dans app/Models/Product.php
// In app/Models/Product.php

// Get Product by ID (Admin view with joins)
    public function getProductsAdmin(){
        // Cette requête récupère tous les produits ET le nom de leur catégorie
        $this->db->query("SELECT p.*, c.name as category_name 
                          FROM products p
                          LEFT JOIN categories c ON p.category_id = c.id
                          ORDER BY p.created_at DESC");
                          
        return $this->db->resultSet();
    }
    // =========================================================
    // 1. LECTURE (READ) - ADMIN & FRONT
    // =========================================================

    // Récupérer tous les produits (Admin)
    public function getProducts(){
        $this->db->query("SELECT products.*, 
                                 categories.name as category_name, 
                                 product_types.name as type_name
                          FROM products
                          LEFT JOIN categories ON products.category_id = categories.id
                          LEFT JOIN product_types ON products.type_id = product_types.id
                          ORDER BY products.created_at DESC");
        return $this->db->resultSet();
    }

    // Alias pour compatibilité
    public function getAllProducts(){
        return $this->getProducts();
    }

    // Moteur de recherche & Filtres (Boutique Front-Office)
    public function getShopProducts($filters = []){
        $sql = "SELECT products.*, 
                       categories.name as category_name, 
                       product_types.name as type_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id
                LEFT JOIN product_types ON products.type_id = product_types.id
                WHERE products.status = 'active'"; // On affiche que les actifs

        // Filtres dynamiques
        if(!empty($filters['search'])){
            $sql .= " AND (products.name LIKE :search OR products.description LIKE :search)";
        }
        if(!empty($filters['category_id'])){
            $sql .= " AND products.category_id = :category_id";
        }
        if(!empty($filters['gender'])){
            $sql .= " AND products.gender = :gender";
        }
        if(!empty($filters['type_id'])){
            $sql .= " AND products.type_id = :type_id";
        }
        if(!empty($filters['price_max'])){
            $sql .= " AND products.price <= :price_max";
        }
        if(!empty($filters['promo_only'])){
            $sql .= " AND products.promo_price > 0";
        }

        // Tri
        if(!empty($filters['sort'])){
            switch($filters['sort']){
                case 'price_asc': $sql .= " ORDER BY products.price ASC"; break;
                case 'price_desc': $sql .= " ORDER BY products.price DESC"; break;
                default: $sql .= " ORDER BY products.created_at DESC";
            }
        } else {
            $sql .= " ORDER BY products.created_at DESC";
        }

        $this->db->query($sql);
        
        // Liaison
        if(!empty($filters['search'])) { $this->db->bind(':search', '%' . $filters['search'] . '%'); }
        if(!empty($filters['category_id'])) { $this->db->bind(':category_id', $filters['category_id']); }
        if(!empty($filters['gender'])) { $this->db->bind(':gender', $filters['gender']); }
        if(!empty($filters['type_id'])) { $this->db->bind(':type_id', $filters['type_id']); }
        if(!empty($filters['price_max'])) { $this->db->bind(':price_max', $filters['price_max']); }

        return $this->db->resultSet();
    }

    // Récupérer un produit par ID
    public function getProductById($id){
        $this->db->query("SELECT products.*, 
                                 categories.name as category_name,
                                 product_types.name as type_name
                          FROM products
                          LEFT JOIN categories ON products.category_id = categories.id
                          LEFT JOIN product_types ON products.type_id = product_types.id
                          WHERE products.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Récupérer un produit par SLUG (Front-Office Détail)
    public function getProductBySlug($slug){
        $this->db->query("SELECT products.*, 
                                 categories.name as category_name,
                                 product_types.name as type_name
                          FROM products 
                          LEFT JOIN categories ON products.category_id = categories.id
                          LEFT JOIN product_types ON products.type_id = product_types.id
                          WHERE slug = :slug AND status = 'active'");
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    // =========================================================
    // 2. ÉCRITURE (CREATE / UPDATE / DELETE)
    // =========================================================

    public function addProduct($data){
        $sku = !empty($data['sku']) ? $data['sku'] : strtoupper(substr($data['name'], 0, 3)) . rand(1000, 9999);

        $this->db->query('INSERT INTO products (sku, name, slug, category_id, type_id, gender, description, price, promo_price, stock, image, status, created_at) 
                          VALUES(:sku, :name, :slug, :category_id, :type_id, :gender, :description, :price, :promo_price, :stock, :image, :status, NOW())');
        
        $this->db->bind(':sku', $sku);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':type_id', $data['type_id']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':promo_price', !empty($data['promo_price']) ? $data['promo_price'] : null);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':status', isset($data['status']) ? $data['status'] : 'active');

        if($this->db->execute()){
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function updateProduct($data){
        $this->db->query('UPDATE products SET 
                            name = :name, 
                            slug = :slug,
                            category_id = :category_id, 
                            type_id = :type_id, 
                            gender = :gender, 
                            description = :description, 
                            price = :price, 
                            promo_price = :promo_price, 
                            stock = :stock,
                            image = :image,
                            status = :status
                          WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':type_id', $data['type_id']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':promo_price', !empty($data['promo_price']) ? $data['promo_price'] : null);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':status', isset($data['status']) ? $data['status'] : 'active');
        
        return $this->db->execute();
    }

    public function deleteProduct($id){
        // Suppression des images galerie d'abord
        $this->db->query('DELETE FROM product_images WHERE product_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // Suppression des variantes
        $this->db->query('DELETE FROM product_variants WHERE product_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // Suppression produit
        $this->db->query('DELETE FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // =========================================================
    // 3. GALERIE & VARIANTES
    // =========================================================

    public function addProductGallery($productId, $images){
        foreach($images as $img){
            $this->db->query("INSERT INTO product_images (product_id, image) VALUES (:pid, :img)");
            $this->db->bind(':pid', $productId);
            $this->db->bind(':img', $img);
            $this->db->execute();
        }
        return true;
    }

    // Alias pour compatibilité
    public function addGalleryImage($productId, $imageName){
        return $this->addProductGallery($productId, [$imageName]);
    }

    public function getProductImages($productId){
        $this->db->query("SELECT * FROM product_images WHERE product_id = :pid");
        $this->db->bind(':pid', $productId);
        return $this->db->resultSet();
    }

    public function addProductVariants($product_id, $variants){
        $totalStockAdded = 0;
        foreach($variants as $variant){
            if(!empty($variant['stock']) && $variant['stock'] > 0){
                $this->db->query("INSERT INTO product_variants (product_id, size, color, stock) VALUES (:pid, :size, :color, :stock)");
                $this->db->bind(':pid', $product_id);
                $this->db->bind(':size', !empty($variant['size']) ? $variant['size'] : null);
                $this->db->bind(':color', !empty($variant['color']) ? $variant['color'] : null);
                $this->db->bind(':stock', $variant['stock']);
                $this->db->execute();
                $totalStockAdded += $variant['stock'];
            }
        }
        if($totalStockAdded > 0){
            // On met à jour le stock global
            $this->db->query("UPDATE products SET stock = stock + :qty WHERE id = :pid");
            $this->db->bind(':qty', $totalStockAdded);
            $this->db->bind(':pid', $product_id);
            $this->db->execute();
        }
    }

    public function addVariant($data){
        $this->db->query("INSERT INTO product_variants (product_id, size, color, stock) VALUES (:pid, :size, :color, :stock)");
        $this->db->bind(':pid', $data['product_id']);
        $this->db->bind(':size', $data['size']);
        $this->db->bind(':color', $data['color']);
        $this->db->bind(':stock', $data['stock']);
        return $this->db->execute();
    }

    public function getVariantsByProductId($id){
        $this->db->query("SELECT * FROM product_variants WHERE product_id = :id ORDER BY size ASC");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    // =========================================================
    // 4. UTILS & STATS
    // =========================================================

    

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

    public function getLowStockProducts($limit = 5){
        $this->db->query("SELECT * FROM products WHERE stock < 5 AND stock >= 0 ORDER BY stock ASC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function findProductBySlug($slug){
        $this->db->query("SELECT id FROM products WHERE slug = :slug");
        $this->db->bind(':slug', $slug);
        $this->db->single();
        return ($this->db->rowCount() > 0);
    }

    // Helpers pour les listes déroulantes
    public function getAllGenres(){
        $this->db->query("SELECT DISTINCT gender as name FROM products WHERE gender IS NOT NULL AND gender != ''");
        return $this->db->resultSet();
    }

    // Cette méthode utilise maintenant la vraie table product_types
    public function getAllTypes(){
        $this->db->query("SELECT * FROM product_types ORDER BY name ASC");
        return $this->db->resultSet();
    }

    // =========================================================
    // 5. ACCUEIL (NEW ARRIVALS / PROMOS)
    // =========================================================

    public function getNewArrivals($limit = 4){
        $this->db->query("SELECT * FROM products WHERE stock > 0 AND status='active' ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getPromoProducts($limit = 4){
        $this->db->query("SELECT * FROM products WHERE promo_price > 0 AND stock > 0 AND status='active' ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    // Dans app/Models/Product.php

    // ... autres méthodes ...

    // Récupérer les images de la galerie
    public function getGalleryImages($id){
        // Vérifie d'abord si la table existe pour éviter une erreur SQL
        // Note: Idéalement, créez la table (voir Étape 2 ci-dessous)
        try {
            $this->db->query("SELECT * FROM product_images WHERE product_id = :id");
            $this->db->bind(':id', $id);
            return $this->db->resultSet();
        } catch(Exception $e) {
            return []; // Retourne vide si la table n'existe pas encore
        }
    }
    // Dans app/Models/Product.php

public function countProducts(){
    $this->db->query("SELECT COUNT(*) as count FROM products");
    $row = $this->db->single();
    return $row->count;
}

// In app/Models/Product.php

// Get Product by ID (Admin view with joins for names)
public function getProductByIdAdmin($id){
    $this->db->query("SELECT p.*, c.name as category_name, t.name as type_name 
                      FROM products p
                      LEFT JOIN categories c ON p.category_id = c.id
                      LEFT JOIN product_types t ON p.type_id = t.id
                      WHERE p.id = :id");
    $this->db->bind(':id', $id);
    return $this->db->single();
}

// Get Gallery Images
public function getProductGallery($productId){
    $this->db->query("SELECT * FROM product_gallery WHERE product_id = :product_id");
    $this->db->bind(':product_id', $productId);
    return $this->db->resultSet();
}

// Get Variants
public function getProductVariants($productId){
    $this->db->query("SELECT * FROM product_variants WHERE product_id = :product_id");
    $this->db->bind(':product_id', $productId);
    return $this->db->resultSet();
}

}