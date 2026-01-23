<?php
class HomeController extends Controller {
    private $productModel;
    private $categoryModel;

    public function __construct(){
        // Charger les modèles pour les afficher sur l'accueil
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
    }

    public function index(){
        // 1. Récupérer les 8 derniers produits (On simule un "limit" en PHP si SQL limit n'est pas dans le model)
        $products = $this->productModel->getProducts(); 
        
        // On ne garde que les 8 premiers (les plus récents car order by DESC dans le model)
        $recentProducts = array_slice($products, 0, 8);

        // 2. Récupérer les catégories
        $categories = $this->categoryModel->getCategories();

        $data = [
            'products' => $recentProducts,
            'categories' => $categories
        ];
        
        $this->view('front/home', $data);
    }
}