<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}

class Shop extends Controller {
    private $productModel;
    private $categoryModel;
    private $typeModel;
    private $wishlistModel; // Renamed to be consistent
    private $reviewModel;

    public function __construct(){
        // Chargement des modèles
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->typeModel = $this->model('ProductType');
        $this->wishlistModel = $this->model('Wishlist');
        $this->reviewModel = $this->model('Review');
    }

    // =========================================================
    // 1. PAGE BOUTIQUE (CATALOGUE)
    // =========================================================
    public function index(){
        // A. Récupération des Filtres depuis l'URL
        $filters = [
            'search'      => isset($_GET['q']) ? trim($_GET['q']) : (isset($_GET['search']) ? trim($_GET['search']) : ''),
            'category_id' => isset($_GET['category_id']) ? trim($_GET['category_id']) : (isset($_GET['category']) ? trim($_GET['category']) : ''),
            'type_id'     => isset($_GET['type_id']) ? trim($_GET['type_id']) : '',
            'gender'      => isset($_GET['gender']) ? trim($_GET['gender']) : '',
            'promo_only'  => isset($_GET['promo']) || isset($_GET['promo_only']),
            'price_max'   => isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000000,
            'sort'        => isset($_GET['sort']) ? $_GET['sort'] : 'newest'
        ];

        // B. Récupération des produits
        // On s'assure que la méthode existe avant de l'appeler pour éviter une erreur fatale
        $products = method_exists($this->productModel, 'getShopProducts') 
                    ? $this->productModel->getShopProducts($filters) 
                    : [];

        // C. Gestion de la Wishlist (Récupérer les IDs des produits likés)
        $liked_products = [];
        if(isLoggedIn()){
            if(method_exists($this->wishlistModel, 'getUserWishlist')){
                $user_wishlist = $this->wishlistModel->getUserWishlist($_SESSION['user_id']);
                if($user_wishlist){
                    foreach($user_wishlist as $item) { 
                        $liked_products[] = $item->product_id; // Attention: vérifier si c'est 'id' ou 'product_id' dans le retour du modèle
                    }
                }
            }
        }

        // D. Données pour la sidebar (Genres hardcodés ou dynamiques)
        $genres = [
            (object)['name' => 'women'],
            (object)['name' => 'men'],
            (object)['name' => 'unisex']
              ];

        // E. Données pour la Vue
        $data = [
            'title'      => lang('page_shop_title'), // Traduction du titre
            'products'   => $products,
            'categories' => $this->categoryModel->getAllCategories(),
            'types'      => $this->typeModel->getAllTypes(),
            'genres'     => $genres,
            'filters'    => $filters,
            'liked'      => $liked_products
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
            redirect('shop');
            return;
        }

        $id = $product->id; 

        // 2. Récupérer les variantes
        $variants = method_exists($this->productModel, 'getProductVariants') 
                    ? $this->productModel->getProductVariants($id) 
                    : [];

        // 3. Récupérer la galerie (On utilise product_images)
        $gallery = method_exists($this->productModel, 'getProductGallery') 
                    ? $this->productModel->getProductGallery($id) 
                    : [];

        // 4. Récupérer les avis
        $reviews = method_exists($this->reviewModel, 'getReviewsByProductId')
                    ? $this->reviewModel->getReviewsByProductId($id)
                    : [];

        // 5. Produits Similaires (Logique simplifiée : même catégorie)
        $related = [];
        if($product->category_id){
            // On réutilise getShopProducts pour filtrer par catégorie
            // Note: cela retournera tous les produits actifs de la catégorie
            if(method_exists($this->productModel, 'getShopProducts')){
                $relatedAll = $this->productModel->getShopProducts(['category_id' => $product->category_id]);
                // On filtre en PHP pour exclure le produit courant et en garder 4
                foreach($relatedAll as $p){
                    if($p->id != $id){
                        $related[] = $p;
                    }
                    if(count($related) >= 4) break;
                }
            } elseif (method_exists($this->productModel, 'getProductsByCategory')) {
                 $related = $this->productModel->getProductsByCategory($product->category_id);
                 // Filter out current product if logic isn't in model
                 $related = array_filter($related, function($p) use ($id) { return $p->id != $id; });
                 $related = array_slice($related, 0, 4);
            }
        }

        // 6. Vérifier si liké (Wishlist)
        $is_liked = false;
        if(isLoggedIn()){
            // Vérifiez la méthode 'check' ou 'is in wishlist' dans votre modèle
            if(method_exists($this->wishlistModel, 'check')){
                 $is_liked = $this->wishlistModel->check($_SESSION['user_id'], $id);
            }
        }

        // --- TRADUCTION DU TITRE ---
        $displayTitle = $product->name;
        if(isset($_SESSION['lang']) && $_SESSION['lang'] === 'fr' && !empty($product->name_fr)){
            $displayTitle = $product->name_fr;
        }

        $data = [
            'title'    => $displayTitle,
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
        // Si la méthode spécifique n'existe pas, on redirige vers l'index avec le filtre promo
        if(!method_exists($this->productModel, 'getPromoProducts')){
            $_GET['promo_only'] = 1;
            $this->index();
            return;
        }

        $products = $this->productModel->getPromoProducts(50); 

        // Trouver le produit avec la plus grosse réduction (Star Product)
        $starProduct = null;
        $maxPercent = 0;

        if($products){
            foreach($products as $p){
                if($p->price > 0 && $p->promo_price > 0){
                    $percent = round((($p->price - $p->promo_price) / $p->price) * 100);
                    // On injecte le pourcentage directement dans l'objet pour l'affichage
                    $p->discount_percent = $percent;
                    
                    if($percent > $maxPercent){
                        $maxPercent = $percent;
                        $starProduct = $p;
                    }
                }
            }
        }

        $data = [
            'title'        => lang('page_promo_title'),
            'products'     => $products,
            'star_product' => $starProduct,
            'max_percent'  => $maxPercent
        ];

        // Si une vue spécifique existe, on l'utilise, sinon on utilise l'index avec un titre différent
        if(file_exists(APPROOT . '/Views/front/shop/promotions.php')){
             $this->view('front/shop/promotions', $data);
        } else {
             // Fallback: render standard shop index with promo data
             // We need to match the data structure expected by shop/index
             $genres = [
                (object)['name' => 'women'],
                (object)['name' => 'men'],
                (object)['name' => 'unisex']
            ];
             $filters = ['promo_only' => true]; // Minimal filter set for display logic
             
             $indexData = [
                'title'      => lang('page_promo_title'),
                'products'   => $products,
                'categories' => $this->categoryModel->getAllCategories(),
                'types'      => $this->typeModel->getAllTypes(),
                'genres'     => $genres,
                'filters'    => $filters,
                'liked'      => [] // Empty or fetch like in index()
            ];
            $this->view('front/shop/index', $indexData);
        }
    }
}