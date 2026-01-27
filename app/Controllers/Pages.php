<?php
// On charge le helper pour l'envoi d'email
require_once '../app/Helpers/mail_helper.php';

class Pages extends Controller {
    private $productModel;
    private $categoryModel;
    private $orderModel;
    private $newsletterModel;
    private $postModel;
    private $reviewModel;

    public function __construct(){
        // Chargement des modèles
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->newsletterModel = $this->model('Newsletter');
        $this->orderModel = $this->model('Order');
        $this->postModel = $this->model('Post');
        $this->reviewModel = $this->model('Review');
    }

    // =========================================================
    // 1. PAGE D'ACCUEIL (HOME)
    // =========================================================
    public function index(){
        // A. Nouveautés
        $newArrivals = $this->productModel->getNewArrivals(4);

        // B. Promotions
        $promoProducts = $this->productModel->getPromoProducts(4);

        // C. Catégories
        if(method_exists($this->categoryModel, 'getCategoriesWithCover')){
            $categories = $this->categoryModel->getCategoriesWithCover();
        } else {
            $categories = $this->categoryModel->getCategories();
        }

        // D. Blog (3 derniers articles)
        $posts = $this->postModel->getLatestPosts(3); // Utilise la méthode optimisée si elle existe

        // E. Témoignages (Validés uniquement)
        // On récupère les avis globaux approuvés pour les témoignages
if(method_exists($this->reviewModel, 'getApprovedReviews')){
    $reviews = $this->reviewModel->getApprovedReviews();
} else {
    $reviews = [];
} // Note: Il faudra adapter ReviewModel pour avoir une méthode getApprovedReviews() globale si ce n'est pas par produit.
        // Sinon, utilise celle que tu as écrite :
        if(method_exists($this->reviewModel, 'getApprovedReviews')){
            $reviews = $this->reviewModel->getApprovedReviews();
        } else {
            $reviews = [];
        }

        $data = [
            'title' => 'Exotikha - Mode Africaine Moderne',
            'description' => 'Découvrez notre collection unique.',
            'new_arrivals' => $newArrivals,
            'promo_products' => $promoProducts,
            'categories' => $categories,
            'posts' => $posts,
            'reviews' => $reviews
        ];
        
        $this->view('front/pages/index', $data);
    }

    // =========================================================
    // 2. PAGE A PROPOS
    // =========================================================
    public function about(){
        $this->view('front/pages/about');
    }

    // =========================================================
    // 3. PAGE ARTICLE DE BLOG UNIQUE
    // =========================================================
    public function post($id){
        $post = $this->postModel->getPostById($id);

        if(!$post){
            redirect('pages');
        }

        // Sidebar : 3 articles récents
        $recentPosts = $this->postModel->getLatestPosts(3);

        $data = [
            'title' => $post->title,
            'post' => $post,
            'recent' => $recentPosts
        ];

        $this->view('front/pages/post', $data);
    }
    
    // =========================================================
    // 4. NEWSLETTER
    // =========================================================
    public function subscribe(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $email = trim($_POST['email']);

            if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
                if($this->newsletterModel->addEmail($email)){
                    flash('product_message', 'Inscription réussie à la newsletter !');
                } else {
                    flash('product_message', 'Vous êtes déjà inscrit.', 'bg-orange-100 text-orange-700');
                }
            } else {
                flash('product_message', 'Email invalide.', 'bg-red-100 text-red-700');
            }
        }
        
        // Redirection vers la page précédente
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : URLROOT;
        header("Location: $referer");
    }

    // =========================================================
    // 5. CONTACT
    // =========================================================
    public function contact(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
                'error' => ''
            ];

            if(empty($data['name']) || empty($data['email']) || empty($data['message'])){
                $data['error'] = 'Veuillez remplir tous les champs.';
            }

            if(empty($data['error'])){
                $to = 'sales@exotikha.com'; 
                $email_subject = "Contact: " . $data['subject'];
                $email_body = "Nom: " . $data['name'] . "\nEmail: " . $data['email'] . "\n\n" . $data['message'];
                
                sendEmail($to, $email_subject, nl2br($email_body));

                flash('product_message', 'Merci ! Votre message a été envoyé.');
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
    // 6. LISTE DE TOUS LES ARTICLES (BLOG)
    // =========================================================
    public function blog(){
        $posts = $this->postModel->getPosts();

        $data = [
            'title' => 'Exotikha Journal',
            'posts' => $posts
        ];

        $this->view('front/pages/blog', $data);
    }

    // =========================================================
    // 7. SUIVI DE COMMANDE
    // =========================================================
    public function track(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $orderNumber = trim($_POST['order_number']);
            $email = trim($_POST['email']);

            if(empty($orderNumber) || empty($email)){
                flash('track_error', 'Veuillez entrer le numéro et l\'email.', 'bg-red-100 text-red-700');
                // CORRECTION ICI : Redirection vers profile
                redirect('users/profile?tab=track');
                return;
            }

            $order = $this->orderModel->trackOrder($orderNumber, $email);

            if($order){
                $data = [
                    'order' => $order,
                    'items' => $this->orderModel->getOrderItems($order->id)
                ];
                $this->view('front/pages/order_status', $data);
            } else {
                flash('product_message', 'Commande introuvable.', 'bg-red-100 text-red-700');
                // CORRECTION ICI : Redirection vers profile
                redirect('users/profile?tab=track');
            }
        } else {
            redirect('');
        }
    }
}