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
        // A. Nouveautés (4 derniers produits)
        $newArrivals = $this->productModel->getNewArrivals(4);

        // B. Promotions (4 produits en promo)
        $promoProducts = $this->productModel->getPromoProducts(4);

        // C. Catégories
        // Si vous avez ajouté une gestion d'image de couverture dans Category, utilisez getAllCategoriesWithCover
        // Sinon, la méthode standard suffit.
        $categories = $this->categoryModel->getAllCategories();

        // D. Blog (3 derniers articles)
        $posts = $this->postModel->getLatestPosts(3);

        // E. Témoignages (Validés uniquement)
        $reviews = $this->reviewModel->getApprovedReviews();

        $data = [
            'title' => 'Exotikha - Mode Africaine Moderne',
            'description' => 'Découvrez notre collection unique de vêtements et accessoires.',
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
        $data = [
            'title' => 'À Propos de Nous',
            'description' => 'L\'histoire d\'Exotikha et notre mission.'
        ];
        $this->view('front/pages/about', $data);
    }

    // =========================================================
    // 3. BLOG (LISTE & DÉTAIL)
    // =========================================================
    
    // Liste de tous les articles
    public function blog(){
        $posts = $this->postModel->getPosts();
        $data = [
            'title' => 'Exotikha Journal',
            'description' => 'Actualités, mode et tendances.',
            'posts' => $posts
        ];
        $this->view('front/pages/blog', $data);
    }

    // Article unique
    public function post($slug_or_id){
        // On essaie de récupérer par ID ou par Slug (si vous avez implémenté getPostBySlug)
        // Ici on suppose ID pour rester simple, ou Slug si le modèle le gère
        if(is_numeric($slug_or_id)){
            $post = $this->postModel->getPostById($slug_or_id);
        } else {
            // Si vous avez ajouté getPostBySlug dans Post.php
            $post = method_exists($this->postModel, 'getPostBySlug') ? $this->postModel->getPostBySlug($slug_or_id) : null;
        }

        if(!$post){
            redirect('pages/blog');
        }

        // Sidebar : 3 articles récents pour inciter à la lecture
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
            verifyCsrfToken();
            $email = trim($_POST['email']);

            if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)){
                if($this->newsletterModel->addEmail($email)){
                    flash('newsletter_msg', 'Inscription réussie à la newsletter !');
                } else {
                    flash('newsletter_msg', 'Vous êtes déjà inscrit.', 'bg-orange-100 text-orange-700');
                }
            } else {
                flash('newsletter_msg', 'Email invalide.', 'bg-red-100 text-red-700');
            }
        }
        
        // Redirection vers la page précédente (Stay on same page)
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : URLROOT;
        header("Location: $referer");
    }

    // =========================================================
    // 5. CONTACT
    // =========================================================
    public function contact(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
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
                $data['error'] = 'Veuillez remplir tous les champs obligatoires.';
            }

            if(empty($data['error'])){
                // Configuration de l'email admin
                $to = 'sales@exotikha.com'; 
                $email_subject = "Contact Site: " . $data['subject'];
                $email_body = "<strong>Nom:</strong> " . $data['name'] . "<br>" .
                              "<strong>Email:</strong> " . $data['email'] . "<br><br>" .
                              "<strong>Message:</strong><br>" . nl2br($data['message']);
                
                // Utilisation du Helper mail_helper.php
                if(sendEmail($to, $email_subject, $email_body)){
                    flash('contact_msg', 'Merci ! Votre message a été envoyé.');
                    redirect('pages/contact');
                } else {
                    $data['error'] = 'Erreur lors de l\'envoi. Veuillez réessayer.';
                    $this->view('front/pages/contact', $data);
                }
            } else {
                $this->view('front/pages/contact', $data);
            }
        } else {
            $data = ['name' => '', 'email' => '', 'subject' => '', 'message' => '', 'error' => ''];
            $this->view('front/pages/contact', $data);
        }
    }

    // =========================================================
    // 6. SUIVI DE COMMANDE (GUEST TRACKING)
    // =========================================================
    public function track(){
        // Note: Si vous n'avez pas créé la méthode trackOrder dans Order.php,
        // cette fonction ne marchera pas. Assurez-vous d'avoir ajouté la logique dans le modèle Order.
        // Sinon, redirigez vers la connexion.
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            $orderNumber = trim($_POST['order_number']);
            // On demande l'email pour vérifier l'identité (sécurité simple)
            // Si votre formulaire n'a pas d'email (juste ID), retirez cette vérification
            $email = isset($_POST['email']) ? trim($_POST['email']) : ''; 

            if(empty($orderNumber)){
                flash('track_error', 'Veuillez entrer le numéro de commande.', 'bg-red-100 text-red-700');
                redirect('users/account?tab=track');
                return;
            }

            // On suppose que vous avez ajouté cette méthode dans Order.php
            // Si elle n'existe pas, il faut l'ajouter dans le modèle Order
            if(method_exists($this->orderModel, 'getOrderByNumber')){
                 $order = $this->orderModel->getOrderByNumber($orderNumber);
            } else {
                 // Fallback si la méthode n'existe pas encore
                 flash('track_error', 'Fonctionnalité en maintenance.', 'bg-yellow-100 text-yellow-700');
                 redirect('users/account?tab=track');
                 return;
            }

            if($order){
                // Si l'utilisateur a entré un email, on vérifie que ça correspond
                if(!empty($email) && strtolower($order->email) !== strtolower($email)){
                     flash('track_error', 'Email ne correspond pas à la commande.', 'bg-red-100 text-red-700');
                     redirect('users/account?tab=track');
                     return;
                }

                $data = [
                    'order' => $order,
                    'items' => $this->orderModel->getOrderItems($order->id)
                ];
                // Vue spéciale pour le statut (ou réutiliser details)
                $this->view('front/pages/order_status', $data);
            } else {
                flash('track_error', 'Commande introuvable.', 'bg-red-100 text-red-700');
                redirect('users/account?tab=track');
            }
        } else {
            redirect('users/account?tab=track');
        }
    }
}