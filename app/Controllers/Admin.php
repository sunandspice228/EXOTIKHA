<?php
class Admin extends Controller {
    // Déclaration des modèles
    private $productModel;
    private $categoryModel;
    private $attributeModel;
    private $typeModel;
    private $userModel;
    private $orderModel;
    private $postModel;
    private $reviewModel;
    private $newsletterModel;
    private $db; 

    public function __construct(){
        // Chargement des modèles
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->attributeModel = $this->model('ProductAttribute');
        $this->typeModel = $this->model('ProductType');
        $this->userModel = $this->model('User');
        $this->orderModel = $this->model('Order');
        $this->postModel = $this->model('Post');
        $this->reviewModel = $this->model('Review');
        $this->newsletterModel = $this->model('Newsletter');
        
        $this->db = new Database;
    }

    // Protection : Redirige si non connecté
    private function requireAdmin(){
        if(!isset($_SESSION['admin_id'])){
            redirect('admin/login');
        }
    }

    // =========================================================
    // 1. AUTHENTIFICATION (LOGIN / LOGOUT)
    // =========================================================

    public function login(){
        // Si déjà connecté, direction Dashboard
        if(isset($_SESSION['admin_id'])){
            redirect('admin');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            // Validation
            if(empty($data['email'])){ $data['email_err'] = 'Email requis'; }
            if(empty($data['password'])){ $data['password_err'] = 'Mot de passe requis'; }

            if(empty($data['email_err']) && empty($data['password_err'])){
                // Tentative de connexion via le Modèle User
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if($loggedInUser){
                    // Création de session
                    $_SESSION['admin_id'] = $loggedInUser->id;
                    $_SESSION['admin_email'] = $loggedInUser->email;
                    $_SESSION['admin_name'] = $loggedInUser->name;
                    $_SESSION['user_role'] = 'admin';
                    
                    redirect('admin');
                } else {
                    $data['password_err'] = 'Email ou mot de passe incorrect';
                    $this->view('admin/login', $data);
                }
            } else {
                $this->view('admin/login', $data);
            }
        } else {
            $data = ['email' => '', 'password' => '', 'email_err' => '', 'password_err' => ''];
            $this->view('admin/login', $data);
        }
    }

    public function logout(){
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_email']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        redirect('admin/login');
    }

    // =========================================================
    // 2. DASHBOARD (ACCUEIL)
    // =========================================================

    public function index(){
        $this->requireAdmin();

        // Récupération des stats via les modèles
        $nbProducts = $this->productModel->countProducts();
        $stockValue = $this->productModel->getStockValue();
        $outOfStock = $this->productModel->countOutOfStock();
        $nbCustomers = $this->userModel->countCustomers();
        $nbNewsletter = $this->newsletterModel->countSubscribers();
        $totalRevenue = $this->orderModel->getTotalRevenue();
        $totalOrders = $this->orderModel->countOrders();
        $totalProducts = $this->productModel->countProducts();
        
        // 2. Commandes Récentes (Les 5 dernières)
        $recentOrders = $this->orderModel->getOrders(5); 

        // 3. Stats Mensuelles pour le Graphique (6 derniers mois)
        $monthlyStats = $this->orderModel->getMonthlyStats();
        
        $currentYear = date('Y');
        $monthlyStats = $this->orderModel->getMonthlySales();

        $data = [
            'title' => 'Dashboard',
            'revenue' => $this->orderModel->getTotalRevenue(),
            'total_orders' => $this->orderModel->countOrders(),
            'total_products' => $nbProducts,
            'total_customers' => $nbCustomers,
            'total_newsletter' => $nbNewsletter,
            'stock_value' => $stockValue,
            'out_of_stock' => $outOfStock,
            'monthly_stats' => $monthlyStats,
            'current_year' => $currentYear,
            'recent_orders' => $recentOrders,
            'latest_orders' => $this->orderModel->getRecentOrders(5), // 5 dernières commandes
            'low_stock' => $this->productModel->getLowStockProducts(5) // Produits bientôt épuisés
        ];

        $this->view('admin/index', $data);
    }

    // Dans app/Controllers/Admin.php

// Dans app/Controllers/Admin.php

public function orders(){
    $orders = $this->orderModel->getAllOrders();

    $data = [
        'orders' => $orders
    ];

    // MODIFICATION ICI : On pointe vers le sous-dossier 'orders/index'
    $this->view('admin/orders/index', $data);
}
// Dans app/Controllers/Admin.php

// 1. AFFICHER LES DÉTAILS
public function order_details($id){
    // Récupérer les infos générales de la commande (avec email client)
    $order = $this->orderModel->getOrderById($id);
    
    // Récupérer les articles de la commande
    $items = $this->orderModel->getOrderItems($id);

    // Si la commande n'existe pas
    if(!$order){
        redirect('admin/orders');
    }

    $data = [
        'order' => $order,
        'items' => $items
    ];

    $this->view('admin/orders/details', $data);
}

// 2. METTRE À JOUR LE STATUT (Formulaire Quick Action)
public function update_status(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // On récupère les données du formulaire
        $orderId = $_POST['order_id'];
        $status = $_POST['status'];

        if($this->orderModel->updateStatus($orderId, $status)){
            // Message flash (optionnel)
            // flash('admin_msg', 'Statut mis à jour');
            redirect('admin/order_details/' . $orderId);
        } else {
            die('Erreur lors de la mise à jour');
        }
    }
}
    // =========================================================
    // 3. GESTION DES PRODUITS
    // =========================================================
// ... code existant ...

    // GESTION DES PRODUITS
    public function products(){
        // On récupère les produits via le modèle
        $products = $this->productModel->getProductsAdmin();

        $data = [
            'products' => $products
        ];

        // On charge la vue qui est dans le sous-dossier products
        $this->view('admin/products/index', $data);
    }
    
    // ... suite du code ...
    // In app/Controllers/Admin.php

    // In app/Controllers/Admin.php

public function product_details($id){
    // 1. Get Product Info (with Category & Type names)
    $product = $this->productModel->getProductByIdAdmin($id);
    
    // 2. Get Gallery Images
    $gallery = $this->productModel->getProductGallery($id);
    
    // 3. Get Variants
    $variants = $this->productModel->getProductVariants($id);

    // Security check: if product doesn't exist
    if(!$product){
        flash('product_msg', 'Product not found', 'alert-danger');
        redirect('admin/products');
    }

    $data = [
        'product' => $product,
        'gallery' => $gallery,
        'variants' => $variants
    ];

    $this->view('admin/products/details', $data);
}



    public function products_add(){
        $this->requireAdmin();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Génération du Slug
            $name = trim($_POST['name']);
            $slug = $this->createSlug($name);
            if($this->productModel->findProductBySlug($slug)){
                $slug = $slug . '-' . rand(100, 999);
            }

            // Gestion Images
            $mainImage = $this->handleImageUpload($_FILES['image']);
            $galleryImages = [];
            if(!empty($_FILES['gallery']['name'][0])){
                $galleryImages = $this->handleGalleryUpload($_FILES['gallery']);
            }

            // Préparation des données
            $data = [
                'name' => $name,
                'slug' => $slug,
                'sku'  => !empty($_POST['sku']) ? trim($_POST['sku']) : strtoupper(substr($name, 0, 3)) . rand(1000, 9999),
                'category_id' => $_POST['category_id'],
                'type_id' => $_POST['type_id'],
                'gender' => $_POST['gender'],
                'description' => trim($_POST['description']),
                'price' => (float)$_POST['price'],
                'promo_price' => !empty($_POST['promo_price']) ? (float)$_POST['promo_price'] : 0,
                'stock' => (int)$_POST['simple_stock'],
                'status' => $_POST['status'],
                'image' => $mainImage
            ];

            // Insertion en BDD
            $productId = $this->productModel->addProduct($data);

            if($productId){
                // Ajout galerie
                if(!empty($galleryImages)){
                    $this->productModel->addProductGallery($productId, $galleryImages);
                }
                // Ajout variantes (si géré par la vue)
                if(isset($_POST['has_variants']) && isset($_POST['variants'])){
                    $this->productModel->addProductVariants($productId, $_POST['variants']);
                }
                
                flash('product_msg', 'Produit ajouté avec succès');
                redirect('admin/products');
            }
        }

        // Affichage du formulaire
        $data = [
            'categories' => $this->categoryModel->getAllCategories(),
            'types' => $this->typeModel->getAllTypes(),
            'attributes' => $this->attributeModel->getAllAttributes()
        ];
        $this->view('admin/products/add', $data);
    }

    public function products_edit($id){
        $this->requireAdmin();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Gestion Image Principale (Si nouvelle, on remplace)
            $currentProduct = $this->productModel->getProductById($id);
            $imageName = $currentProduct->image; 
            
            if(!empty($_FILES['image']['name'])){
                $uploaded = $this->handleImageUpload($_FILES['image']);
                if($uploaded != 'no_image.jpg'){
                    $imageName = $uploaded;
                }
            }

            // Gestion Galerie (Ajout)
            if(!empty($_FILES['gallery']['name'][0])){
                $galleryImages = $this->handleGalleryUpload($_FILES['gallery']);
                if(!empty($galleryImages)){
                    $this->productModel->addProductGallery($id, $galleryImages);
                }
            }

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'slug' => $this->createSlug(trim($_POST['name'])), // On régénère le slug au cas où le nom change
                'sku' => trim($_POST['sku']),
                'price' => (float)$_POST['price'],
                'promo_price' => (float)$_POST['promo_price'],
                'description' => trim($_POST['description']),
                'category_id' => $_POST['category_id'],
                'type_id' => $_POST['type_id'],
                'gender' => $_POST['gender'],
                'stock' => (int)$_POST['simple_stock'],
                'status' => $_POST['status'],
                'image' => $imageName
            ];

            if($this->productModel->updateProduct($data)){
                flash('product_msg', 'Produit mis à jour');
                redirect('admin/products');
            }
        } else {
            $product = $this->productModel->getProductById($id);
            if(!$product){ redirect('admin/products'); }

            $data = [
                'product' => $product,
                'categories' => $this->categoryModel->getAllCategories(),
                'types' => $this->typeModel->getAllTypes(),
                'attributes' => $this->attributeModel->getAllAttributes(),
                'variants' => $this->productModel->getVariantsByProductId($id)
            ];
            $this->view('admin/products/edit', $data);
        }
    }

    public function products_delete($id){
        $this->requireAdmin();
        if($this->productModel->deleteProduct($id)){
            flash('product_msg', 'Produit supprimé.');
        }
        redirect('admin/products');
    }

    // =========================================================
    // 4. GESTION DES COMMANDES
    // =========================================================

    

    public function orders_details($id){
        $this->requireAdmin();
        $order = $this->orderModel->getOrderById($id);
        if(!$order) redirect('admin/orders');
        
        $items = $this->orderModel->getOrderItems($id);
        $this->view('admin/orders/details', ['order' => $order, 'items' => $items]);
    }

    
// Dans app/Controllers/Admin.php

// Dans app/Models/Product.php

public function getProductsAdmin(){
    // On sélectionne TOUT le produit (p.*)
    // ET le nom de la catégorie (c.name) qu'on renomme en 'category_name'
    $this->db->query("SELECT p.*, c.name as category_name 
                      FROM products p
                      LEFT JOIN categories c ON p.category_id = c.id
                      ORDER BY p.created_at DESC");
                      
    return $this->db->resultSet();
}

// (Optionnel) Si vous avez besoin de supprimer un produit avec ses images
public function deleteProduct($id){
    // D'abord récupérer l'image pour la supprimer du dossier
    $this->db->query("SELECT image FROM products WHERE id = :id");
    $this->db->bind(':id', $id);
    $row = $this->db->single();

    if($this->db->execute()){
        // Suppression physique de l'image
        if(!empty($row->image)){
            $imagePath = '../public/img/' . $row->image;
            if(file_exists($imagePath)){
                unlink($imagePath);
            }
        }
        
        // Suppression en base
        $this->db->query("DELETE FROM products WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    return false;
}
    // =========================================================
    // 5. GESTION DES CATÉGORIES
    // =========================================================

    public function categories(){
        $this->requireAdmin();
        
        // Ajout rapide via la page index
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'])
            ];
            
            if(!empty($data['name'])){
                $this->categoryModel->addCategory($data);
                flash('category_msg', 'Catégorie ajoutée');
                redirect('admin/categories');
            }
        }
        $this->view('admin/categories/index', ['categories' => $this->categoryModel->getAllCategories()]);
    }

    // CORRECTION : Méthode manquante ajoutée
    public function categories_edit($id){
        $this->requireAdmin();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'])
            ];
            
            if($this->categoryModel->updateCategory($data)){
                flash('category_msg', 'Catégorie mise à jour');
            }
        }
        redirect('admin/categories');
    }

    public function categories_delete($id){
        $this->requireAdmin();
        $this->categoryModel->deleteCategory($id);
        redirect('admin/categories');
    }

    // =========================================================
    // 6. GESTION DU STAFF (ADMINISTRATEURS)
    // =========================================================

    public function users(){
        $this->requireAdmin();
        $admins = $this->userModel->getAdmins();
        $this->view('admin/users/index', ['admins' => $admins]);
    }

    public function users_add(){
        $this->requireAdmin();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken(); 
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '', 'email_err' => '', 'password_err' => '', 'confirm_password_err' => ''
            ];

            if(empty($data['email'])){ $data['email_err'] = 'Email requis'; }
            if(empty($data['password'])){ $data['password_err'] = 'Mot de passe requis'; }
            elseif(strlen($data['password']) < 6){ $data['password_err'] = '6 caractères min.'; }
            if($data['password'] != $data['confirm_password']){ $data['confirm_password_err'] = 'Mots de passe non identiques'; }

            if(empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Hachage du mot de passe
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                if($this->userModel->register($data)){
                    flash('admin_msg', 'Administrateur ajouté');
                    redirect('admin/users');
                } else {
                    die('Erreur système');
                }
            } else {
                $this->view('admin/users/add', $data);
            }
        } else {
            $data = ['name'=>'','email'=>'','password'=>'','confirm_password'=>'','name_err'=>'','email_err'=>'','password_err'=>'','confirm_password_err'=>''];
            $this->view('admin/users/add', $data);
        }
    }

    public function users_delete($id){
        $this->requireAdmin();
        if($id == $_SESSION['admin_id']){
            flash('admin_msg', 'Impossible de supprimer votre propre compte.', 'bg-red-50 text-red-600');
            redirect('admin/users');
        }
        $this->userModel->deleteUser($id);
        flash('admin_msg', 'Administrateur supprimé.');
        redirect('admin/users');
    }

    // =========================================================
    // 7. GESTION DES CLIENTS
    // =========================================================

    public function customers(){
        $this->requireAdmin();
        
        // Logique de recherche intégrée dans le contrôleur (ou mieux, via le modèle si mis à jour)
        $sql = "SELECT c.*, 
                       CONCAT(c.first_name, ' ', c.last_name) as full_name,
                       (SELECT COUNT(*) FROM orders WHERE user_id = c.id) as order_count
                FROM customers c";
        
        if(isset($_GET['q']) && !empty($_GET['q'])){
            $search = trim($_GET['q']);
            $sql .= " WHERE c.first_name LIKE '%$search%' OR c.last_name LIKE '%$search%' OR c.email LIKE '%$search%'";
        }
        $sql .= " ORDER BY c.created_at DESC";
        
        $this->db->query($sql);
        $customers = $this->db->resultSet();
        
        $this->view('admin/customers/index', ['customers' => $customers]);
    }

    // =========================================================
    // 8. BLOG & NEWSLETTER
    // =========================================================

    public function blog(){
        $this->requireAdmin();
        $this->view('admin/blog/index', ['posts' => $this->postModel->getPosts()]);
    }

    public function blog_add(){
        $this->requireAdmin();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            
            $imageName = null;
            if(!empty($_FILES['image']['name'])){
                $imageName = $this->handleImageUpload($_FILES['image']);
            }

            $data = [
                'title' => trim($_POST['title']),
                'slug' => $this->createSlug(trim($_POST['title'])),
                'category' => $_POST['category'],
                'content' => trim($_POST['content']),
                'image' => $imageName
            ];
            
            $this->postModel->addPost($data);
            redirect('admin/blog');
        }
        $this->view('admin/blog/add');
    }

    public function blog_delete($id){
        $this->requireAdmin();
        $this->postModel->deletePost($id);
        redirect('admin/blog');
    }

    public function newsletter(){
        $this->requireAdmin();
        $subscribers = $this->newsletterModel->getSubscribers();
        $this->view('admin/newsletter', ['subscribers' => $subscribers]);
    }

    // =========================================================
    // 9. ATTRIBUTS & TYPES
    // =========================================================

    public function attributes(){
        $this->requireAdmin();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            // On envoie Name et Values (Array via explode)
            $valuesArray = explode(',', $_POST['values']);
            $valuesArray = array_map('trim', $valuesArray); // Nettoyage espaces
            
            $this->attributeModel->addAttribute(trim($_POST['name']), $valuesArray);
            redirect('admin/attributes');
        }
        $this->view('admin/attributes/index', ['attributes' => $this->attributeModel->getAllAttributes()]);
    }

    public function attributes_delete($id){
        $this->requireAdmin();
        $this->attributeModel->deleteAttribute($id);
        redirect('admin/attributes');
    }

    public function types(){
        $this->requireAdmin();
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            $data = ['name' => trim($_POST['name'])];
            $this->typeModel->addType($data);
            redirect('admin/types');
        }
        $this->view('admin/types/index', ['types' => $this->typeModel->getAllTypes()]);
    }

    public function types_delete($id){
        $this->requireAdmin();
        $this->typeModel->deleteType($id);
        redirect('admin/types');
    }

    // =========================================================
    // 10. HELPERS (FONCTIONS UTILES)
    // =========================================================

    private function createSlug($string) {
        $slug = strtolower($string);
        $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
        return trim($slug, '-');
    }

    private function handleImageUpload($file){
        if(empty($file['name'])){ return 'no_image.jpg'; }
        $targetDir = "../public/img/";
        // Ajout d'un ID unique pour éviter les doublons
        $fileName = time() . '_' . basename($file["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowTypes = array('jpg','png','jpeg','gif','webp');
        
        if(in_array(strtolower($fileType), $allowTypes)){
            if(move_uploaded_file($file["tmp_name"], $targetFilePath)){
                return $fileName;
            }
        }
        return 'no_image.jpg';
    }

    private function handleGalleryUpload($files){
        $uploadedNames = [];
        $targetDir = "../public/img/";
        $allowTypes = array('jpg','png','jpeg','gif','webp');

        foreach($files['name'] as $key => $val){
            $fileName = basename($files['name'][$key]);
            if(!empty($fileName)){
                $uniqueName = time() . '_' . uniqid() . '_' . $fileName;
                $targetFilePath = $targetDir . $uniqueName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                if(in_array(strtolower($fileType), $allowTypes)){
                    if(move_uploaded_file($files['tmp_name'][$key], $targetFilePath)){
                        $uploadedNames[] = $uniqueName;
                    }
                }
            }
        }
        return $uploadedNames;
    }
    // In app/Models/Product.php

// Get all categories
public function getCategories(){
    $this->db->query("SELECT * FROM categories ORDER BY id DESC");
    return $this->db->resultSet();
}

// Add a new category
public function addCategory($data){
    $this->db->query("INSERT INTO categories (name, description) VALUES (:name, :description)");
    $this->db->bind(':name', $data['name']);
    $this->db->bind(':description', $data['description']);
    return $this->db->execute();
}

// Delete a category
public function deleteCategory($id){
    $this->db->query("DELETE FROM categories WHERE id = :id");
    $this->db->bind(':id', $id);
    return $this->db->execute();
}
}