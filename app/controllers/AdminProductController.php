<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Attribute;

class AdminProductController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['admin_id'])) $this->redirect('/admin/login');
    }

    public function index() {
        $model = new Product();
        $this->view('admin/products/index', ['products' => $model->findAll()]);
    }

    public function create() {
        $attrModel = new Attribute();
        $this->view('admin/products/create', [
            'categories' => $attrModel->getAll('categories'),
            'sizes' => $attrModel->getAll('sizes'),
            'colors' => $attrModel->getAll('colors')
        ]);
    }

    public function store() {
        verify_csrf();
        
        // Upload Image
        $imageName = '';
        if (!empty($_FILES['image']['name'])) {
            $imageName = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], ROOT_PATH . '/public/uploads/' . $imageName);
        }

        // Conversion tableaux -> string pour la BDD
        $sizes = isset($_POST['sizes']) ? implode(',', $_POST['sizes']) : '';
        $colors = isset($_POST['colors']) ? implode(',', $_POST['colors']) : '';

        $model = new Product();
        $model->create([
            'name' => $_POST['name'],
            'name_en' => $_POST['name_en'],
            'description' => $_POST['description'],
            'description_en' => $_POST['description_en'],
            'price' => $_POST['price'],
            'stock' => $_POST['stock'],
            'category' => $_POST['category'],
            'gender' => $_POST['gender'],
            'sizes' => $sizes,
            'colors' => $colors,
            'image' => $imageName
        ]);

        flash('success', 'Produit créé !');
        $this->redirect('/admin/products');
    }

    public function edit($id) {
        $model = new Product();
        $attrModel = new Attribute();
        
        $this->view('admin/products/edit', [
            'product' => $model->findById($id),
            'categories' => $attrModel->getAll('categories'),
            'sizes' => $attrModel->getAll('sizes'),
            'colors' => $attrModel->getAll('colors')
        ]);
    }

    public function update($id) {
        verify_csrf();
        $model = new Product();
        $current = $model->findById($id);
        
        $imageName = $current['image'];
        if (!empty($_FILES['image']['name'])) {
            $imageName = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], ROOT_PATH . '/public/uploads/' . $imageName);
        }

        $sizes = isset($_POST['sizes']) ? implode(',', $_POST['sizes']) : '';
        $colors = isset($_POST['colors']) ? implode(',', $_POST['colors']) : '';

        $model->update($id, [
            'name' => $_POST['name'],
            'name_en' => $_POST['name_en'],
            'description' => $_POST['description'],
            'description_en' => $_POST['description_en'],
            'price' => $_POST['price'],
            'stock' => $_POST['stock'],
            'category' => $_POST['category'],
            'gender' => $_POST['gender'],
            'sizes' => $sizes,
            'colors' => $colors,
            'image' => $imageName
        ]);

        flash('success', 'Produit modifié !');
        $this->redirect('/admin/products');
    }

    public function delete($id) {
        $model = new Product();
        $model->delete($id);
        flash('success', 'Produit supprimé.');
        $this->redirect('/admin/products');
    }
}