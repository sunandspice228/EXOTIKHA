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
}