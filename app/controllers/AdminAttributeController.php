<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Attribute;

class AdminAttributeController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['admin_id'])) $this->redirect('/admin/login');
    }

    public function index() {
        $model = new Attribute();
        $this->view('admin/attributes/index', [
            'categories' => $model->getAll('categories'),
            'sizes' => $model->getAll('sizes'),
            'colors' => $model->getAll('colors')
        ]);
    }

    public function store() {
        verify_csrf();
        $model = new Attribute();
        
        $name = trim($_POST['name']);
        $name_en = trim($_POST['name_en']);
        if (empty($name_en)) $name_en = $name;

        if ($model->add($_POST['type'], $name, $name_en)) {
            flash('success', 'Attribut ajouté.');
        } else {
            flash('error', 'Existe déjà.');
        }
        $this->redirect('/admin/attributes');
    }

    public function delete($type, $id) {
        $model = new Attribute();
        $model->delete($type, $id);
        flash('success', 'Supprimé.');
        $this->redirect('/admin/attributes');
    }
    
    // Tu pourras ajouter edit/update ici si besoin (même logique que Product)
    public function edit($type, $id) {
        $model = new Attribute();
        $item = $model->findById($type, $id);
        $this->view('admin/attributes/edit', ['item' => $item, 'type' => $type]);
    }

    public function update($type, $id) {
        verify_csrf();
        $model = new Attribute();
        $model->update($type, $id, $_POST['name'], $_POST['name_en']);
        $this->redirect('/admin/attributes');
    }
}