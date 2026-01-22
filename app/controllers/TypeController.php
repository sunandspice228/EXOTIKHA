<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Type;

class TypeController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
    }

    public function index() {
        $model = new Type();
        $types = method_exists($model, 'getAll') ? $model->getAll() : [];
        $this->view('admin/types', ['types' => $types]);
    }

    public function store() {
        verify_csrf();
        $name = $_POST['name'];

        $model = new Type();
        $model->create($name);

        flash('success', 'Type ajouté.');
        $this->redirect('/admin/types');
    }

    public function delete($id) {
        $model = new Type();
        $model->delete($id);
        
        flash('success', 'Type supprimé.');
        $this->redirect('/admin/types');
    }
}