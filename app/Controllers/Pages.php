<?php
// On charge le helper pour l'envoi d'email
require_once '../app/Helpers/mail_helper.php';

class Pages extends Controller {
    private $productModel;
    private $categoryModel;
    private $orderModel;
    private $newsletterModel;
    private $postModel;   // Nouveau : Pour le Blog
    private $reviewModel; // Nouveau : Pour les Avis

    public function __construct(){
        // Chargement des modèles
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->newsletterModel = $this->model('Newsletter');
        $this->orderModel = $this->model('Order');
        $this->postModel = $this->model('Post');     // Chargement du modèle Post
        $this->reviewModel = $this->model('Review'); // Chargement du modèle Review
    }

    // =========================================================
    // 1. PAGE D'ACCUEIL (HOME)
    // =========================================================
    public function index(){
        // A. Récupérer les produits (New Arrivals - Limite 8)
        $products = $this->productModel->getProducts(); 
        $recentProducts = array_slice($products, 0, 8);

        // B. Récupérer les Catégories
        // Utilise getCategoriesWithCover si elle existe, sinon getCategories simple
        if(method_exists($this->categoryModel, 'getCategoriesWithCover')){
            $categories = $this->categoryModel->getCategoriesWithCover();
        } else {
            $categories = $this->categoryModel->getCategories();
        }

        // C. Récupérer les Articles de Blog (Limite 3)
        $posts = $this->postModel->getPosts();
        $recentPosts = array_slice($posts, 0, 3);

        // D. Récupérer les Témoignages Approuvés (Limite 6)
        $reviews = $this->reviewModel->getApprovedReviews();

        $data = [
            'title' => 'Exotikha - Sensual, Elegant, Confident',
            'products' => $recentProducts,
            'categories' => $categories,
            'posts' => $recentPosts, // Variable disponible dans la vue index.php
            'reviews' => $reviews    // Variable disponible dans la vue index.php
        ];
        
        // Attention au chemin de la vue (front/pages/index ou front/index selon votre structure)
        $this->view('front/pages/index', $data);
    }

    // =========================================================
    // 2. PAGE A PROPOS
    // =========================================================
    public function about(){
        $this->view('front/pages/about');
    }

    // =========================================================
    // 3. PAGE ARTICLE DE BLOG UNIQUE (Lecture)
    // =========================================================
    public function post($id){
        $post = $this->postModel->getPostById($id);
        
        if($post){
            $data = ['post' => $post];
            $this->view('front/pages/single_post', $data);
        } else {
            redirect(''); // Redirection si l'article n'existe pas
        }
    }
    
    // =========================================================
    // 4. NEWSLETTER
    // =========================================================
    public function subscribe(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $email = trim($_POST['email']);

            if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
                if($this->newsletterModel->addEmail($email)){
                    flash('product_message', 'You have successfully subscribed to our newsletter!');
                } else {
                    flash('product_message', 'You are already subscribed.', 'bg-orange-100 text-orange-700');
                }
            } else {
                flash('product_message', 'Please enter a valid email address.', 'bg-red-100 text-red-700');
            }
        }
        
        // Rediriger vers la page précédente
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : URLROOT;
        header("Location: $referer");
    }

    // =========================================================
    // 5. CONTACT
    // =========================================================
    public function contact(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
                'error' => ''
            ];

            if(empty($data['name']) || empty($data['email']) || empty($data['message'])){
                $data['error'] = 'Please fill in all required fields.';
            }

            if(empty($data['error'])){
                $to = 'sales@exotikha.com'; 
                $email_subject = "Contact Form: " . $data['subject'];
                $email_body = "Name: " . $data['name'] . "\n" . "Email: " . $data['email'] . "\n\n" . $data['message'];
                
                // Utilisation du mail_helper si disponible, sinon mail() natif
                if(function_exists('sendEmail')){
                    sendEmail($to, $email_subject, nl2br($email_body)); // nl2br pour convertir les sauts de ligne
                } else {
                    mail($to, $email_subject, $email_body);
                }

                flash('product_message', 'Thank you! Your message has been sent.');
                redirect('pages/contact');
            } else {
                $this->view('front/pages/contact', $data);
            }
        } else {
            $data = ['name' => '', 'email' => '', 'subject' => '', 'message' => '', 'error' => ''];
            $this->view('front/pages/contact', $data);
        }
    }

    // =========================================================
    // 6. SUIVI DE COMMANDE (TRACK)
    // =========================================================
    public function track(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $orderNumber = trim($_POST['order_number']);
            $email = trim($_POST['email']);

            if(empty($orderNumber) || empty($email)){
                flash('track_error', 'Please provide both Order ID and Email.', 'bg-red-100 text-red-700');
                redirect('users/account?tab=track');
                return;
            }

            // Vérification via le modèle Order
            $order = $this->orderModel->trackOrder($orderNumber, $email);

            if($order){
                $data = [
                    'order' => $order,
                    'items' => $this->orderModel->getOrderItems($order->id)
                ];
                $this->view('front/pages/order_status', $data);
            } else {
                flash('product_message', 'Order not found. Please check your details.', 'bg-red-100 text-red-700');
                redirect('users/account?tab=track');
            }
        } else {
            redirect('');
        }
    }
}