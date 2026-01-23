<?php
require_once '../app/Helpers/mail_helper.php';

class Users extends Controller {
    private $userModel;
    private $orderModel;
    private $cardModel;
    private $reviewModel;

    public function __construct(){
        $this->userModel = $this->model('User');
        $this->orderModel = $this->model('Order');
        $this->cardModel = $this->model('SavedCard');
    }

    // REDIRECTION PAR DÉFAUT
    public function index(){
        if(isLoggedIn()){
            redirect('users/account');
        } else {
            redirect('users/login');
        }
    }

    // =========================================================
    // GESTION DU COMPTE (DASHBOARD)
    // =========================================================
    public function account(){
        if(!isLoggedIn()){ redirect('users/login'); }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);
        $orders = $this->orderModel->getOrdersByUserId($userId);
        $cards = $this->cardModel->getUserCards($userId);
        
        $tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
        
        $data = [
            'user' => $user,
            'orders' => $orders,
            'cards' => $cards,
            'tab' => $tab
        ];

        // --- TRAITEMENT DES FORMULAIRES POST ---
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            // 1. Mise à jour des détails personnels
            if(isset($_POST['update_details'])){
                $updateData = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'current_password' => trim($_POST['current_password']),
                    'new_password' => trim($_POST['new_password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'password' => '' 
                ];

                // Sécurité : Vérifier le mot de passe actuel pour changer les infos
                if(!empty($updateData['current_password']) && !$this->userModel->checkPassword($userId, $updateData['current_password'])){
                    flash('product_message', 'Current password incorrect.', 'bg-red-100 text-red-700');
                } else {
                    // Si on veut changer de mot de passe
                    if(!empty($updateData['new_password'])){
                        if($updateData['new_password'] != $updateData['confirm_password']){
                            flash('product_message', 'New passwords do not match.', 'bg-red-100 text-red-700');
                            redirect('users/account?tab=details');
                            return;
                        }
                        $updateData['password'] = password_hash($updateData['new_password'], PASSWORD_DEFAULT);
                    }

                    if($this->userModel->updateDetails($userId, $updateData)){
                        $_SESSION['user_name'] = $updateData['name'];
                        flash('product_message', 'Account details updated successfully.');
                    }
                }
                redirect('users/account?tab=details');
            }

            // 2. Mise à jour des adresses
            if(isset($_POST['update_address'])){
                $type = $_POST['address_type']; // billing ou shipping
                $addressData = [
                    'phone' => trim($_POST[$type.'_phone']),
                    'address' => trim($_POST[$type.'_address']),
                    'city' => trim($_POST[$type.'_city']),
                    'region' => trim($_POST[$type.'_region'])
                ];

                if($this->userModel->updateAddress($userId, $addressData, $type)){
                    flash('product_message', ucfirst($type) . ' address updated successfully.');
                }
                redirect('users/account?tab=addresses');
            }

            // 3. Sauvegarde d'un nouveau moyen de paiement (Retour Paystack)
            if(isset($_POST['save_new_card'])){
                $ref = $_POST['paystack_ref'];
                
                // Vérification de la transaction auprès de Paystack
                $url = "https://api.paystack.co/transaction/verify/" . rawurlencode($ref);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . PAYSTACK_SECRET_KEY]);
                $result = curl_exec($ch);
                curl_close($ch);

                if($result){
                    $json = json_decode($result, true);
                    if($json['status'] && $json['data']['status'] == 'success'){
                        $auth = $json['data']['authorization'];
                        
                        // On enregistre seulement si c'est une carte réutilisable
                        if($auth['reusable']){
                            $cardData = [
                                'user_id' => $userId,
                                'authorization_code' => $auth['authorization_code'],
                                'card_type' => $auth['card_type'],
                                'last4' => $auth['last4'],
                                'bank' => $auth['bank'],
                                'email' => $json['data']['customer']['email']
                            ];
                            
                            if($this->cardModel->addCard($cardData)){
                                flash('product_message', 'Payment method saved successfully.');
                            }
                        } else {
                            flash('product_message', 'This payment method cannot be saved for later use.', 'bg-orange-100 text-orange-700');
                        }
                    }
                }
                redirect('users/account?tab=payment');
            }
        }

        $this->view('front/users/account', $data);
    }

    // SUPPRIMER UNE CARTE
    public function delete_card($id){
        if(!isLoggedIn()){ redirect('users/login'); }
        
        if($this->cardModel->deleteCard($id, $_SESSION['user_id'])){
            flash('product_message', 'Payment method removed.');
        }
        redirect('users/account?tab=payment');
    }

    // =========================================================
    // AUTHENTIFICATION (LOGIN / REGISTER / LOGOUT)
    // =========================================================

    public function login(){
        if(isLoggedIn()){ redirect('users/account'); }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = ['email' => trim($_POST['email']), 'password' => trim($_POST['password']), 'email_err' => '', 'password_err' => ''];

            if(empty($data['email'])){ $data['email_err'] = 'Please enter email'; }
            if(empty($data['password'])){ $data['password_err'] = 'Please enter password'; }

            if($this->userModel->findUserByEmail($data['email'])){
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                if($loggedInUser){
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('front/users/login', $data);
                }
            } else {
                $data['email_err'] = 'No user found';
                $this->view('front/users/login', $data);
            }
        } else {
            $data = ['email' => '', 'password' => '', 'email_err' => '', 'password_err' => ''];
            $this->view('front/users/login', $data);
        }
    }

    public function register(){
        if(isLoggedIn()){ redirect('users/account'); }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '', 'email_err' => '', 'password_err' => '', 'confirm_password_err' => ''
            ];

            if(empty($data['name'])){ $data['name_err'] = 'Name is required'; }
            if(empty($data['email'])){ $data['email_err'] = 'Email is required'; }
            elseif($this->userModel->findUserByEmail($data['email'])){ $data['email_err'] = 'Email already exists'; }
            
            if(empty($data['password'])){ $data['password_err'] = 'Password is required'; }
            elseif(strlen($data['password']) < 6){ $data['password_err'] = 'Min 6 characters'; }
            
            if($data['password'] != $data['confirm_password']){ $data['confirm_password_err'] = 'Passwords do not match'; }

            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err'])){
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                if($this->userModel->register($data)){
                    flash('register_success', 'Account created! You can now login.');
                    redirect('users/login');
                }
            } else {
                $this->view('front/users/register', $data);
            }
        } else {
            $data = ['name'=>'','email'=>'','password'=>'','confirm_password'=>'','name_err'=>'','email_err'=>'','password_err'=>'','confirm_password_err'=>''];
            $this->view('front/users/register', $data);
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->full_name;
        $_SESSION['user_role'] = $user->role;
        redirect($user->role == 'admin' ? 'admin' : 'users/account');
    }

    public function logout(){
        unset($_SESSION['user_id'], $_SESSION['user_email'], $_SESSION['user_name'], $_SESSION['user_role']);
        session_destroy();
        redirect('users/login');
    }
    // Dans Controllers/Users.php
public function add_review(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $data = [
            'rating' => $_POST['rating'],
            'comment' => trim($_POST['comment'])
        ];
        $this->reviewModel = $this->model('Review');
        if($this->reviewModel->addReview($data)){
            flash('product_message', 'Thank you! Your review has been submitted for approval.');
            redirect('users/account?tab=review');
        }
    }
}}
