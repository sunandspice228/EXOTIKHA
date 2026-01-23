<?php
class Admin extends Controller {
 private $productModel;
    private $categoryModel;
    private $attributeModel;
    private $typeModel; // Nouveau
    private $userModel;
    private $orderModel;
    private $postModel; 
    private $reviewModel;

    public function __construct(){
        // Security Check
        if(!isLoggedIn() || !isAdmin()){
            redirect('users/login');
        }

        // Load Models
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->attributeModel = $this->model('ProductAttribute'); // Modèle Attributs
        $this->typeModel = $this->model('ProductType');           // Modèle Types
        $this->userModel = $this->model('User');
        $this->orderModel = $this->model('Order');
        $this->postModel = $this->model('Post');
        $this->reviewModel = $this->model('Review');
    }

    // =========================================================
    // 1. DASHBOARD & STATS
    // =========================================================
    public function index(){
        $nbProducts = $this->productModel->countProducts();
        $stockValue = $this->productModel->getStockValue();
        $outOfStock = $this->productModel->countOutOfStock();
        $nbCustomers = $this->userModel->countCustomers(); 
        
        $currentYear = date('Y');
        $yearlySales = $this->orderModel->getYearlySales($currentYear);

        $data = [
            'nb_products' => $nbProducts,
            'stock_value' => $stockValue,
            'out_of_stock' => $outOfStock,
            'nb_customers' => $nbCustomers,
            'chart_data' => $yearlySales,
            'current_year' => $currentYear,
            'posts_count' => count($this->postModel->getPosts()),
            'pending_reviews' => count($this->reviewModel->getPendingReviews())
        ];

        $this->view('admin/index', $data);
    }

    // =========================================================
    // 2. PRODUCT MANAGEMENT
    // =========================================================

  public function products(){
        $products = $this->productModel->getProducts();
        $this->view('admin/products/index', ['products' => $products]);
    }

    public function products_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'category_id' => trim($_POST['category_id']),
                'type_id' => !empty($_POST['type_id']) ? trim($_POST['type_id']) : null, // TYPE
                'gender' => trim($_POST['gender']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'promo_price' => trim($_POST['promo_price']),
                'promo_start' => trim($_POST['promo_start']),
                'promo_end' => trim($_POST['promo_end']),
                'stock' => trim($_POST['stock']),
                'image' => '', 
                'error' => ''
            ];

            if(empty($data['name']) || empty($data['price'])) { $data['error'] = 'Champs requis manquants'; }

            // Upload Image Principale
            if(!empty($_FILES['image']['name'])){
                $data['image'] = $this->uploadFile($_FILES['image'], "products/");
            }

            if(empty($data['error'])){
                // 1. Sauvegarde Produit
                $productId = $this->productModel->addProduct($data);
                
                if($productId){
                    // 2. Sauvegarde Galerie
                    if(isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])){
                        $this->handleGalleryUpload($productId, $_FILES['gallery']);
                    }

                    // 3. Sauvegarde Attributs (Nouveau)
                    // On s'attend à $_POST['attributes'] = [ 'id_attribut' => 'Valeur', ... ]
                    if(isset($_POST['attributes'])){
                        foreach($_POST['attributes'] as $attrId => $attrValue){
                            if(!empty($attrValue)){
                                $this->productModel->addProductAttributeValue($productId, $attrId, trim($attrValue));
                            }
                        }
                    }

                    flash('product_message', 'Produit ajouté avec succès !');
                    redirect('admin/products');
                }
            } else {
                // Recharger les listes en cas d'erreur
                $data['categories'] = $this->categoryModel->getCategories();
                $data['types'] = $this->typeModel->getTypes();
                $data['attributes'] = $this->attributeModel->getAttributes();
                $this->view('admin/products/add', $data);
            }
        } else {
            // Affichage Formulaire
            $data = [
                'categories' => $this->categoryModel->getCategories(),
                'types' => $this->typeModel->getTypes(),           // Liste des Types
                'attributes' => $this->attributeModel->getAttributes() // Liste des Attributs
            ];
            $this->view('admin/products/add', $data);
        }
    }

   public function products_edit($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'category_id' => trim($_POST['category_id']),
                'type_id' => !empty($_POST['type_id']) ? trim($_POST['type_id']) : null,
                'gender' => trim($_POST['gender']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'promo_price' => trim($_POST['promo_price']),
                'promo_start' => trim($_POST['promo_start']),
                'promo_end' => trim($_POST['promo_end']),
                'stock' => trim($_POST['stock'])
            ];
            
            if($this->productModel->updateProduct($data)){
                // Update Galerie
                if(isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])){
                    $this->handleGalleryUpload($id, $_FILES['gallery']);
                }

                // Update Attributs (Supprime les anciens et remet les nouveaux)
                $this->productModel->deleteProductAttributes($id);
                if(isset($_POST['attributes'])){
                    foreach($_POST['attributes'] as $attrId => $attrValue){
                        if(!empty($attrValue)){
                            $this->productModel->addProductAttributeValue($id, $attrId, trim($attrValue));
                        }
                    }
                }

                flash('product_message', 'Produit mis à jour');
                redirect('admin/products');
            }
        } else {
            $data = [
                'product' => $this->productModel->getProductById($id),
                'categories' => $this->categoryModel->getCategories(),
                'types' => $this->typeModel->getTypes(),
                'attributes' => $this->attributeModel->getAttributes(),
                'gallery' => $this->productModel->getGalleryImages($id),
                'current_attributes' => $this->productModel->getProductAttributes($id) // Valeurs actuelles
            ];
            $this->view('admin/products/edit', $data);
        }
    }

    // ... (Helpers uploadFile et handleGalleryUpload restent identiques) ...
    // ... (Méthodes attributes() et categories() restent identiques) ...
    
    // Ajoutez ceci pour éviter les erreurs si les helpers sont appelés
    private function uploadFile($file, $folder = ""){
        $targetDir = 'uploads/' . $folder;
        $absDir = APPROOT . '/../public/' . $targetDir;
        if (!file_exists($absDir)) { mkdir($absDir, 0777, true); }
        $fileName = time() . '_' . basename($file['name']);
        if(move_uploaded_file($file["tmp_name"], $absDir . $fileName)){ return $targetDir . $fileName; }
        return 'default.jpg';
    }
    private function handleGalleryUpload($productId, $files){
        $count = count($files['name']);
        for($i=0; $i<$count; $i++){
            if(!empty($files['name'][$i])){
                $file = ['name' => $files['name'][$i], 'tmp_name' => $files['tmp_name'][$i]];
                $path = $this->uploadFile($file, "products/");
                $this->productModel->addGalleryImage($productId, $path);
            }
        }
    }
        

    // =========================================================
    // 3. BLOG MANAGEMENT (NEW)
    // =========================================================
    public function blog(){
        $posts = $this->postModel->getPosts();
        $this->view('admin/blog/index', ['posts' => $posts]);
    }

    public function add_post(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data = [
                'title' => trim($_POST['title']),
                'category' => trim($_POST['category']),
                'content' => $_POST['content'], // HTML from Editor
                'image' => 'blog-default.jpg'
            ];

            if(!empty($_FILES['image']['name'])){
                $data['image'] = $this->uploadFile($_FILES['image'], "blog/");
            }

            if($this->postModel->addPost($data)){
                flash('admin_msg', 'Article publié !');
                redirect('admincontroller/blog');
            }
        } else {
            $this->view('admin/blog/add');
        }
    }

    public function delete_post($id){
        if($this->postModel->deletePost($id)){
            flash('admin_msg', 'Article supprimé');
            redirect('admincontroller/blog');
        }
    }

    // =========================================================
    // 4. REVIEWS MANAGEMENT (NEW)
    // =========================================================
    public function reviews(){
        $reviews = $this->reviewModel->getAllReviews();
        $this->view('admin/reviews/index', ['reviews' => $reviews]);
    }

    public function approve_review($id){
        if($this->reviewModel->updateStatus($id, 'approved')){
            flash('admin_msg', 'Avis approuvé et publié sur l\'accueil.');
            redirect('admincontroller/reviews');
        }
    }

    public function delete_review($id){
        if($this->reviewModel->deleteReview($id)){
            flash('admin_msg', 'Avis supprimé.');
            redirect('admincontroller/reviews');
        }
    }

    // =========================================================
    // 5. ORDERS & CUSTOMERS
    // =========================================================
    public function orders(){
        $orders = $this->orderModel->getOrders();
        $this->view('admin/orders/index', ['orders' => $orders]);
    }

    public function orders_details($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->orderModel->updateStatus($id, $_POST['status']);
            flash('product_message', 'Statut mis à jour');
            redirect('admincontroller/orders_details/' . $id);
        }
        $data = [
            'order' => $this->orderModel->getOrderById($id),
            'items' => $this->orderModel->getOrderItems($id)
        ];
        $this->view('admin/orders/details', $data);
    }

    public function customers(){
        $customers = $this->userModel->getCustomers();
        $this->view('admin/customers/index', ['customers' => $customers]);
    }

    // =========================================================
    // HELPERS (UPLOAD)
    // =========================================================
    
        // --- GESTION DES ATTRIBUTS & TYPES ---

    public function attributes(){
        // Ajout d'un Attribut (Ex: Taille)
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_attribute'])){
            $data = ['name' => trim($_POST['name']), 'value' => trim($_POST['value'])];
            if(!empty($data['name'])){
                $this->attributeModel->addAttribute($data);
                flash('admin_msg', 'Attribut ajouté');
            }
            redirect('admin/attributes');
        }

        // Ajout d'un Type (Ex: Robe)
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_type'])){
            $data = ['name' => trim($_POST['type_name'])];
            if(!empty($data['name'])){
                $this->typeModel->addType($data);
                flash('admin_msg', 'Type de produit ajouté');
            }
            redirect('admin/attributes');
        }

        $data = [
            'attributes' => $this->attributeModel->getAttributes(),
            'types' => $this->typeModel->getTypes()
        ];
        $this->view('admin/attributes/index', $data);
    }

    public function attributes_delete($id){
        $this->attributeModel->deleteAttribute($id);
        redirect('admin/attributes');
    }

    public function types_delete($id){
        $this->typeModel->deleteType($id);
        redirect('admin/attributes');
    }
    // =========================================================
    // GESTION DES CATÉGORIES
    // =========================================================
    public function categories(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Nettoyage
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'])
            ];

            if(!empty($data['name'])){
                // On utilise une requête directe pour simplifier si la méthode n'est pas dans le Modèle
                // Ou mieux : $this->categoryModel->addCategory($data);
                
                // Ici, méthode compatible universelle :
                $this->categoryModel->db->query("INSERT INTO categories (name, description) VALUES (:name, :desc)");
                $this->categoryModel->db->bind(':name', $data['name']);
                $this->categoryModel->db->bind(':desc', $data['description']);
                
                if($this->categoryModel->db->execute()){
                    flash('admin_msg', 'Catégorie ajoutée avec succès');
                    redirect('admin/categories');
                }
            }
        }

        $categories = $this->categoryModel->getCategories();
        $this->view('admin/categories/index', ['categories' => $categories]);
    }

    public function categories_delete($id){
        $this->categoryModel->db->query("DELETE FROM categories WHERE id = :id");
        $this->categoryModel->db->bind(':id', $id);
        $this->categoryModel->db->execute();
        flash('admin_msg', 'Catégorie supprimée', 'bg-red-100 text-red-700');
        redirect('admin/categories');
    }

    // =========================================================
    // GESTION DU STAFF (AJOUT ADMIN)
    // =========================================================
    public function users_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'role' => 'admin', // Force le rôle Admin
                'error' => ''
            ];

            // Validation
            if(empty($data['email']) || empty($data['password'])) { 
                $data['error'] = "Veuillez remplir tous les champs."; 
            }
            if($data['password'] != $data['confirm_password']) { 
                $data['error'] = "Les mots de passe ne correspondent pas."; 
            }
            // Vérifier si l'email existe déjà
            if($this->userModel->findUserByEmail($data['email'])){ 
                $data['error'] = "Cet email est déjà utilisé."; 
            }

            if(empty($data['error'])){
                // Hash du mot de passe
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Inscription via le User Model
                // Note : On utilise register() ou on fait l'insertion ici
                if($this->userModel->register($data)){
                    // IMPORTANT : On force le rôle 'admin' juste après l'insertion si register() met 'customer' par défaut
                    // Mais le mieux est d'avoir une méthode registerAdmin ou de passer le rôle.
                    
                    // Update rapide pour être sûr :
                    $this->userModel->db->query("UPDATE users SET role = 'admin' WHERE email = :email");
                    $this->userModel->db->bind(':email', $data['email']);
                    $this->userModel->db->execute();

                    flash('admin_msg', 'Nouvel administrateur ajouté !');
                    redirect('admin/users_add');
                } else {
                    die('Erreur système.');
                }
            } else {
                $this->view('admin/users/add', $data);
            }
        } else {
            // Affichage du formulaire
            $data = [
                'name' => '', 'email' => '', 'password' => '', 'confirm_password' => '', 'error' => ''
            ];
            $this->view('admin/users/add', $data);
        }
    }
}