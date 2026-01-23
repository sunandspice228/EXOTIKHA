<?php
class Shop extends Controller {
    private $productModel;
    private $categoryModel;
    private $typeModel;

    public function __construct(){
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->typeModel = $this->model('Type');
    }

    public function index(){
        // Récupération des filtres URL
        $filters = [
            'category_id' => isset($_GET['category']) ? $_GET['category'] : null,
            'gender' => isset($_GET['gender']) ? $_GET['gender'] : null,
            'types' => isset($_GET['types']) ? $_GET['types'] : [],
            // NOUVEAU : On récupère la valeur promo (1 ou null)
            'promo_only' => isset($_GET['promo']) ? $_GET['promo'] : null,
            'price_min' => isset($_GET['min_price']) ? $_GET['min_price'] : 0,
            'price_max' => isset($_GET['max_price']) ? $_GET['max_price'] : 5000,
            'sort' => isset($_GET['sort']) ? $_GET['sort'] : 'newest'
        ];

        // Récupérer les données
        $products = $this->productModel->getProducts($filters);
        $categories = $this->categoryModel->getCategories();
        $types = $this->typeModel->getTypes();

        $data = [
            'title' => 'Shop Collection',
            'products' => $products,
            'categories' => $categories,
            'types' => $types,
            'filters' => $filters
        ];

        $this->view('front/shop/index', $data);
    }

    public function product($id){
        $product = $this->productModel->getProductById($id);
        if(!$product){ redirect('shop'); }
        $gallery = $this->productModel->getGalleryImages($id);
        
        $allRelated = $this->productModel->getProducts(['category_id' => $product->category_id]);
        $related = array_slice(array_filter($allRelated, fn($p) => $p->id != $id), 0, 4);

        $data = ['product' => $product, 'gallery' => $gallery, 'related' => $related, 'title' => $product->name];
        $this->view('front/shop/details', $data);
    }
}