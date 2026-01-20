<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Attribute;

class HomeController extends Controller {
    
    public function index() {
        $prodModel = new Product();
        $attrModel = new Attribute();

        // Gestion Filtres
        $filters = [
            'category' => $_GET['category'] ?? 'all',
            'type'     => $_GET['type'] ?? '',
            'color'    => $_GET['color'] ?? '',
            'q'        => $_GET['q'] ?? '',
            'is_promo' => $_GET['is_promo'] ?? 0, // Ajout filtre promo direct
            'new'      => $_GET['new'] ?? 0       // Ajout filtre new direct
        ];

        // Détection mode recherche
        $isSearching = !empty($filters['type']) || !empty($filters['color']) || !empty($filters['q']) || $filters['category'] !== 'all' || $filters['is_promo'] || $filters['new'];

        $data = [
            'categories'    => $attrModel->getAll('categories'),
            'sizes'         => $attrModel->getAll('sizes'),
            'colors'        => $attrModel->getAll('colors'),
            'types'         => $attrModel->getAll('types'),
            'activeFilters' => $filters,
            'isSearching'   => $isSearching,
            'title'         => 'Exotikha - Accueil'
        ];

        // --- CHARGEMENT DES DONNÉES CLÉS (Toujours !) ---
        // Le "?? []" protège contre les erreurs si la BDD est vide
        $data['new_arrivals'] = $prodModel->getNewArrivals(8) ?? [];
        $data['promotions']   = $prodModel->getPromotions(8) ?? [];

        // --- CHARGEMENT PRINCIPAL ---
        if ($isSearching) {
            // Si on demande les promos via l'URL
            if ($filters['is_promo']) {
                $data['products'] = $data['promotions'];
            } 
            // Si on demande les nouveautés via l'URL
            elseif ($filters['new']) {
                $data['products'] = $data['new_arrivals'];
            } 
            // Sinon filtre classique
            else {
                $data['products'] = $prodModel->filter($filters);
            }
        } else {
            // Par défaut si pas de recherche : on charge tout pour l'onglet "Tout"
            $data['products'] = $prodModel->findAll() ?? [];
        }

        $this->view('home', $data);
    }

public function product($id) {
        $prodModel = new Product();
        $product = $prodModel->findById($id);

        if (!$product) {
            $this->redirect('/');
        }

        // 1. Récupérer la Galerie (Images multiples)
        $gallery = $prodModel->getGallery($id);
        
        // 2. Récupérer les produits similaires (même catégorie)
        $similar = $prodModel->filter(['category' => $product['category']]);
        // On retire le produit actuel de la liste
        $similar = array_filter($similar ?? [], function($p) use ($id) { return $p['id'] != $id; });

        $this->view('products/show', [
            'product' => $product,
            'gallery' => $gallery, // <-- On envoie la galerie ici
            'similar' => array_slice($similar, 0, 4) // Limite à 4 produits
        ]);
    }

    public function setLang($lang) {
        $_SESSION['lang'] = $lang;
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/')); exit;
    }
    
    public function setCurrency($currency) {
        $_SESSION['currency'] = $currency;
        header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/')); exit;
    }
}