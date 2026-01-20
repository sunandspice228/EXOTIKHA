<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Attribute;

class HomeController extends Controller {
    
    public function index() {
        $prodModel = new Product();
        $attrModel = new Attribute();

        // Récupération des filtres URL
        $filters = [
            'category' => $_GET['category'] ?? 'all',
            'gender'   => $_GET['gender'] ?? '',
            'size'     => $_GET['size'] ?? '',
            'color'    => $_GET['color'] ?? '',
            'q'        => $_GET['q'] ?? ''
        ];

        // On passe toutes les données à la vue
        $data = [
            'products'      => $prodModel->filter($filters),
            'categories'    => $attrModel->getAll('categories'),
            'sizes'         => $attrModel->getAll('sizes'),
            'colors'        => $attrModel->getAll('colors'),
            'activeFilters' => $filters,
            'title'         => lang('home')
        ];

        $this->view('home', $data);
    }

    // Page Détail Produit
    public function product($id) {
        $prodModel = new Product();
        $product = $prodModel->findById($id);

        if (!$product) {
            $this->redirect('/');
        }

        $this->view('products/show', ['product' => $product]);
    }

    // Changer la langue (FR/EN)
    public function setLang($lang) {
        if (in_array($lang, ['fr', 'en'])) {
            $_SESSION['lang'] = $lang;
        }
        // Retour à la page précédente
        header("Location: " . $_SERVER['HTTP_REFERER']); 
        exit;
    }
}