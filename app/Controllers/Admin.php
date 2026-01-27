<?php
class Admin extends Controller {
    private $productModel;
    private $categoryModel;
    private $attributeModel;
    private $typeModel;
    private $userModel;
    private $orderModel;
    private $postModel;
    private $reviewModel;
    private $db; // Utile pour les requêtes directes si besoin

    public function __construct(){
        // 1. Security Check : Seuls les admins peuvent accéder ici
        if(!isLoggedIn()){
            redirect('users/login');
        }
        
        // On vérifie le rôle (suppose que ta session stocke user_role ou qu'on vérifie via DB)
        // Si tu n'as pas de fonction isAdmin(), assure-toi que $_SESSION['user_role'] == 'admin'
        if(isset($_SESSION['user_role']) && $_SESSION['user_role'] != 'admin'){
            redirect('pages/index');
        }

        // 2. Load Models
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->attributeModel = $this->model('ProductAttribute');
        $this->typeModel = $this->model('ProductType');
        $this->userModel = $this->model('User');
        $this->orderModel = $this->model('Order');
        $this->postModel = $this->model('Post');
        $this->reviewModel = $this->model('Review');
        $this->orderModel = $this->model('Order');
        
        // Instance DB pour requêtes rapides si nécessaire
        $this->db = new Database;
    }

    // =========================================================
    // 1. DASHBOARD & STATS
    // =========================================================
    public function index(){
        $nbProducts = $this->productModel->countProducts();
        $stockValue = $this->productModel->getStockValue(); // Assure-toi que cette méthode existe
        $outOfStock = $this->productModel->countOutOfStock();
        $nbCustomers = $this->userModel->countCustomers();
        
        $currentYear = date('Y');
        // Vérifie si getYearlySales existe dans OrderModel, sinon commente
        $yearlySales = (method_exists($this->orderModel, 'getYearlySales')) ? $this->orderModel->getYearlySales($currentYear) : [];

        $data = [
            'revenue' => $this->orderModel->getTotalRevenue(),
            'total_orders' => $this->orderModel->countOrders(),
            'total_products' => $nbProducts,
            'total_customers' => $nbCustomers,
            
            // Pour les graphiques et widgets
            'stock_value' => $stockValue,
            'out_of_stock' => $outOfStock,
            'chart_data' => $yearlySales,
            'current_year' => $currentYear,
            'posts_count' => count($this->postModel->getPosts()),
            'pending_reviews' => count($this->reviewModel->getPendingReviews()), // Assure-toi que cette méthode existe
            
            // Listes pour le Dashboard
            'latest_orders' => $this->orderModel->getLatestOrders(5),
            'low_stock' => $this->productModel->getLowStockProducts(5)
        ];

        $this->view('admin/index', $data);
    }

    // =========================================================
    // 2. GESTION DU STAFF (ADMINISTRATEURS)
    // =========================================================
    
    // LISTE DES ADMINS (C'est cette méthode qui manquait !)
    public function users(){
        // On récupère tous les utilisateurs qui ont le rôle 'admin'
        // Si getAdmins() n'existe pas dans UserModel, on fait une requête directe ici
        $this->db->query("SELECT * FROM users WHERE role = 'admin' ORDER BY created_at DESC");
        $admins = $this->db->resultSet();

        $data = [
            'admins' => $admins
        ];
        
        $this->view('admin/users/index', $data);
    }

    // AJOUTER UN ADMIN
    public function users_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'role' => 'admin', // Force le rôle
                'error' => ''
            ];

            if(empty($data['email']) || empty($data['password'])) { 
                $data['error'] = "Veuillez remplir tous les champs."; 
            } elseif($data['password'] != $data['confirm_password']) { 
                $data['error'] = "Les mots de passe ne correspondent pas."; 
            } elseif($this->userModel->findUserByEmail($data['email'])){ 
                $data['error'] = "Cet email est déjà utilisé."; 
            }

            if(empty($data['error'])){
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Insertion manuelle pour forcer le rôle admin si register() ne le permet pas
                $this->db->query("INSERT INTO users (full_name, email, password, role) VALUES (:name, :email, :pass, 'admin')");
                $this->db->bind(':name', $data['name']);
                $this->db->bind(':email', $data['email']);
                $this->db->bind(':pass', $data['password']);
                
                if($this->db->execute()){
                    flash('admin_msg', 'Nouvel administrateur ajouté !');
                    redirect('admin/users'); // Redirection vers la liste
                } else {
                    die('Erreur base de données');
                }
            } else {
                $this->view('admin/users/add', $data);
            }
        } else {
            $data = ['name' => '', 'email' => '', 'password' => '', 'confirm_password' => '', 'error' => ''];
            $this->view('admin/users/add', $data);
        }
    }

    // SUPPRIMER UN ADMIN
    public function users_delete($id){
        // Empêcher de se supprimer soi-même
        if($id == $_SESSION['user_id']){
            flash('admin_msg', 'Impossible de supprimer votre propre compte.', 'bg-red-50 text-red-600');
            redirect('admin/users');
        }

        $this->db->query("DELETE FROM users WHERE id = :id AND role = 'admin'");
        $this->db->bind(':id', $id);
        
        if($this->db->execute()){
            flash('admin_msg', 'Administrateur supprimé.');
        }
        redirect('admin/users');
    }

    // LISTE DES CLIENTS
    public function customers(){
        // Récupère uniquement les utilisateurs avec role = 'customer'
        $this->db->query("SELECT * FROM users WHERE role = 'customer' ORDER BY created_at DESC");
        $customers = $this->db->resultSet();
        
        $this->view('admin/customers/index', ['customers' => $customers]);
    }

    // =========================================================
    // 3. GESTION DES PRODUITS
    // =========================================================

    public function products(){
        $products = $this->productModel->getProducts();
        $this->view('admin/products/index', ['products' => $products]);
    }

    public function products_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Upload Image
            $imageName = null;
            if(!empty($_FILES['image']['name'])){
                $imageName = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../public/img/' . $imageName);
            }

            $data = [
                'name' => trim($_POST['name']),
                'category_id' => $_POST['category_id'],
                'type_id' => $_POST['type_id'],
                'gender' => $_POST['gender'],
                'description' => trim($_POST['description']),
                'price' => $_POST['price'],
                'promo_price' => $_POST['promo_price'],
                'stock' => $_POST['simple_stock'], // Stock global
                'image' => $imageName
            ];

            $productId = $this->productModel->addProduct($data);

            if($productId){
                // Ajout des Variantes si cochées
                if(isset($_POST['has_variants']) && isset($_POST['variants'])){
                    // $_POST['variants'] est un tableau envoyé par le JS
                    $this->productModel->addProductVariants($productId, $_POST['variants']);
                    
                    // Si variantes, on met le stock global à la somme des variantes (optionnel mais recommandé)
                    // $this->productModel->updateStockFromVariants($productId);
                }

                flash('admin_msg', 'Produit ajouté avec succès');
                redirect('admin/products');
            }
        }

        $data = [
            'categories' => $this->categoryModel->getCategories(),
            'types' => $this->typeModel->getTypes(),
            'attributes' => $this->attributeModel->getAttributes()
        ];

        $this->view('admin/products/add', $data);
    }

    public function products_edit($id){
        $product = $this->productModel->getProductById($id);
        if(!$product){ redirect('admin/products'); }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $imageName = $product->image;
            if(!empty($_FILES['image']['name'])){
                $imageName = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../public/img/' . $imageName);
            }

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'category_id' => $_POST['category_id'],
                'type_id' => $_POST['type_id'],
                'gender' => $_POST['gender'],
                'description' => trim($_POST['description']),
                'price' => $_POST['price'],
                'promo_price' => $_POST['promo_price'],
                'stock' => $_POST['simple_stock'],
                'image' => $imageName
            ];

            if($this->productModel->updateProduct($data)){
                // Ajout des NOUVELLES variantes
                if(isset($_POST['has_variants']) && isset($_POST['variants'])){
                    $this->productModel->addProductVariants($id, $_POST['variants']);
                }
                
                flash('admin_msg', 'Produit mis à jour');
                redirect('admin/products');
            }
        }

        $data = [
            'product' => $product,
            'variants' => $this->productModel->getVariantsByProductId($id),
            'categories' => $this->categoryModel->getCategories(),
            'types' => $this->typeModel->getTypes(),
            'attributes' => $this->attributeModel->getAttributes()
        ];

        $this->view('admin/products/edit', $data);
    }

    public function products_show($id){
        $product = $this->productModel->getProductById($id);
        if(!$product){ redirect('admin/products'); }

        $data = [
            'product' => $product,
            'variants' => $this->productModel->getVariantsByProductId($id)
        ];
        $this->view('admin/products/details', $data);
    }

    public function products_delete($id){
        // Supprime le produit (la DB doit être en ON DELETE CASCADE pour supprimer les variantes auto)
        // Sinon il faut supprimer les variantes manuellement avant.
        if($this->productModel->deleteProduct($id)){
            flash('admin_msg', 'Produit supprimé');
        }
        redirect('admin/products');
    }

    public function products_delete_variant($variantId, $productId){
        $this->db->query("DELETE FROM product_variants WHERE id = :id");
        $this->db->bind(':id', $variantId);
        $this->db->execute();
        
        flash('admin_msg', 'Variante supprimée');
        redirect('admin/products_edit/' . $productId);
    }

    // =========================================================
    // 4. GESTION DES CATÉGORIES
    // =========================================================
    public function categories(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $name = trim($_POST['name']);
            $desc = trim($_POST['description']);
            
            if(!empty($name)){
                $this->db->query("INSERT INTO categories (name, description) VALUES (:name, :desc)");
                $this->db->bind(':name', $name);
                $this->db->bind(':desc', $desc);
                $this->db->execute();
                flash('admin_msg', 'Catégorie ajoutée');
                redirect('admin/categories');
            }
        }
        $data = ['categories' => $this->categoryModel->getCategories()];
        $this->view('admin/categories/index', $data);
    }

    public function categories_delete($id){
        $this->db->query("DELETE FROM categories WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        redirect('admin/categories');
    }

    // =========================================================
    // 5. GESTION DES ATTRIBUTS
    // =========================================================
    public function attributes(){
        $data = ['attributes' => $this->attributeModel->getAttributes()];
        $this->view('admin/attributes/index', $data);
    }

    public function attributes_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $name = trim($_POST['name']);
            $values = explode(',', $_POST['values']); // Transforme "S, M, L" en tableau
            if($this->attributeModel->addAttribute($name, $values)){
                redirect('admin/attributes');
            }
        }
        $this->view('admin/attributes/add');
    }

    public function attributes_edit($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $name = trim($_POST['name']);
            $values = explode(',', $_POST['values']);
            if($this->attributeModel->updateAttribute($id, $name, $values)){
                redirect('admin/attributes');
            }
        }
        
        $attr = $this->attributeModel->getAttributeById($id);
        // On prépare les valeurs sous forme de string pour l'input
        $valArray = [];
        if($attr && isset($attr->values)){
            foreach($attr->values as $v) $valArray[] = $v->value;
        }
        
        $data = [
            'id' => $id,
            'name' => $attr->name,
            'values_str' => implode(', ', $valArray)
        ];
        $this->view('admin/attributes/edit', $data);
    }

    public function attributes_delete($id){
        $this->attributeModel->deleteAttribute($id);
        redirect('admin/attributes');
    }

    // =========================================================
    // 6. GESTION DES COMMANDES
    // =========================================================
    public function orders(){
        $orders = $this->orderModel->getAllOrders();
        $this->view('admin/orders/index', ['orders' => $orders]);
    }

    public function orders_details($id){
        $order = $this->orderModel->getOrderById($id);
        if(!$order) redirect('admin/orders');
        
        $items = $this->orderModel->getOrderItems($id);
        $this->view('admin/orders/details', ['order' => $order, 'items' => $items]);
    }

    
public function update_status(){
        // DEBUG : Si tu vois ça, le lien marche !
        // Une fois que tu as vu "TEST RECU", supprime cette ligne die(...)
        // die('TEST RECU : Le formulaire atteint bien le contrôleur');

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $orderId = $_POST['order_id'];
            $newStatus = $_POST['status'];

            // On tente la mise à jour
            if($this->orderModel->updateStatus($orderId, $newStatus)){
                
                // Si livré => On marque payé
                if($newStatus == 'delivered'){
                    $this->orderModel->updatePaymentStatus($orderId, 'paid');
                }

                // Retour à la page détails
                redirect('admin/orders_details/' . $orderId);
            } else {
                die('Erreur SQL : Impossible de mettre à jour le statut.');
            }
        } else {
            redirect('admin/orders');
        }
    }
    // =========================================================
    // 7. BLOG & AVIS
    // =========================================================
    public function blog(){
        $this->view('admin/blog/index', ['posts' => $this->postModel->getPosts()]);
    }

    public function blog_add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $imageName = null;
            if(!empty($_FILES['image']['name'])){
                $imageName = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../public/img/' . $imageName);
            }
            
            $data = [
                'title' => trim($_POST['title']),
                'category' => $_POST['category'],
                'content' => trim($_POST['content']),
                'image' => $imageName
            ];
            
            $this->postModel->addPost($data);
            redirect('admin/blog');
        }
        $this->view('admin/blog/add');
    }

    public function blog_edit($id){
        $post = $this->postModel->getPostById($id);
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $imageName = $post->image;
            if(!empty($_FILES['image']['name'])){
                $imageName = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], '../public/img/' . $imageName);
            }
            
            $data = [
                'id' => $id,
                'title' => trim($_POST['title']),
                'category' => $_POST['category'],
                'content' => trim($_POST['content']),
                'image' => $imageName
            ];
            
            $this->postModel->updatePost($data);
            redirect('admin/blog');
        }
        $this->view('admin/blog/edit', ['post' => $post]);
    }

    public function blog_delete($id){
        $this->postModel->deletePost($id);
        redirect('admin/blog');
    }

    public function reviews(){
        $this->view('admin/reviews/index', ['reviews' => $this->reviewModel->getAllReviews()]);
    }

    public function approve_review($id){
        $this->reviewModel->updateStatus($id, 'approved');
        redirect('admin/reviews');
    }

    public function delete_review($id){
        $this->reviewModel->deleteReview($id);
        redirect('admin/reviews');
    }

    // À ajouter dans app/Controllers/Admin.php

    public function update_order_status(){
        // On vérifie que c'est bien une requête POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            // Nettoyage des données
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $orderId = $_POST['order_id'];
            $newStatus = $_POST['status'];

            // Appel au modèle pour mettre à jour
            if($this->orderModel->updateStatus($orderId, $newStatus)){
                
                // Si le statut est "delivered" (Livré), on peut aussi mettre le paiement en "Paid" automatiquement
                if($newStatus == 'delivered'){
                    $this->orderModel->updatePaymentStatus($orderId, 'paid');
                }

                // Message de succès (si tu as le helper flash)
                // flash('admin_msg', 'Statut de commande mis à jour avec succès');
                
                // Redirection vers la page de détails
                redirect('admin/orders_details/' . $orderId);
            } else {
                die('Erreur lors de la mise à jour.');
            }
        } else {
            redirect('admin/orders');
        }
    }
}