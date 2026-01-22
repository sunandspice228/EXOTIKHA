<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Category;

class CategoryController extends Controller {

    public function __construct() {
        // Sécurité Admin
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
    }

    public function index() {
        $model = new Category();
        // Si la méthode getAll() n'existe pas encore, on évite le crash
        $categories = method_exists($model, 'getAll') ? $model->getAll() : [];
        $this->view('admin/categories', ['categories' => $categories]);
    }

    public function store() {
        verify_csrf();
        $name = $_POST['name'];
        
        $model = new Category();
        $model->create($name);

        flash('success', 'Catégorie ajoutée.');
        $this->redirect('/admin/categories');
    }

    public function delete($id) {
        $model = new Category();
        $model->delete($id);
        
        flash('success', 'Catégorie supprimée.');
        $this->redirect('/admin/categories');
    }
}