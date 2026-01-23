<?php
class ShopController extends Controller {
    private $productModel;
    private $categoryModel;

    public function __construct(){
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
    }

    public function index(){
        // Récupération des filtres depuis l'URL ($_GET)
        $filters = [
            'category_id' => isset($_GET['category']) ? trim($_GET['category']) : '',
            'gender'      => isset($_GET['gender']) ? trim($_GET['gender']) : '',
            'type_id'     => isset($_GET['type']) ? trim($_GET['type']) : '',
            'price_max'   => isset($_GET['price']) ? trim($_GET['price']) : ''
        ];

        // Appel au modèle avec les filtres
        $products = $this->productModel->getProducts($filters);
        
        // On a besoin des listes pour construire la sidebar
        $categories = $this->categoryModel->getCategories();
        $types = $this->categoryModel->getAllTypes(); // Assure-toi d'avoir cette méthode dans CategoryModel

        $data = [
            'products' => $products,
            'categories' => $categories,
            'types' => $types,
            'filters' => $filters // On renvoie les filtres pour cocher les cases actives
        ];

        $this->view('front/shop/index', $data);
    }
    
    // ... Laisser la méthode product() comme avant ...
    public function product($id){
        // (Code existant inchangé)
        $product = $this->productModel->getProductById($id);
        $images = $this->productModel->getProductImages($id);
        if(!$product){ redirect('shop'); }
        $data = ['product' => $product, 'images' => $images];
        $this->view('front/shop/details', $data);
    }
}