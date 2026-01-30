<?php
// On charge les namespaces nécessaires pour Dompdf si installé via Composer
// require_once '../vendor/autoload.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;

class Users extends Controller {
    private $userModel;
    private $orderModel;
    private $cardModel;
    private $reviewModel;
    private $wishlistModel;

    public function __construct(){
        $this->userModel = $this->model('User');
        $this->orderModel = $this->model('Order');
        $this->cardModel = $this->model('SavedCard');
        $this->reviewModel = $this->model('Review');
        $this->wishlistModel = $this->model('Wishlist');

        // SÉCURITÉ : Vérification session valide
        if(isset($_SESSION['user_id']) && !isset($_SESSION['is_admin'])){
            $checkUser = $this->userModel->getCustomerById($_SESSION['user_id']);
            if(!$checkUser){
                $this->forceLogout();
            }
        }
    }

    public function index(){
        $this->login();
    }

    // =========================================================
    // 1. INSCRIPTION
    // =========================================================
    public function register(){
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

            if(empty($data['name'])){ $data['name_err'] = 'Entrez votre nom complet'; }
            
            if(empty($data['email'])){ 
                $data['email_err'] = 'Entrez votre email'; 
            } else {
                if($this->userModel->findCustomerByEmail($data['email'])){
                    $data['email_err'] = 'Cet email est déjà utilisé';
                }
            }

            if(empty($data['password'])){ $data['password_err'] = 'Entrez un mot de passe'; }
            elseif(strlen($data['password']) < 6){ $data['password_err'] = '6 caractères minimum'; }
            
            if($data['password'] != $data['confirm_password']){ $data['confirm_password_err'] = 'Les mots de passe ne correspondent pas'; }

            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Hachage géré dans le modèle ou ici. Ici on le fait avant l'envoi.
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                if($this->userModel->registerCustomer($data)){
                    flash('register_success', 'Compte créé avec succès ! Connectez-vous.');
                    redirect('users/login');
                } else {
                    die('Erreur système lors de l\'inscription');
                }
            } else {
                $this->view('front/users/register', $data);
            }
        } else {
            $data = ['name'=>'','email'=>'','password'=>'','confirm_password'=>'','name_err'=>'','email_err'=>'','password_err'=>'','confirm_password_err'=>''];
            $this->view('front/users/register', $data);
        }
    }

    // =========================================================
    // 2. CONNEXION
    // =========================================================
    public function login(){
        // Si déjà connecté, redirection
        if(isset($_SESSION['user_id'])){ redirect('users/account'); }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '', 'password_err' => ''
            ];

            if(empty($data['email'])){ $data['email_err'] = 'Entrez votre email'; }
            if(empty($data['password'])){ $data['password_err'] = 'Entrez votre mot de passe'; }

            if(empty($data['email_err']) && empty($data['password_err'])){
                if($this->userModel->findCustomerByEmail($data['email'])){
                    $loggedInUser = $this->userModel->loginCustomer($data['email'], $data['password']);
                    
                    if($loggedInUser){
                        $this->createUserSession($loggedInUser);
                    } else {
                        $data['password_err'] = 'Mot de passe incorrect';
                        $this->view('front/users/login', $data);
                    }
                } else {
                    $data['email_err'] = 'Aucun compte client trouvé avec cet email';
                    $this->view('front/users/login', $data);
                }
            } else {
                $this->view('front/users/login', $data);
            }
        } else {
            $data = ['email' => '', 'password' => '', 'email_err' => '', 'password_err' => ''];
            $this->view('front/users/login', $data);
        }
    }

    public function createUserSession($user){
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
        $_SESSION['user_role'] = 'customer';
        redirect('users/account');
    }

    public function logout(){
        $this->forceLogout();
    }

    private function forceLogout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        redirect('users/login');
    }

    // =========================================================
    // 3. MON COMPTE (DASHBOARD)
    // =========================================================
    public function account(){
        if(!isLoggedIn()){ redirect('users/login'); }

        $user = $this->userModel->getCustomerById($_SESSION['user_id']);
        if(!$user){ $this->forceLogout(); return; }

        $data = ['user' => $user];

        // Chargement conditionnel des données selon l'onglet
        if(isset($_GET['tab'])){
            if($_GET['tab'] == 'orders'){
                $data['orders'] = $this->orderModel->getOrdersByUserId($_SESSION['user_id']);
            }
            elseif($_GET['tab'] == 'wishlist'){
                // On récupère la wishlist
                $data['wishlist'] = $this->wishlistModel->getUserWishlist($_SESSION['user_id']);
            }
            elseif($_GET['tab'] == 'reviews'){
                $data['reviews'] = $this->reviewModel->getReviewsByUserId($_SESSION['user_id']);
            }
            elseif($_GET['tab'] == 'payment'){
                $data['cards'] = $this->cardModel->getCustomerCards($_SESSION['user_id']);
            }
        }

        $this->view('front/users/account', $data);
    }

    // =========================================================
    // 4. MISE À JOUR PROFIL
    // =========================================================
    public function update_details(){
        if(!isLoggedIn()){ redirect('users/login'); }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            $userId = $_SESSION['user_id'];
            
            $updateData = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => '' 
            ];

            // Changement de mot de passe
            if(!empty($_POST['current_password']) && !empty($_POST['new_password'])){
                $check = $this->userModel->loginCustomer($_SESSION['user_email'], $_POST['current_password']);
                
                if(!$check){
                    flash('product_message', 'Mot de passe actuel incorrect', 'bg-red-100 text-red-700');
                    redirect('users/account?tab=details');
                    return;
                }
                if(strlen($_POST['new_password']) < 6){
                    flash('product_message', 'Nouveau mot de passe trop court', 'bg-red-100 text-red-700');
                    redirect('users/account?tab=details');
                    return;
                }
                if($_POST['new_password'] !== $_POST['confirm_password']){
                    flash('product_message', 'Mots de passe non identiques', 'bg-red-100 text-red-700');
                    redirect('users/account?tab=details');
                    return;
                }

                $updateData['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            }

            if($this->userModel->updateCustomerProfile($userId, $updateData)){
                $_SESSION['user_name'] = $updateData['name']; 
                flash('product_message', 'Profil mis à jour.');
            } else {
                flash('product_message', 'Erreur lors de la mise à jour.', 'bg-red-100 text-red-700');
            }
            redirect('users/account?tab=details');
        }
    }

    // Dans app/Controllers/Users.php

public function update_address(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // On nettoie les données reçues
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        $data = [
            'shipping_phone'   => trim($_POST['shipping_phone']),
            'shipping_address' => trim($_POST['shipping_address']),
            'shipping_city'    => trim($_POST['shipping_city']),
            'shipping_region'  => trim($_POST['shipping_region'])
        ];

        // On appelle le modèle avec l'ID du client (stocké en session)
        if($this->userModel->updateCustomerAddress($_SESSION['user_id'], $data)){
            // Succès
            flash('product_message', 'Adresse mise à jour avec succès.');
            redirect('users/profile?tab=addresses');
        } else {
            die('Erreur lors de la sauvegarde.');
        }
    }
}

    // =========================================================
    // 5. WISHLIST (AJOUTÉ)
    // =========================================================
    public function add_wishlist($product_id){
        if(!isLoggedIn()){ redirect('users/login'); }
        
        if($this->wishlistModel->add($_SESSION['user_id'], $product_id)){
            // On reste sur la page précédente
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : URLROOT;
            header("Location: $referer");
        }
    }

    public function remove_wishlist($product_id){
        if(!isLoggedIn()){ redirect('users/login'); }
        
        $this->wishlistModel->remove($_SESSION['user_id'], $product_id);
        
        // Si on est sur la page account, on redirige vers l'onglet wishlist
        if(strpos($_SERVER['REQUEST_URI'], 'account') !== false){
            redirect('users/account?tab=wishlist');
        } else {
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : URLROOT;
            header("Location: $referer");
        }
    }

    // =========================================================
    // 6. AVIS
    // =========================================================
    public function add_review(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            if(!isLoggedIn()){ redirect('users/login'); }
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'rating' => $_POST['rating'],
                'comment' => trim($_POST['comment']),
                'product_id' => $_POST['product_id']
            ];
            
            if($this->reviewModel->addReview($data)){
                flash('product_message', 'Avis soumis pour modération.');
            }
            
            // Retour page produit ou compte
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : URLROOT;
            header("Location: $referer");
        }
    }

    public function delete_review($id){
        if(!isLoggedIn()){ redirect('users/login'); }
        $this->reviewModel->deleteReview($id); 
        flash('product_message', 'Avis supprimé.');
        redirect('users/account?tab=reviews'); // Attention au 's' ou pas selon votre vue
    }
}