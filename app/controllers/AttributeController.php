<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Attribute;

class AttributeController extends Controller {

    public function __construct() {
        // Sécurité : Seul l'admin a le droit d'être ici
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
    }

    // 1. AFFICHER LA PAGE
    public function index() {
        $attrModel = new Attribute();
        $attributes = $attrModel->getAll();
        
        $this->view('admin/attributes', ['attributes' => $attributes]);
    }

    // 2. ENREGISTRER (POST)
    public function store() {
        verify_csrf();
        
        // On récupère et nettoie les données
        $name = htmlspecialchars(trim($_POST['name']));
        $type = htmlspecialchars($_POST['type']);
        
        // Note: Comme on a simplifié les couleurs en texte simple, value est NULL
        $value = NULL; 

        $attrModel = new Attribute();
        
        // ON TENTE LA CRÉATION
        if ($attrModel->create($name, $type, $value)) {
            // Succès : Le modèle a retourné TRUE
            flash('success', 'Attribut ajouté avec succès.');
        } else {
            // Echec : Le modèle a retourné FALSE (Doublon)
            flash('error', "Impossible : L'attribut '{$name}' existe déjà dans la catégorie '{$type}'.");
        }

        $this->redirect('/admin/attributes');
    }

    // 3. SUPPRIMER
    public function delete($id) {
        $attrModel = new Attribute();
        $attrModel->delete($id);
        
        flash('success', 'Attribut supprimé.');
        $this->redirect('/admin/attributes');
    }
}