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
        
        // On charge les 4 listes pour les afficher dans la vue
        $data = [
            'categories' => $model->getAll('categories'),
            'types'      => $model->getAll('types'),
            'sizes'      => $model->getAll('sizes'),
            'colors'     => $model->getAll('colors')
        ];

        $this->view('admin/attributes/index', $data);
    }

    public function store() {
        verify_csrf();

        $type = $_POST['type'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $name_en = trim($_POST['name_en'] ?? '');

        // Validation basique
        if (!empty($type) && !empty($name)) {
            $model = new Attribute();
            
            // Si l'anglais est vide, on met le français par défaut (ou on traduit auto côté client)
            if (empty($name_en)) $name_en = $name;

            $model->create($type, $name, $name_en);
            flash('success', ucfirst($type) . ' ajouté avec succès !');
        } else {
            flash('error', 'Le nom est obligatoire.');
        }

        $this->redirect('/admin/attributes');
    }

    public function delete($type, $id) {
        $model = new Attribute();
        $model->delete($type, $id);
        
        flash('success', 'Élément supprimé.');
        $this->redirect('/admin/attributes');
    }
}