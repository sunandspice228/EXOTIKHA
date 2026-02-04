<?php
// ⛔ SÉCURITÉ : Empêche l'accès direct au fichier
if (!defined('APPROOT')) {
    die('Accès interdit');
}

class Admin extends Controller {
    
    // Déclaration des modèles
    private $productModel;
    private $categoryModel;
    private $attributeModel;
    private $typeModel;
    private $userModel;
    private $staffModel;
    private $orderModel;
    private $postModel;
    private $reviewModel;

    public function __construct(){
        // 1. Chargement des Modèles
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->attributeModel = $this->model('ProductAttribute');
        $this->typeModel = $this->model('ProductType');
        $this->userModel = $this->model('User'); // Pour les clients
        $this->staffModel = $this->model('Staff'); // Pour les admins
        $this->orderModel = $this->model('Order');
        $this->postModel = $this->model('Post');
        $this->reviewModel = $this->model('Review');

        // 2. Sécurité Admin
        // Si la méthode n'est pas 'login' (si vous aviez une page login admin séparée), on vérifie les droits
        $url = isset($_GET['url']) ? explode('/', $_GET['url']) : [];
        $method = isset($url[1]) ? $url[1] : 'index';

        if($method != 'login'){
            $this->requireAdmin();
        }
    }

    // 🔒 VÉRIFICATION STRICTE DES DROITS
    private function requireAdmin(){
        if(!isset($_SESSION['user_id'])){
            redirect('users/login');
        }
        // Vérifie si le rôle est admin, super_admin ou editor
        if(!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'super_admin', 'editor'])){
            redirect('pages/index'); 
        }
    }

    // =========================================================
    // 1. DASHBOARD
    // =========================================================

    public function index(){
        $data = [
            'title' => 'Dashboard',
            // KPI
            'revenue' => $this->orderModel->getTotalRevenue() ?? 0,
            'total_orders' => $this->orderModel->countOrders(),
            'pending_orders' => $this->orderModel->countOrders('pending'),
            'total_products' => $this->productModel->countProducts(),
            'low_stock' => $this->productModel->countLowStock(),
            'total_users' => $this->userModel->countCustomers(), 
            
            // Data Tables
            'recent_orders' => $this->orderModel->getRecentOrders(5),
            'monthly_stats' => $this->orderModel->getMonthlySales()
        ];
        $this->view('admin/index', $data);
    }

    // =========================================================
    // 2. GESTION DES PRODUITS
    // =========================================================

    // LISTE
    public function products(){
        $products = $this->productModel->getProducts();
        $this->view('admin/products/index', ['products' => $products]);
    }

    // AJOUTER
    public function products_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // 1. Image Principale
            $imageName = $this->handleImageUpload($_FILES['image']);

            // 2. Galerie (Images Multiples)
            $galleryImages = [];
            if(!empty($_FILES['gallery']['name'][0])){
                $galleryImages = $this->handleGalleryUpload($_FILES['gallery']);
            }

            $data = [
                'name' => trim($_POST['name']),
                'name_fr' => trim($_POST['name_fr']),
                'slug' => $this->createSlug(trim($_POST['name'])),
                'sku' => trim($_POST['sku']),
                'description' => trim($_POST['description']),
                'description_fr' => trim($_POST['description_fr']),
                'price' => (float)$_POST['price'],
                'promo_price' => !empty($_POST['promo_price']) ? (float)$_POST['promo_price'] : 0,
                'stock' => (int)$_POST['stock'], // Stock global si pas de variantes
                'category_id' => $_POST['category_id'],
                'type_id' => !empty($_POST['type_id']) ? $_POST['type_id'] : null,
                'gender' => $_POST['gender'],
                'status' => isset($_POST['status']) ? 'active' : 'draft',
                'image' => $imageName
            ];

            // Enregistrement Produit
            $productId = $this->productModel->addProduct($data);

            if($productId){
                // Enregistrement Galerie
                if(!empty($galleryImages)){
                    $this->productModel->addProductGallery($productId, $galleryImages);
                }
                
                // Enregistrement Variantes (si envoyées via le JS)
                if(isset($_POST['new_variants'])){
                    $this->handleVariants($productId, $_POST['new_variants']);
                }
                
                flash('product_msg', 'Produit ajouté avec succès !', 'alert alert-success');
                redirect('admin/products');
            } else {
                die('Erreur lors de l\'ajout');
            }
        }

        // Chargement de la vue ADD
        $data = [
            'categories' => $this->categoryModel->getCategories(),
            'types' => $this->typeModel->getAllTypes()
        ];
        $this->view('admin/products/add', $data);
    }

    // MODIFIER
    public function products_edit($id){
        $product = $this->productModel->getProductById($id);
        if(!$product) redirect('admin/products');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // 1. Image Principale
            $imageName = $product->image;
            if(!empty($_FILES['image']['name'])){
                $imageName = $this->handleImageUpload($_FILES['image']);
            }

            // 2. Galerie (Ajout aux existantes)
            if(!empty($_FILES['gallery']['name'][0])){
                $newGallery = $this->handleGalleryUpload($_FILES['gallery']);
                if(!empty($newGallery)){
                    $this->productModel->addProductGallery($id, $newGallery);
                }
            }

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'name_fr' => trim($_POST['name_fr']),
                'slug' => $product->slug, // On garde le slug original ou on le recrée
                'sku' => trim($_POST['sku']),
                'description' => trim($_POST['description']),
                'description_fr' => trim($_POST['description_fr']),
                'price' => (float)$_POST['price'],
                'promo_price' => !empty($_POST['promo_price']) ? (float)$_POST['promo_price'] : 0,
                'stock' => (int)$_POST['stock'], // Stock simple
                'category_id' => $_POST['category_id'],
                'type_id' => !empty($_POST['type_id']) ? $_POST['type_id'] : null,
                'gender' => $_POST['gender'],
                'status' => $_POST['status'],
                'image' => $imageName
            ];

            if($this->productModel->updateProduct($data)){
                
                // Mise à jour stock variantes existantes
                if(isset($_POST['existing_variants'])){
                    foreach($_POST['existing_variants'] as $vid => $vstock){
                        $this->productModel->updateVariantStock($vid, $vstock);
                    }
                }

                // Ajout nouvelles variantes (via JS dans edit.php)
                if(isset($_POST['variants_size'])){
                    // Recomposition du tableau car envoyé en vecteurs séparés par le JS
                    $newVariants = [];
                    for($i=0; $i<count($_POST['variants_size']); $i++){
                        $newVariants[] = [
                            'size' => $_POST['variants_size'][$i],
                            'color' => $_POST['variants_color'][$i],
                            'stock' => $_POST['variants_stock'][$i]
                        ];
                    }
                    $this->productModel->addProductVariants($id, $newVariants);
                }

                flash('product_msg', 'Produit modifié avec succès !', 'alert alert-success');
                redirect('admin/products');
            }
        }

        // Chargement de la vue EDIT
        $data = [
            'product' => $product,
            'categories' => $this->categoryModel->getCategories(),
            'types' => $this->typeModel->getAllTypes(),
            'gallery' => $this->productModel->getProductGallery($id),
            'variants' => $this->productModel->getProductVariants($id)
        ];
        $this->view('admin/products/edit', $data);
    }

    // SUPPRIMER
    public function products_delete($id){
        if($this->productModel->deleteProduct($id)){
            flash('product_msg', 'Produit supprimé.', 'alert alert-warning');
        }
        redirect('admin/products');
    }

    // SUPPRIMER IMAGE GALERIE
    public function gallery_delete($imgId){
        // Récupérer l'ID produit avant de supprimer pour rediriger
        // (Logique simplifiée, on redirige vers la liste si on a pas l'ID produit)
        $this->productModel->deleteGalleryImage($imgId);
        redirect('admin/products'); 
    }

    // SUPPRIMER VARIANTE
    public function variant_delete($varId){
        $this->productModel->deleteVariant($varId);
        // Idéalement rediriger vers la page d'édition précédente
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    // =========================================================
    // 3. GESTION DES CATÉGORIES
    // =========================================================

    public function categories(){
        $categories = $this->categoryModel->getCategories();
        $this->view('admin/categories/index', ['categories' => $categories]);
    }

    public function categories_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            
            $data = [
                'name' => trim($_POST['name']),
                'name_fr' => trim($_POST['name_fr']),
                'slug' => $this->createSlug(trim($_POST['name'])),
                'description' => trim($_POST['description']),
                'description_fr' => trim($_POST['description_fr'])
            ];

            if($this->categoryModel->addCategory($data)){
                flash('category_msg', 'Catégorie ajoutée avec succès', 'alert alert-success');
                redirect('admin/categories');
            } else {
                die('Erreur lors de l\'ajout');
            }
        }
        $this->view('admin/categories/add');
    }

    public function categories_edit($id){
        $category = $this->categoryModel->getCategoryById($id);
        if(!$category) redirect('admin/categories');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'name_fr' => trim($_POST['name_fr']),
                'slug' => $this->createSlug(trim($_POST['name'])),
                'description' => trim($_POST['description']),
                'description_fr' => trim($_POST['description_fr'])
            ];

            if($this->categoryModel->updateCategory($data)){
                flash('category_msg', 'Catégorie modifiée avec succès', 'alert alert-success');
                redirect('admin/categories');
            }
        }
        $this->view('admin/categories/edit', ['category' => $category]);
    }

    public function categories_delete($id){
        if($this->categoryModel->deleteCategory($id)){
            flash('category_msg', 'Catégorie supprimée.', 'alert alert-warning');
        }
        redirect('admin/categories');
    }

    // =========================================================
    // 4. GESTION DES ATTRIBUTS
    // =========================================================

    public function attributes(){
        $attributes = $this->attributeModel->getAllAttributes();
        $this->view('admin/attributes/index', ['attributes' => $attributes]);
    }

    public function attributes_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $data = [
                'name' => trim($_POST['name']),
                'name_fr' => trim($_POST['name_fr']),
                'values_en' => trim($_POST['values_en']), 
                'values_fr' => trim($_POST['values_fr'])
            ];
            if($this->attributeModel->addAttribute($data)){
                flash('attr_msg', 'Attribut ajouté.', 'alert alert-success');
                redirect('admin/attributes');
            }
        }
        $this->view('admin/attributes/add');
    }

    public function attributes_edit($id){
        $attribute = $this->attributeModel->getAttributeById($id);
        if(!$attribute) redirect('admin/attributes');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'name_fr' => trim($_POST['name_fr']),
                'values_en' => trim($_POST['values_en']),
                'values_fr' => trim($_POST['values_fr'])
            ];
            if($this->attributeModel->updateAttribute($data)){
                flash('attr_msg', 'Attribut modifié.', 'alert alert-success');
                redirect('admin/attributes');
            }
        }
        $this->view('admin/attributes/edit', ['attribute' => $attribute]);
    }

    public function attributes_delete($id){
        if($this->attributeModel->deleteAttribute($id)){
            flash('attr_msg', 'Attribut supprimé.', 'alert alert-warning');
        }
        redirect('admin/attributes');
    }

    // =========================================================
    // 5. GESTION DES TYPES
    // =========================================================

    public function types(){
        $types = $this->typeModel->getAllTypes();
        $this->view('admin/types/index', ['types' => $types]);
    }

    public function types_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $data = ['name' => trim($_POST['name']), 'name_fr' => trim($_POST['name_fr'])];
            if($this->typeModel->addType($data)){
                flash('type_msg', 'Type ajouté.', 'alert alert-success');
                redirect('admin/types');
            }
        }
        $this->view('admin/types/add');
    }

    public function types_edit($id){
        $type = $this->typeModel->getTypeById($id);
        if(!$type) redirect('admin/types');
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $data = ['id' => $id, 'name' => trim($_POST['name']), 'name_fr' => trim($_POST['name_fr'])];
            if($this->typeModel->updateType($data)){
                flash('type_msg', 'Type modifié.', 'alert alert-success');
                redirect('admin/types');
            }
        }
        $this->view('admin/types/edit', ['type' => $type]);
    }

    public function types_delete($id){
        if($this->typeModel->deleteType($id)){
            flash('type_msg', 'Type supprimé.', 'alert alert-warning');
        }
        redirect('admin/types');
    }

    // =========================================================
    // 6. GESTION DES COMMANDES
    // =========================================================

    public function orders(){
        $orders = $this->orderModel->getAllOrders();
        $this->view('admin/orders/index', ['orders' => $orders]);
    }

    public function order_details($id){
        $order = $this->orderModel->getOrderById($id);
        if(!$order) redirect('admin/orders');
        
        $items = $this->orderModel->getOrderItems($id);
        
        $this->view('admin/orders/details', [
            'order' => $order, 
            'items' => $items
        ]);
    }

    public function update_status(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $order_id = $_POST['order_id'];
            $status = $_POST['status'];

            if($this->orderModel->updateStatus($order_id, $status)){
                if($status == 'delivered'){
                    $this->orderModel->updatePaymentStatus($order_id, 'paid');
                }
                flash('admin_msg', 'Statut mis à jour.', 'alert alert-success');
            }
            redirect('admin/order_details/' . $order_id);
        }
    }

    // =========================================================
    // 7. GESTION DU STAFF (Utilisateurs Admin)
    // =========================================================

    public function users(){
        $admins = $this->staffModel->getAdmins();
        $this->view('admin/users/index', ['admins' => $admins]);
    }

    public function users_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            
            $imageName = null;
            if(!empty($_FILES['image']['name'])){
                $imageName = $this->handleImageUpload($_FILES['image']);
            }

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => password_hash(trim($_POST['password']), PASSWORD_DEFAULT),
                'role' => $_POST['role'],
                'image' => $imageName
            ];

            if($this->staffModel->findAdminByEmail($data['email'])){
                flash('admin_msg', 'Email déjà utilisé.', 'alert alert-danger');
            } elseif($this->staffModel->addAdmin($data)){
                flash('admin_msg', 'Admin ajouté.', 'alert alert-success');
                redirect('admin/users');
            }
        }
        $this->view('admin/users/add');
    }

    public function users_edit($id){
        $admin = $this->staffModel->getAdminById($id);
        if(!$admin) redirect('admin/users');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            // Utilise l'image existante, ou NULL si elle n'existe pas
$imageName = isset($admin->image) ? $admin->image : null;$imageName = $admin->image;
            if(!empty($_FILES['image']['name'])){
                $imageName = $this->handleImageUpload($_FILES['image']);
            }

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'role' => $_POST['role'],
                'image' => $imageName,
                'password' => !empty($_POST['password']) ? password_hash(trim($_POST['password']), PASSWORD_DEFAULT) : ''
            ];

            if($this->staffModel->updateAdmin($data)){
                // Mise à jour session si soi-même
                if($_SESSION['user_id'] == $id){
                    $_SESSION['user_name'] = $data['name'];
                    $_SESSION['user_image'] = $imageName;
                }
                flash('admin_msg', 'Profil mis à jour.', 'alert alert-success');
                redirect('admin/users');
            }
        }
        $this->view('admin/users/edit', ['admin' => $admin]);
    }

    public function users_delete($id){
        if($id == $_SESSION['user_id']){
            flash('admin_msg', 'Impossible de se supprimer soi-même.', 'alert alert-danger');
            redirect('admin/users');
        }
        if($this->staffModel->deleteAdmin($id)){
            flash('admin_msg', 'Admin supprimé.', 'alert alert-warning');
        }
        redirect('admin/users');
    }

    // =========================================================
    // 8. GESTION DES CLIENTS
    // =========================================================

    public function customers(){
        $customers = $this->userModel->getCustomers();
        $this->view('admin/customers/index', ['customers' => $customers]);
    }

    public function customers_details($id){
        $customer = $this->userModel->getUserById($id);
        if(!$customer) redirect('admin/customers');
        $orders = $this->userModel->getCustomerOrders($id);
        
        $this->view('admin/customers/details', ['customer' => $customer, 'orders' => $orders]);
    }

    public function customers_delete($id){
        if($this->userModel->deleteUser($id)){
            flash('customer_msg', 'Client supprimé.', 'alert alert-danger');
        }
        redirect('admin/customers');
    }

    // =========================================================
    // 9. GESTION DU BLOG
    // =========================================================

    public function blog(){
        $posts = $this->postModel->getPosts();
        $this->view('admin/blog/index', ['posts' => $posts]);
    }

    public function blog_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $imageName = 'default-blog.jpg';
            if(!empty($_FILES['image']['name'])) $imageName = $this->handleImageUpload($_FILES['image']);

            $data = [
                'title' => trim($_POST['title']),
                'title_fr' => trim($_POST['title_fr']),
                'slug' => $this->createSlug(trim($_POST['title'])),
                'content' => trim($_POST['content']),
                'content_fr' => trim($_POST['content_fr']),
                'image' => $imageName,
                'status' => $_POST['status']
            ];

            if($this->postModel->addPost($data)){
                flash('blog_msg', 'Article publié.', 'alert alert-success');
                redirect('admin/blog');
            }
        }
        $this->view('admin/blog/add');
    }

    public function blog_edit($id){
        $post = $this->postModel->getPostById($id);
        if(!$post) redirect('admin/blog');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $imageName = $post->image;
            if(!empty($_FILES['image']['name'])) $imageName = $this->handleImageUpload($_FILES['image']);

            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'title_fr' => trim($_POST['title_fr']),
                'slug' => $this->createSlug(trim($_POST['title'])),
                'content' => trim($_POST['content']),
                'content_fr' => trim($_POST['content_fr']),
                'image' => $imageName,
                'status' => $_POST['status']
            ];

            if($this->postModel->updatePost($data)){
                flash('blog_msg', 'Article mis à jour.', 'alert alert-success');
                redirect('admin/blog');
            }
        }
        $this->view('admin/blog/edit', ['post' => $post]);
    }

    public function blog_delete($id){
        if($this->postModel->deletePost($id)){
            flash('blog_msg', 'Article supprimé.', 'alert alert-warning');
        }
        redirect('admin/blog');
    }

    // =========================================================
    // 10. GESTION DES AVIS
    // =========================================================

    public function reviews(){
        $reviews = $this->reviewModel->getAllReviews();
        $this->view('admin/reviews/index', ['reviews' => $reviews]);
    }

    public function reviews_approve($id){
        $this->reviewModel->updateStatus($id, 'approved');
        flash('review_msg', 'Avis approuvé.', 'alert alert-success');
        redirect('admin/reviews');
    }

    public function reviews_pending($id){
        $this->reviewModel->updateStatus($id, 'pending');
        flash('review_msg', 'Avis masqué.', 'alert alert-warning');
        redirect('admin/reviews');
    }

    public function reviews_delete($id){
        $this->reviewModel->deleteReview($id);
        flash('review_msg', 'Avis supprimé.', 'alert alert-danger');
        redirect('admin/reviews');
    }

    // =========================================================
    // 11. HELPERS (MÉTHODES PRIVÉES)
    // =========================================================

    // Création de slug URL
    private function createSlug($string) {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9 -]/', '', $string);
        $string = str_replace(' ', '-', $string);
        return $string . '-' . rand(100,999);
    }

    // Upload Image Unique
    private function handleImageUpload($file){
        if(empty($file['name'])){ return 'no_image.jpg'; }
        
        // Utilisation de APPROOT pour le chemin physique
        $targetDir = APPROOT . '/../public/uploads/'; 
        if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if(in_array($fileExt, $allowed)){
            $newName = uniqid('img_', true) . '.' . $fileExt;
            if(move_uploaded_file($file['tmp_name'], $targetDir . $newName)){
                return $newName;
            }
        }
        return 'no_image.jpg';
    }

    // Upload Galerie (Multiple)
    private function handleGalleryUpload($files){
        $uploadedImages = [];
        $targetDir = APPROOT . '/../public/uploads/';
        if(!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        foreach($files['name'] as $key => $value){
            if(!empty($files['name'][$key])){
                $fileExt = strtolower(pathinfo($files['name'][$key], PATHINFO_EXTENSION));
                if(in_array($fileExt, $allowed)){
                    $newName = uniqid('gal_', true) . '.' . $fileExt;
                    if(move_uploaded_file($files['tmp_name'][$key], $targetDir . $newName)){
                        $uploadedImages[] = $newName;
                    }
                }
            }
        }
        return $uploadedImages;
    }

    // Gestion des Variantes (formatage du tableau)
    private function handleVariants($productId, $variantsData){
        $variants = [];
        if(is_array($variantsData)){
            foreach($variantsData as $v){
                if(!empty($v['size'])){
                    $variants[] = [
                        'size' => trim($v['size']),
                        'color' => !empty($v['color']) ? trim($v['color']) : null,
                        'stock' => !empty($v['stock']) ? (int)$v['stock'] : 0
                    ];
                }
            }
        }
        if(!empty($variants)){
            $this->productModel->addProductVariants($productId, $variants);
        }
    }

    // Changer la langue
    public function set_lang($lang = 'en'){
        if(in_array($lang, ['en', 'fr'])){
            $_SESSION['lang'] = $lang;
        }
        $referer = $_SERVER['HTTP_REFERER'] ?? URLROOT . '/admin';
        header('Location: ' . $referer);
        exit;
    }
}