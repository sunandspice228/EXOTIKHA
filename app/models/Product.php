<?php
class Product {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. LECTURE (READ)
    // =========================================================

    // Récupérer tous les produits (Pour l'Admin)
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

    // --- C'EST CETTE MÉTHODE QUI MANQUAIT ---
    // Moteur de recherche & Filtres pour la Boutique (Front-Office)
    public function getShopProducts($filters = []){
        $sql = "SELECT products.*, 
                       categories.name as category_name, 
                       product_types.name as type_name
                FROM products
                LEFT JOIN categories ON products.category_id = categories.id
                LEFT JOIN product_types ON products.type_id = product_types.id
                WHERE 1=1"; // Permet d'enchaîner les AND

        // Filtre : Recherche (Barre de recherche)
        if(!empty($filters['search'])){
            $sql .= " AND (products.name LIKE :search OR products.description LIKE :search)";
        }

        // Filtre : Catégorie
        if(!empty($filters['category_id'])){
            $sql .= " AND products.category_id = :category_id";
        }

        // Filtre : Genre (Homme/Femme)
        if(!empty($filters['gender'])){
            $sql .= " AND products.gender = :gender";
        }

        // Filtre : Type de vêtement
        if(!empty($filters['type_id'])){
            $sql .= " AND products.type_id = :type_id";
        }

        // Filtre : Prix Max
        if(!empty($filters['price_max'])){
            $sql .= " AND products.price <= :price_max";
        }

        // Filtre : Promo uniquement
        if(!empty($filters['promo_only']) && $filters['promo_only'] == true){
            $sql .= " AND products.promo_price > 0";
        }

        // Tri (Sorting)
        if(!empty($filters['sort'])){
            switch($filters['sort']){
                case 'price_asc':
                    $sql .= " ORDER BY products.price ASC";
                    break;
                case 'price_desc':
                    $sql .= " ORDER BY products.price DESC";
                    break;
                default: // 'newest'
                    $sql .= " ORDER BY products.created_at DESC";
            }
        } else {
            $sql .= " ORDER BY products.created_at DESC";
        }

        $this->db->query($sql);
        
        // Liaison des paramètres
        if(!empty($filters['search'])) { $this->db->bind(':search', '%' . $filters['search'] . '%'); }
        if(!empty($filters['category_id'])) { $this->db->bind(':category_id', $filters['category_id']); }
        if(!empty($filters['gender'])) { $this->db->bind(':gender', $filters['gender']); }
        if(!empty($filters['type_id'])) { $this->db->bind(':type_id', $filters['type_id']); }
        if(!empty($filters['price_max'])) { $this->db->bind(':price_max', $filters['price_max']); }

        return $this->db->resultSet();
    }

    // Récupérer un produit par son ID (Détails & Edit)
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

    // =========================================================
    // 2. ÉCRITURE (CREATE / UPDATE / DELETE)
    // =========================================================

    public function addProduct($data){
        // Génération d'un SKU automatique si non fourni
        $sku = strtoupper(substr($data['name'], 0, 3)) . rand(1000, 9999);

        $this->db->query('INSERT INTO products (sku, name, category_id, type_id, gender, description, price, promo_price, stock, image, created_at) 
                          VALUES(:sku, :name, :category_id, :type_id, :gender, :description, :price, :promo_price, :stock, :image, NOW())');
        
        $this->db->bind(':sku', $sku);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':type_id', $data['type_id']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':promo_price', !empty($data['promo_price']) ? $data['promo_price'] : null);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':image', $data['image']);

        if($this->db->execute()){
            return $this->db->lastInsertId(); // Retourne l'ID pour ajouter les variantes ensuite
        } else {
            return false;
        }
    }

    public function updateProduct($data){
        $this->db->query('UPDATE products SET 
                            name = :name, 
                            category_id = :category_id, 
                            type_id = :type_id, 
                            gender = :gender, 
                            description = :description, 
                            price = :price, 
                            promo_price = :promo_price, 
                            stock = :stock,
                            image = :image 
                          WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':type_id', $data['type_id']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':promo_price', !empty($data['promo_price']) ? $data['promo_price'] : null);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':image', $data['image']);
        
        return $this->db->execute();
    }

    public function deleteProduct($id){
        // Suppression des variantes d'abord
        $this->db->query('DELETE FROM product_variants WHERE product_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // Suppression des images de la galerie
        $this->db->query('DELETE FROM product_images WHERE product_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // Suppression du produit
        $this->db->query('DELETE FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // =========================================================
    // 3. GESTION DES VARIANTES (PREMIUM FEATURE)
    // =========================================================

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
            $this->updateStockFromVariants($product_id);
        }
    }

    public function getVariantsByProductId($id){
        $this->db->query("SELECT * FROM product_variants WHERE product_id = :id ORDER BY size ASC");
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function updateStockFromVariants($product_id){
        $this->db->query("SELECT SUM(stock) as total FROM product_variants WHERE product_id = :id");
        $this->db->bind(':id', $product_id);
        $row = $this->db->single();
        
        $newStock = $row->total ?? 0;

        $this->db->query("UPDATE products SET stock = :stock WHERE id = :id");
        $this->db->bind(':stock', $newStock);
        $this->db->bind(':id', $product_id);
        $this->db->execute();
    }

    // =========================================================
    // 4. DASHBOARD & STATISTIQUES
    // =========================================================

    public function countProducts(){
        $this->db->query("SELECT COUNT(*) as count FROM products");
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

    public function getLowStockProducts($limit = 5){
        $this->db->query("SELECT * FROM products WHERE stock < 5 AND stock >= 0 ORDER BY stock ASC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // =========================================================
    // 5. FONCTIONS POUR LA BOUTIQUE (FRONT-END)
    // =========================================================

    public function getNewArrivals($limit = 4){
        $this->db->query("SELECT products.*, categories.name as category_name 
                          FROM products 
                          LEFT JOIN categories ON products.category_id = categories.id
                          WHERE stock > 0 
                          ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getPromoProducts($limit = 4){
        $this->db->query("SELECT products.*, categories.name as category_name 
                          FROM products 
                          LEFT JOIN categories ON products.category_id = categories.id
                          WHERE promo_price > 0 
                          ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getProductsByCategory($categoryId, $limit = 4){
        $this->db->query("SELECT * FROM products WHERE category_id = :cat_id AND stock > 0 ORDER BY RAND() LIMIT :limit");
        $this->db->bind(':cat_id', $categoryId);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Galerie d'images
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
}