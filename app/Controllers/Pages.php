<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}
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
        $newArrivals = $this->productModel->getNewArrivals(4);
        $promoProducts = $this->productModel->getPromoProducts(4);
        $categories = $this->categoryModel->getAllCategories();
        $posts = $this->postModel->getLatestPosts(3);
        $reviews = $this->reviewModel->getApprovedReviews();

        $data = [
            'title' => defined('SITENAME') ? SITENAME : 'Exotikha',
            'description' => lang('meta_desc_home'), // Clé à ajouter dans fr.php/en.php
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
            'title' => lang('page_about_title'),
            'description' => lang('page_about_desc')
        ];
        $this->view('front/pages/about', $data);
    }

    // =========================================================
    // 3. BLOG (LISTE & DÉTAIL)
    // =========================================================
    
    public function blog(){
        $posts = $this->postModel->getPosts();
        $data = [
            'title' => lang('page_blog_title'),
            'description' => lang('page_blog_desc'),
            'posts' => $posts
        ];
        $this->view('front/pages/blog', $data);
    }

    // Article unique
    public function post($slug_or_id){
        if(is_numeric($slug_or_id)){
            $post = $this->postModel->getPostById($slug_or_id);
        } else {
            $post = method_exists($this->postModel, 'getPostBySlug') ? $this->postModel->getPostBySlug($slug_or_id) : null;
        }

        if(!$post){
            redirect('pages/blog');
        }

        $recentPosts = $this->postModel->getLatestPosts(3);

        // --- TRADUCTION DU TITRE ---
        // Si FR, on affiche le titre FR, sinon EN
        $displayTitle = $post->title;
        if(isset($_SESSION['lang']) && $_SESSION['lang'] === 'fr' && !empty($post->title_fr)){
            $displayTitle = $post->title_fr;
        }

        $data = [
            'title' => $displayTitle,
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

            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if ($this->newsletterModel->addEmail($email)) {
                    flash('newsletter_msg', lang('msg_sub_success'));
                } else {
                    flash('newsletter_msg', lang('msg_sub_exist'), 'bg-orange-100 text-orange-700');
                }
            } else {
                flash('newsletter_msg', lang('msg_sub_invalid'), 'bg-red-100 text-red-700');
            }
        }
        
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
                $data['error'] = lang('err_fill_all');
            }

            if(empty($data['error'])){
                $to = 'sales@exotikha.com'; 
                $email_subject = "Contact Site: " . $data['subject'];
                $email_body = "<strong>Name:</strong> " . $data['name'] . "<br>" .
                              "<strong>Email:</strong> " . $data['email'] . "<br><br>" .
                              "<strong>Message:</strong><br>" . nl2br($data['message']);
                
                if(sendEmail($to, $email_subject, $email_body)){
                    flash('contact_msg', lang('msg_contact_success'));
                    redirect('pages/contact');
                } else {
                    $data['error'] = lang('err_contact_send');
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
    // 6. SUIVI DE COMMANDE
    // =========================================================
    public function track(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            $orderNumber = trim($_POST['order_number']);
            $email = isset($_POST['email']) ? trim($_POST['email']) : ''; 

            if(empty($orderNumber)){
                flash('track_error', lang('err_track_empty'), 'bg-red-100 text-red-700');
                redirect('users/account?tab=track');
                return;
            }

            if(method_exists($this->orderModel, 'getOrderByNumber')){
                 $order = $this->orderModel->getOrderByNumber($orderNumber);
            } else {
                 flash('track_error', lang('err_maintenance'), 'bg-yellow-100 text-yellow-700');
                 redirect('users/account?tab=track');
                 return;
            }

            if($order){
                if(!empty($email) && strtolower($order->email) !== strtolower($email)){
                      flash('track_error', lang('err_track_email'), 'bg-red-100 text-red-700');
                      redirect('users/account?tab=track');
                      return;
                }

                $data = [
                    'order' => $order,
                    'items' => $this->orderModel->getOrderItems($order->id)
                ];
                $this->view('front/pages/order_status', $data);
            } else {
                flash('track_error', lang('err_track_not_found'), 'bg-red-100 text-red-700');
                redirect('users/account?tab=track');
            }
        } else {
            redirect('users/account?tab=track');
        }
    }
}