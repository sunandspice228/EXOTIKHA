<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Content; // Modèle pour Blog/Témoignages/Ventes Privées
use App\Models\Review;  // Modèle pour les Avis (si existant)

class HomeController extends Controller {

    // ==========================================
    // 1. PAGE D'ACCUEIL & CATALOGUE
    // ==========================================
    public function index() {
        $productModel = new Product();
        $contentModel = new Content();
        
        // Variables par défaut
        $isSearching = false;
        $title = "Home";
        $products = []; // Liste des produits (si recherche/catégorie)

        // --- A. LOGIQUE DE RECHERCHE & FILTRES ---
        
        // 1. Cas : Recherche par mot-clé
        if (isset($_GET['q']) && !empty($_GET['q'])) {
            $search = htmlspecialchars($_GET['q']);
            $products = $productModel->getAll($search);
            $isSearching = true;
            $title = "Search results for: " . $search;
        } 
        // 2. Cas : Filtrage par Catégorie (Menu)
        elseif (isset($_GET['category'])) {
            $cat = htmlspecialchars($_GET['category']);
            // Note: On suppose que getAll() ou une méthode spécifique gère ça
            // Pour l'instant, on utilise getAll avec le nom de la catégorie comme terme de recherche
            // Ou mieux, tu peux créer une méthode getByCategory($cat) dans le modèle Product
            $products = $productModel->getAll($cat); 
            $isSearching = true;
            $title = strtoupper($cat);
        }
        // 3. Cas : Nouveautés
        elseif (isset($_GET['new'])) {
            $products = $productModel->getNewArrivals(20);
            $isSearching = true;
            $title = "New Arrivals";
        }
        // 4. Cas : Promotions
        elseif (isset($_GET['is_promo'])) {
            $products = $productModel->getPromotions(20);
            $isSearching = true;
            $title = "Sales & Offers";
        }
        // 5. Cas : Filtre Prix (Si implémenté)
        elseif (isset($_GET['min']) && isset($_GET['max'])) {
            // Logique à ajouter dans le modèle si besoin
            $products = $productModel->getAll(); // Placeholder
            $isSearching = true;
            $title = "Filtered by Price";
        }

        // --- B. TRI (SORTING) ---
        if ($isSearching && !empty($products) && isset($_GET['sort'])) {
            $sort = $_GET['sort'];
            usort($products, function($a, $b) use ($sort) {
                if ($sort == 'price_asc') return $a['price'] <=> $b['price'];
                if ($sort == 'price_desc') return $b['price'] <=> $a['price'];
                if ($sort == 'latest') return strtotime($b['created_at']) <=> strtotime($a['created_at']);
                return 0;
            });
        }

        // --- C. DONNÉES DYNAMIQUES (Pour la Home Page normale) ---
        $newArrivals = $productModel->getNewArrivals(8);
        $testimonials = $contentModel->getTestimonials();
        $blogPosts = $contentModel->getLatestBlogPosts(3);

        // --- D. RENDU DE LA VUE ---
        $this->view('home', [
            'title' => $title,
            'isSearching' => $isSearching,
            'products' => $products,       // Grille si recherche
            'newArrivals' => $newArrivals, // Slider Nouveautés
            'testimonials' => $testimonials, // Slider Témoignages
            'blogPosts' => $blogPosts      // Section Blog
        ]);
    }

    // ==========================================
    // 2. PAGE PRODUIT (DÉTAILS)
    // ==========================================
    public function product($id) {
        $productModel = new Product();
        $product = $productModel->findById($id);

        if (!$product) {
            http_response_code(404);
            die("Product not found.");
        }

        // Galerie & Produits similaires
        $gallery = method_exists($productModel, 'getGallery') ? $productModel->getGallery($id) : [];
        
        // Produits similaires (basés sur la catégorie)
        $related = $productModel->getAll($product['category']); 
        $related = array_filter($related, function($p) use ($id) { return $p['id'] != $id; });

        $this->view('product', [
            'product' => $product,
            'gallery' => $gallery,
            'related' => array_slice($related, 0, 4)
        ]);
    }

    // ==========================================
    // 3. INSCRIPTION VENTE PRIVÉE (POST)
    // ==========================================
    public function joinPrivateSale() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = htmlspecialchars($_POST['full_name'] ?? '');
            $phone = htmlspecialchars($_POST['whatsapp_number'] ?? '');

            if(!empty($name) && !empty($phone)) {
                $contentModel = new Content();
                $contentModel->savePrivateSaleLead($name, $phone);
                flash('success', 'Welcome to the club! You have successfully joined the private sales list.');
            } else {
                flash('error', 'Please fill in all fields.');
            }
        }
        $this->redirect('/');
    }
}