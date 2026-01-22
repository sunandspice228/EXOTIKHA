<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Attribute;

class AdminProductController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['admin_id'])) {
            $this->redirect('/admin/login');
        }
    }

    public function index() {
        $model = new Product();
        $products = $model->findAll();
        $this->view('admin/products/index', ['products' => $products]);
    }

    public function create() {
        $attrModel = new Attribute();
        $data = [
            'categories' => $attrModel->getAll('categories'),
            'types'      => $attrModel->getAll('types'),
            'sizes'      => $attrModel->getAll('sizes'),
            'colors'     => $attrModel->getAll('colors')
        ];
        $this->view('admin/products/create', $data);
    }

    public function store() {
        verify_csrf();
        $model = new Product();

        // Gestion Image Principale
        $imageName = '';
        if (!empty($_FILES['image']['name'])) {
            $imageName = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], ROOT_PATH . '/public/uploads/' . $imageName);
        }

        $data = [
            'name' => $_POST['name'],
            'name_en' => $_POST['name_en'] ?? '',
            'description' => $_POST['description'],
            'description_en' => $_POST['description_en'] ?? '',
            'price' => $_POST['price'],
            'promo_price' => $_POST['promo_price'] ?? 0,
            'is_promo' => isset($_POST['is_promo']) ? 1 : 0,
            'stock' => $_POST['stock'],
            'image' => $imageName,
            'category' => $_POST['category'],
            'type' => $_POST['type'],
            'sizes' => isset($_POST['sizes']) ? implode(',', $_POST['sizes']) : '',
            'colors' => isset($_POST['colors']) ? implode(',', $_POST['colors']) : ''
        ];

        // ✅ CORRECTION ICI : On récupère l'ID retourné par create()
        $productId = $model->create($data);
        
        // Gestion Galerie (On utilise l'ID récupéré proprement)
        $this->handleGalleryUpload($productId, $model);

        flash('success', 'Produit créé avec succès !');
        $this->redirect('/admin/products');
    }

    public function edit($id) {
        $model = new Product();
        $attrModel = new Attribute();
        
        $product = $model->findById($id);
        if (!$product) $this->redirect('/admin/products');

        $data = [
            'product'    => $product,
            'gallery'    => $model->getGallery($id),
            'categories' => $attrModel->getAll('categories'),
            'types'      => $attrModel->getAll('types'),
            'sizes'      => $attrModel->getAll('sizes'),
            'colors'     => $attrModel->getAll('colors')
        ];
        
        $this->view('admin/products/edit', $data);
    }

    public function update($id) {
        verify_csrf();
        $model = new Product();
        $currentProduct = $model->findById($id);

        $imageName = $currentProduct['image'];
        if (!empty($_FILES['image']['name'])) {
            $imageName = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], ROOT_PATH . '/public/uploads/' . $imageName);
        }

        $data = [
            'name' => $_POST['name'],
            'name_en' => $_POST['name_en'] ?? '',
            'description' => $_POST['description'],
            'description_en' => $_POST['description_en'] ?? '',
            'price' => $_POST['price'],
            'promo_price' => $_POST['promo_price'] ?? 0,
            'is_promo' => isset($_POST['is_promo']) ? 1 : 0,
            'stock' => $_POST['stock'],
            'image' => $imageName,
            'category' => $_POST['category'],
            'type' => $_POST['type'],
            'sizes' => isset($_POST['sizes']) ? implode(',', $_POST['sizes']) : '',
            'colors' => isset($_POST['colors']) ? implode(',', $_POST['colors']) : ''
        ];

        $model->update($id, $data);

        // Gestion Galerie
        $this->handleGalleryUpload($id, $model);

        flash('success', 'Produit mis à jour !');
        $this->redirect('/admin/products');
    }

    public function delete($id) {
        $model = new Product();
        $model->delete($id);
        flash('success', 'Produit supprimé.');
        $this->redirect('/admin/products');
    }

    public function deleteImage($id) {
        $model = new Product();
        $model->deleteGalleryImage($id);
        flash('success', 'Image supprimée de la galerie.');
        header("Location: " . $_SERVER['HTTP_REFERER']); 
        exit;
    }

// Helper privé pour uploader la galerie
    private function handleGalleryUpload($productId, $model) {
        // Vérifier si des fichiers ont été envoyés
        if (empty($_FILES['gallery']['name'][0])) {
            return; // Rien à uploader
        }

        $files = $_FILES['gallery'];
        $count = count($files['name']);
        $uploadDir = ROOT_PATH . '/public/uploads/';

        // Créer le dossier s'il n'existe pas
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        for ($i = 0; $i < $count; $i++) {
            // Vérifier qu'il n'y a pas d'erreur sur ce fichier spécifique
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                
                $tmpName = $files['tmp_name'][$i];
                $name    = basename($files['name'][$i]);
                $ext     = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                
                // Sécurité : Vérifier l'extension
                $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                if (!in_array($ext, $allowed)) {
                    continue; // On saute ce fichier s'il n'est pas une image
                }

                // Générer un nom unique pour éviter d'écraser les fichiers
                $filename = 'gallery_' . $productId . '_' . uniqid() . '.' . $ext;
                $destination = $uploadDir . $filename;

                if (move_uploaded_file($tmpName, $destination)) {
                    $model->addGalleryImage($productId, $filename);
                }
            }
        }
    }

    // ... suite du AdminController ...

    // 6. FORMULAIRE D'ÉDITION
    public function editProduct($id) {
        $prodModel = new Product();
        $product = $prodModel->findById($id);

        if (!$product) {
            flash('error', 'Produit introuvable.');
            $this->redirect('/admin/products');
        }

        // On récupère aussi les attributs pour afficher les cases à cocher
        if (class_exists('App\Models\Attribute')) {
            $attrModel = new \App\Models\Attribute();
            $attributes = $attrModel->getAll();
        } else {
            $attributes = [];
        }

        $this->view('admin/products/edit', [
            'product' => $product,
            'attributes' => $attributes
        ]);
    }

    // 7. METTRE À JOUR LE PRODUIT (POST)
    public function updateProduct() {
        verify_csrf();
        $id = $_POST['product_id'];

        $model = new Product();
        $currentProduct = $model->findById($id); // Pour garder l'ancienne image si pas changée

        // Gestion Image (On garde l'ancienne si on n'en envoie pas une nouvelle)
        $imageName = $currentProduct['image'];
        if (!empty($_FILES['image']['name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], ROOT_PATH . '/public/uploads/' . $imageName);
        }

        // Gestion Attributs (Tableau -> Texte)
        $sizes = isset($_POST['sizes']) ? implode(',', $_POST['sizes']) : '';
        $colors = isset($_POST['colors']) ? implode(',', $_POST['colors']) : '';

        // Préparation des données
        $data = [
            'id' => $id,
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $_POST['price'],
            'old_price' => !empty($_POST['old_price']) ? $_POST['old_price'] : NULL,
            'stock' => $_POST['stock'],
            'category' => $_POST['category'],
            'is_promo' => isset($_POST['is_promo']) ? 1 : 0,
            'image' => $imageName,
            'sizes' => $sizes,
            'colors' => $colors
        ];

        // Requête UPDATE
        $sql = "UPDATE products SET 
                name = :name, 
                description = :description, 
                price = :price, 
                old_price = :old_price, 
                stock = :stock, 
                category = :category, 
                is_promo = :is_promo, 
                image = :image,
                sizes = :sizes,
                colors = :colors
                WHERE id = :id";
        
        $model->getDb()->query($sql, $data);

        flash('success', 'Produit mis à jour avec succès.');
        $this->redirect('/admin/products');
    }
}