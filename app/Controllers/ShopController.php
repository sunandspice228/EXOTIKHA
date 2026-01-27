<?php
class Shop extends Controller {
    private $productModel;
    private $categoryModel;
    private $typeModel; // Nécessaire pour les filtres

    public function __construct(){
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->typeModel = $this->model('ProductType'); // Chargement du modèle Type
    }

    public function index(){
        // 1. Récupération des filtres depuis l'URL ($_GET)
        $filters = [
            'category_id' => isset($_GET['category']) ? trim($_GET['category']) : '',
            'gender'      => isset($_GET['gender']) ? trim($_GET['gender']) : '',
            'type_id'     => isset($_GET['type']) ? trim($_GET['type']) : '', // Correction nom variable
            'price_max'   => isset($_GET['price']) ? trim($_GET['price']) : '',
            'search'      => isset($_GET['q']) ? trim($_GET['q']) : '', // Recherche
            'sort'        => isset($_GET['sort']) ? $_GET['sort'] : 'newest'
        ];

        // 2. Appel au modèle Product pour les produits filtrés
        // Utilise getShopProducts() qui gère tous ces filtres (voir Product.php)
        $products = $this->productModel->getShopProducts($filters); 
        
        // 3. Données pour la Sidebar
        $categories = $this->categoryModel->getCategories();
        $types = $this->typeModel->getTypes(); // Utilisation du bon modèle

        $data = [
            'products' => $products,
            'categories' => $categories,
            'types' => $types,
            'filters' => $filters
        ];

        $this->view('front/shop/index', $data);
    }
    
    public function product($id){
        // 1. Récupérer le produit
        $product = $this->productModel->getProductById($id);
        
        if(!$product){ redirect('shop'); }

        // 2. Récupérer la galerie (Correction du nom de la méthode)
        $gallery = $this->productModel->getGalleryImages($id);

        // 3. Récupérer les variantes (CRUCIAL pour le panier : Taille/Couleur)
        $variants = $this->productModel->getVariantsByProductId($id);

        // 4. Produits similaires (Optionnel mais recommandé)
        $related = $this->productModel->getProductsByCategory($product->category_id, 4);

        $data = [
            'product' => $product, 
            'gallery' => $gallery, // Variable renommée pour clarté
            'variants' => $variants,
            'related' => $related
        ];

        $this->view('front/shop/details', $data);
    }
}