<?php
class Shop extends Controller {
    private $productModel;
    private $categoryModel;
    private $typeModel;
    private $wishlistModel;
    private $reviewModel;

    public function __construct(){
        // Chargement unique des modèles
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->typeModel = $this->model('ProductType');
        $this->wishlistModel = $this->model('WishlistModel');
        $this->reviewModel = $this->model('Review');
    }

    // =========================================================
    // 1. PAGE BOUTIQUE (CATALOGUE)
    // =========================================================
    public function index(){
        // A. Récupération des Filtres depuis l'URL
        $filters = [
            'search'      => isset($_GET['q']) ? trim($_GET['q']) : '',
            'category_id' => isset($_GET['category']) ? trim($_GET['category']) : '',
            'gender'      => isset($_GET['gender']) ? trim($_GET['gender']) : '',
            'promo_only'  => isset($_GET['promo']),
            'price_max'   => isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000000,
            'sort'        => isset($_GET['sort']) ? $_GET['sort'] : 'newest'
        ];

        // B. Récupération des produits via le Modèle Product
        $products = $this->productModel->getShopProducts($filters);

        // C. Gestion de la WishlistModel (Récupérer les IDs des produits likés)
        $liked_products = [];
        if(isLoggedIn()){
            $user_wishlist = $this->wishlistModel->getUserWishlist($_SESSION['user_id']);
            foreach($user_wishlist as $item) { 
                $liked_products[] = $item->id; 
            }
        }

        // D. Données pour la Vue
        $data = [
            'title'      => 'Boutique Exotikha',
            'products'   => $products,
            'categories' => $this->categoryModel->getCategories(),
            'types'      => $this->typeModel->getTypes(),
            'filters'    => $filters,
            'liked'      => $liked_products
        ];

        $this->view('front/shop/index', $data);
    }

    // =========================================================
    // 2. PAGE DÉTAIL PRODUIT
    // =========================================================
    public function product($id){
        // 1. Récupérer le produit
        $product = $this->productModel->getProductById($id);
        if(!$product){ redirect('shop'); }

        // 2. Récupérer les variantes (Taille/Couleur)
        // Utilise la méthode qu'on a définie dans Product.php
        $variants = $this->productModel->getVariantsByProductId($id);

        // 3. Récupérer la galerie d'images
        $gallery = $this->productModel->getGalleryImages($id);

        // 4. Récupérer les avis du produit
        // Utilise la méthode qu'on a définie dans Review.php
        $reviews = $this->reviewModel->getReviewsByProductId($id);

        // 5. Produits Similaires (Même catégorie)
        $related = $this->productModel->getProductsByCategory($product->category_id, 4);

        // 6. Vérifier si liké
        $is_liked = false;
        if(isLoggedIn()){
            $is_liked = $this->wishlistModel->check($_SESSION['user_id'], $id);
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
        // Utilise la méthode spécifique du modèle
        $products = $this->productModel->getPromoProducts(50); // On en récupère 50 max

        // Trouver le produit avec la plus grosse réduction (pour la bannière)
        $starProduct = null;
        $maxPercent = 0;

        foreach($products as $p){
            if($p->price > 0 && $p->promo_price > 0){
                $percent = round((($p->price - $p->promo_price) / $p->price) * 100);
                // On injecte le pourcentage dans l'objet pour l'affichage facile
                $p->discount_percent = $percent;
                
                if($percent > $maxPercent){
                    $maxPercent = $percent;
                    $starProduct = $p;
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