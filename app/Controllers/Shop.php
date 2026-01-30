<?php
class Shop extends Controller {
    private $productModel;
    private $categoryModel;
    private $typeModel;
    private $wishlist;
    private $reviewModel;

    public function __construct(){
        // Chargement des modèles
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->typeModel = $this->model('ProductType');
        $this->wishlist = $this->model('Wishlist');
        $this->reviewModel = $this->model('Review');
    }

    // =========================================================
    // 1. PAGE BOUTIQUE (CATALOGUE)
    // =========================================================
    public function index(){
        // A. Récupération des Filtres depuis l'URL
        $filters = [
            'search'      => isset($_GET['q']) ? trim($_GET['q']) : '',
            // Petite correction ici : on garde une seule clé pour l'ID catégorie
            'category_id' => isset($_GET['category_id']) ? trim($_GET['category_id']) : (isset($_GET['category']) ? trim($_GET['category']) : ''),
            'gender'      => isset($_GET['gender']) ? trim($_GET['gender']) : '',
            'promo_only'  => isset($_GET['promo']) || isset($_GET['promo_only']),
            'price_max'   => isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000000,
            'sort'        => isset($_GET['sort']) ? $_GET['sort'] : 'newest'
        ];

        // B. Récupération des produits (Sécurisé : si la méthode n'existe pas, tableau vide)
        $products = method_exists($this->productModel, 'getShopProducts') 
                    ? $this->productModel->getShopProducts($filters) 
                    : [];

        // C. Gestion de la Wishlist (Récupérer les IDs des produits likés)
        $liked_products = [];
        if(isLoggedIn()){
            // Vérification de sécurité sur la méthode wishlist
            if(method_exists($this->wishlist, 'getUserWishlist')){
                $user_wishlist = $this->wishlist->getUserWishlist($_SESSION['user_id']);
                foreach($user_wishlist as $item) { 
                    $liked_products[] = $item->id; 
                }
            }
        }

        // Sécurité pour les genres
        $genres = method_exists($this->productModel, 'getAllGenres') ? $this->productModel->getAllGenres() : [];

        // D. Données pour la Vue
        $data = [
            'title'      => 'Boutique Exotikha',
            'products'   => $products,
            'categories' => $this->categoryModel->getAllCategories(),
            'types'      => $this->typeModel->getAllTypes(),
            'filters'    => $filters,
            'liked'      => $liked_products,
            'genres'     => $genres,
        ];

        $this->view('front/shop/index', $data);
    }

    // =========================================================
    // 2. PAGE DÉTAIL PRODUIT (VIA SLUG)
    // =========================================================
    public function details($slug = null){
        
        // Sécurité : si pas de slug, retour boutique
        if($slug == null){
            redirect('shop');
        }

        // 1. Récupérer le produit via le SLUG
        $product = $this->productModel->getProductBySlug($slug);

        // Si le slug est incorrect (produit introuvable)
        if(!$product){
            // MODIFICATION : Au lieu de die(), on redirige proprement
            // flash('shop_message', 'Ce produit est introuvable.', 'alert alert-danger'); // Si tu as un flash helper
            redirect('shop');
            return;
        }

        // On a trouvé le produit
        $id = $product->id; 

        // 2. Récupérer les variantes (Sécurisé avec method_exists)
        $variants = method_exists($this->productModel, 'getVariantsByProductId') 
                    ? $this->productModel->getVariantsByProductId($id) 
                    : [];

        // 3. Récupérer la galerie (C'est ici que ça plantait avant -> Sécurisé)
        $gallery = method_exists($this->productModel, 'getGalleryImages') 
                   ? $this->productModel->getGalleryImages($id) 
                   : [];

        // 4. Récupérer les avis
        $reviews = method_exists($this->reviewModel, 'getReviewsByProductId')
                   ? $this->reviewModel->getReviewsByProductId($id)
                   : [];

        // 5. Produits Similaires
        // On vérifie quelle méthode existe dans ton modèle
        if(method_exists($this->productModel, 'getRelatedProducts')){
            $related = $this->productModel->getRelatedProducts($product->category_id, $id);
        } elseif(method_exists($this->productModel, 'getProductsByCategory')){
            $related = $this->productModel->getProductsByCategory($product->category_id, 4);
        } else {
            $related = [];
        }

        // 6. Vérifier si liké (Wishlist)
        $is_liked = false;
        if(isLoggedIn()){
            $is_liked = $this->wishlist->check($_SESSION['user_id'], $id);
        }

        $data = [
            'title'    => $product->name,
            'product'  => $product,
            'variants' => $variants,
            'gallery'  => $gallery,
            'reviews'  => $reviews,
            'related'  => $related,
            'is_liked' => $is_liked
        ];

        $this->view('front/shop/details', $data);
    }

    // =========================================================
    // 3. PAGE PROMOTIONS (VENTES FLASH)
    // =========================================================
    public function promotions(){
        // Sécurité si la méthode n'existe pas encore
        if(!method_exists($this->productModel, 'getPromoProducts')){
            redirect('shop');
            return;
        }

        $products = $this->productModel->getPromoProducts(50); 

        // Trouver le produit avec la plus grosse réduction
        $starProduct = null;
        $maxPercent = 0;

        if($products){
            foreach($products as $p){
                if($p->price > 0 && $p->promo_price > 0){
                    $percent = round((($p->price - $p->promo_price) / $p->price) * 100);
                    $p->discount_percent = $percent;
                    
                    if($percent > $maxPercent){
                        $maxPercent = $percent;
                        $starProduct = $p;
                    }
                }
            }
        }

        $data = [
            'title'        => 'Ventes Flash - Exotikha',
            'products'     => $products,
            'star_product' => $starProduct,
            'max_percent'  => $maxPercent
        ];

        $this->view('front/shop/promotions', $data);
    }
}