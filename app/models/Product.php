<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}

class Product {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // =========================================================
    // 1. LECTURE (READ)
    // =========================================================

    // ADMIN : Récupérer tous les produits
    public function getProducts(){
        $this->db->query("SELECT p.*, c.name as category_name, t.name as type_name
                          FROM products p
                          LEFT JOIN categories c ON p.category_id = c.id
                          LEFT JOIN product_types t ON p.type_id = t.id
                          ORDER BY p.created_at DESC");
        return $this->db->resultSet();
    }

    // Récupérer un produit par ID
    public function getProductById($id){
        $this->db->query("SELECT * FROM products WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Récupérer un produit par Slug (Pour le Front-End)
    public function getProductBySlug($slug){
        $this->db->query("SELECT p.*, c.name as category_name 
                          FROM products p
                          LEFT JOIN categories c ON p.category_id = c.id
                          WHERE p.slug = :slug AND p.status = 'active'");
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    // =========================================================
    // 2. ÉCRITURE (CREATE / UPDATE / DELETE)
    // =========================================================

    public function addProduct($data){
        // Génération SKU si non fourni
        $sku = !empty($data['sku']) ? $data['sku'] : strtoupper(substr($data['name'], 0, 3)) . rand(1000, 9999);

        $this->db->query('INSERT INTO products (
            sku, name, name_fr, slug, category_id, type_id, gender, 
            description, description_fr, price, promo_price, stock, image, status, created_at
        ) VALUES (
            :sku, :name, :name_fr, :slug, :category_id, :type_id, :gender, 
            :description, :description_fr, :price, :promo_price, :stock, :image, :status, NOW()
        )');

        // Liaison des paramètres (Binding)
        $this->db->bind(':sku', $sku);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':name_fr', !empty($data['name_fr']) ? $data['name_fr'] : null);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':type_id', $data['type_id']); // Peut être null
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':description_fr', !empty($data['description_fr']) ? $data['description_fr'] : null);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':promo_price', $data['promo_price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':status', $data['status']);

        if($this->db->execute()){
            return $this->db->lastInsertId(); // Retourne l'ID pour la galerie
        } else {
            return false;
        }
    }

    public function updateProduct($data){
        $this->db->query('UPDATE products SET 
                            name = :name, 
                            name_fr = :name_fr,
                            slug = :slug,
                            sku = :sku,
                            category_id = :category_id, 
                            type_id = :type_id, 
                            gender = :gender, 
                            description = :description, 
                            description_fr = :description_fr,
                            price = :price, 
                            promo_price = :promo_price, 
                            stock = :stock,
                            image = :image,
                            status = :status
                          WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':name_fr', !empty($data['name_fr']) ? $data['name_fr'] : null);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':sku', $data['sku']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':type_id', $data['type_id']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':description_fr', !empty($data['description_fr']) ? $data['description_fr'] : null);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':promo_price', $data['promo_price']);
        $this->db->bind(':stock', $data['stock']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':status', $data['status']);
        
        return $this->db->execute();
    }

    public function deleteProduct($id){
        // 1. Supprimer les images de la galerie
        $this->db->query('DELETE FROM product_images WHERE product_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // 2. Supprimer les variantes (si utilisées)
        // $this->db->query('DELETE FROM product_variants WHERE product_id = :id');
        // $this->db->bind(':id', $id);
        // $this->db->execute();

        // 3. Supprimer le produit lui-même
        $this->db->query('DELETE FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // =========================================================
    // 3. GESTION DE LA GALERIE (IMAGES MULTIPLES)
    // =========================================================


   

    // =========================================================
    // 4. STATISTIQUES (POUR DASHBOARD)
    // =========================================================

    public function countProducts() {
        $this->db->query("SELECT COUNT(*) as count FROM products");
        $row = $this->db->single();
        return $row->count;
    }

    public function countLowStock($threshold = 5) {
        $this->db->query("SELECT COUNT(*) as count FROM products WHERE stock <= :threshold AND stock > 0");
        $this->db->bind(':threshold', $threshold);
        $row = $this->db->single();
        return $row->count;
    }
    // =========================================================
    // 3. GESTION GALERIE & VARIANTES (Code Manquant)
    // =========================================================

    // Récupérer les variantes (C'est celle qui cause votre erreur actuelle)
    public function getProductVariants($productId){
        $this->db->query("SELECT * FROM product_variants WHERE product_id = :id ORDER BY size ASC");
        $this->db->bind(':id', $productId);
        return $this->db->resultSet();
    }

    // Récupérer la galerie
    public function getProductGallery($productId){
        $this->db->query("SELECT * FROM product_images WHERE product_id = :pid");
        $this->db->bind(':pid', $productId);
        return $this->db->resultSet();
    }

    // Ajouter des images à la galerie
    public function addProductGallery($productId, $images){
        if(!is_array($images)) return false;
        
        foreach($images as $img){
            $this->db->query("INSERT INTO product_images (product_id, image) VALUES (:pid, :img)");
            $this->db->bind(':pid', $productId);
            $this->db->bind(':img', $img);
            $this->db->execute();
        }
        return true;
    }

    // Ajouter des variantes
    public function addProductVariants($product_id, $variants){
        foreach($variants as $variant){
            // On vérifie qu'il y a au moins une taille et du stock
            if(!empty($variant['size'])){
                $this->db->query("INSERT INTO product_variants (product_id, size, color, stock) VALUES (:pid, :size, :color, :stock)");
                $this->db->bind(':pid', $product_id);
                $this->db->bind(':size', $variant['size']);
                $this->db->bind(':color', !empty($variant['color']) ? $variant['color'] : null);
                $this->db->bind(':stock', !empty($variant['stock']) ? $variant['stock'] : 0);
                $this->db->execute();
            }
        }
    }
    
    // Supprimer les variantes (utile pour la mise à jour)
    public function deleteProductVariants($product_id){
        $this->db->query("DELETE FROM product_variants WHERE product_id = :pid");
        $this->db->bind(':pid', $product_id);
        $this->db->execute();
    }
}