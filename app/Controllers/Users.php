<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}

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

        // SÉCURITÉ : Vérification que l'utilisateur existe toujours en BDD
        if(isset($_SESSION['user_id'])){
            $checkUser = $this->userModel->getUserById($_SESSION['user_id']);
            if(!$checkUser){
                $this->forceLogout();
            }
        }
    }

    public function index(){
        $this->login();
    }

    // =========================================================
    // 1. INSCRIPTION (CLIENTS)
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

            if(empty($data['name'])){ $data['name_err'] = lang('err_enter_name'); }
            
            if(empty($data['email'])){ 
                $data['email_err'] = lang('err_enter_email'); 
            } else {
                if($this->userModel->findCustomerByEmail($data['email'])){
                    $data['email_err'] = lang('err_email_taken');
                }
            }

            if(empty($data['password'])){ $data['password_err'] = lang('err_enter_pass'); }
            elseif(strlen($data['password']) < 6){ $data['password_err'] = lang('err_pass_len'); }
            
            if($data['password'] != $data['confirm_password']){ $data['confirm_password_err'] = lang('err_pass_match'); }

            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Hash du mot de passe
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                if($this->userModel->registerCustomer($data)){
                    flash('register_success', lang('msg_register_success'));
                    redirect('users/login');
                } else {
                    die('System error');
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
    // 2. CONNEXION (CLIENTS & ADMINS)
    // =========================================================
    public function login(){
        if(isset($_SESSION['user_id'])){ 
            // Si déjà connecté, redirection intelligente
            if($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'super_admin'){
                redirect('admin/index');
            } else {
                redirect('users/account');
            }
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '', 'password_err' => ''
            ];

            if(empty($data['email'])){ $data['email_err'] = lang('err_enter_email'); }
            if(empty($data['password'])){ $data['password_err'] = lang('err_enter_pass'); }

            if(empty($data['email_err']) && empty($data['password_err'])){
                // On vérifie si l'email existe
                if($this->userModel->findCustomerByEmail($data['email'])){
                    // Tentative de connexion
                    $loggedInUser = $this->userModel->loginCustomer($data['email'], $data['password']);
                    
                    if($loggedInUser){
                        $this->createUserSession($loggedInUser);
                    } else {
                        $data['password_err'] = lang('err_login_fail');
                        $this->view('front/users/login', $data);
                    }
                } else {
                    $data['email_err'] = lang('err_no_account');
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
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        // On utilise la concaténation faite dans le modèle ou first+last
        $_SESSION['user_name'] = isset($user->name) ? $user->name : $user->first_name . ' ' . $user->last_name;
        $_SESSION['user_image'] = $user->image ?? null; // Pour l'avatar admin
        $_SESSION['user_role'] = $user->role; 

        // REDIRECTION SELON LE RÔLE
        if($user->role === 'admin' || $user->role === 'super_admin' || $user->role === 'editor'){
            redirect('admin/index'); 
        } else {
            redirect('pages/index'); 
        }
    }

    public function logout(){
        $this->forceLogout();
    }

    private function forceLogout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_image']);
        session_destroy();
        redirect('users/login');
    }

    // =========================================================
    // 3. MON COMPTE (FRONT OFFICE)
    // =========================================================
    public function account(){
        if(!isLoggedIn()){ redirect('users/login'); }

        // Utilisation de getUserById (nouveau nom dans le modèle)
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        if(!$user){ $this->forceLogout(); return; }

        $data = ['user' => $user];

        // Chargement conditionnel des onglets
        if(isset($_GET['tab'])){
            if($_GET['tab'] == 'orders'){
                // Assurez-vous que getOrdersByUserId existe dans Order.php
                $data['orders'] = $this->orderModel->getOrdersByUserId($_SESSION['user_id']);
            }
            elseif($_GET['tab'] == 'wishlist'){
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
                // On revérifie le mot de passe actuel
                $check = $this->userModel->loginCustomer($_SESSION['user_email'], $_POST['current_password']);
                
                if(!$check){
                    flash('product_message', lang('err_current_pass'), 'bg-red-100 text-red-700');
                    redirect('users/account?tab=details');
                    return;
                }
                if(strlen($_POST['new_password']) < 6){
                    flash('product_message', lang('err_pass_len'), 'bg-red-100 text-red-700');
                    redirect('users/account?tab=details');
                    return;
                }
                if($_POST['new_password'] !== $_POST['confirm_password']){
                    flash('product_message', lang('err_pass_match'), 'bg-red-100 text-red-700');
                    redirect('users/account?tab=details');
                    return;
                }

                $updateData['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            }

            // Utilisation de updateProfile (nouveau nom dans le modèle)
            if($this->userModel->updateProfile($userId, $updateData)){
                $_SESSION['user_name'] = $updateData['name']; 
                flash('product_message', lang('msg_profile_updated'));
            } else {
                flash('product_message', lang('err_update_fail'), 'bg-red-100 text-red-700');
            }
            redirect('users/account?tab=details');
        }
    }

    public function update_address(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Pas de filtre strict sur FILTER_SANITIZE_STRING (déprécié en PHP 8.1+)
            // On utilise htmlspecialchars à l'affichage ou trim ici
            
            $data = [
                'phone'   => trim($_POST['shipping_phone']),
                'address' => trim($_POST['shipping_address']),
                'city'    => trim($_POST['shipping_city']),
                'region'  => trim($_POST['shipping_region'])
            ];

            // Utilisation de updateAddress (nouveau nom dans le modèle)
            if($this->userModel->updateAddress($_SESSION['user_id'], $data)){
                flash('product_message', lang('msg_address_updated'));
            } else {
                flash('product_message', lang('err_update_fail'), 'bg-red-100 text-red-700');
            }
            redirect('users/account?tab=addresses');
        }
    }

    // =========================================================
    // 5. WISHLIST
    // =========================================================
    public function add_wishlist($product_id){
        if(!isLoggedIn()){ redirect('users/login'); }
        
        if($this->wishlistModel->add($_SESSION['user_id'], $product_id)){
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : URLROOT;
            header("Location: $referer");
        }
    }

    public function remove_wishlist($product_id){
        if(!isLoggedIn()){ redirect('users/login'); }
        
        $this->wishlistModel->remove($_SESSION['user_id'], $product_id);
        
        if(strpos($_SERVER['REQUEST_URI'], 'account') !== false){
            redirect('users/account?tab=wishlist');
        } else {
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : URLROOT;
            header("Location: $referer");
        }
    }

    // =========================================================
    // 6. AVIS (REVIEWS)
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
                flash('product_message', lang('msg_review_submitted'));
            }
            
            $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : URLROOT;
            header("Location: $referer");
        }
    }

    public function delete_review($id){
        if(!isLoggedIn()){ redirect('users/login'); }
        // Idéalement, vérifier que l'avis appartient bien à l'user connecté
        $this->reviewModel->deleteReview($id); 
        flash('product_message', lang('msg_review_deleted'));
        redirect('users/account?tab=reviews');
    }

    // =========================================================
    // 7. LANGUE
    // =========================================================
    public function setLang($lang = 'en'){
        if($lang == 'fr' || $lang == 'en'){
            $_SESSION['lang'] = $lang;
            // Gestion de la devise
            if($lang == 'fr') { $_SESSION['currency'] = 'CFA'; }
            else { $_SESSION['currency'] = 'GHS'; }
        }
        
        if(isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            redirect('pages/index');
        }
    }
}