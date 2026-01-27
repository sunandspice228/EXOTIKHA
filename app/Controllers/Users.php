<?php
// On charge les namespaces nécessaires
use Dompdf\Dompdf;
use Dompdf\Options;

// Helper pour l'email
require_once '../app/Helpers/mail_helper.php';

class Users extends Controller {
    private $userModel;
    private $orderModel;
    private $cardModel;
    private $reviewModel;
    private $wishlistModel;

    public function __construct(){
        // Chargement centralisé des modèles
        $this->userModel = $this->model('User');
        $this->orderModel = $this->model('Order');
        $this->cardModel = $this->model('SavedCard');
        $this->reviewModel = $this->model('Review');
        $this->wishlistModel = $this->model('WishlistModel');
    }

    public function index(){
        if(isLoggedIn()){
            redirect('users/account');
        } else {
            redirect('users/login');
        }
    }

    // =========================================================
    // 1. AUTHENTIFICATION (LOGIN / REGISTER / LOGOUT)
    // =========================================================

    public function register(){
        if(isLoggedIn()){ redirect('users/account'); }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '', 'email_err' => '', 'password_err' => '', 'confirm_password_err' => ''
            ];

            // Validation
            if(empty($data['name'])){ $data['name_err'] = 'Entrez votre nom'; }
            if(empty($data['email'])){ $data['email_err'] = 'Entrez votre email'; }
            elseif($this->userModel->findUserByEmail($data['email'])){ $data['email_err'] = 'Cet email est déjà utilisé'; }
            if(empty($data['password'])){ $data['password_err'] = 'Entrez un mot de passe'; }
            elseif(strlen($data['password']) < 6){ $data['password_err'] = '6 caractères minimum'; }
            if($data['password'] != $data['confirm_password']){ $data['confirm_password_err'] = 'Les mots de passe ne correspondent pas'; }

            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Hash du mot de passe
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                // Création du compte
                if($this->userModel->register($data)){
                    flash('register_success', 'Compte créé avec succès ! Connectez-vous.');
                    redirect('users/login');
                } else {
                    die('Erreur système');
                }
            } else {
                $this->view('front/users/register', $data);
            }
        } else {
            $data = ['name'=>'','email'=>'','password'=>'','confirm_password'=>'','name_err'=>'','email_err'=>'','password_err'=>'','confirm_password_err'=>''];
            $this->view('front/users/register', $data);
        }
    }

    public function login(){
        if(isLoggedIn()){ redirect('users/account'); }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '', 'password_err' => ''
            ];

            if(empty($data['email'])){ $data['email_err'] = 'Entrez votre email'; }
            if(empty($data['password'])){ $data['password_err'] = 'Entrez votre mot de passe'; }

            if($this->userModel->findUserByEmail($data['email'])){
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                if($loggedInUser){
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Mot de passe incorrect';
                    $this->view('front/users/login', $data);
                }
            } else {
                $data['email_err'] = 'Aucun compte trouvé avec cet email';
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
        $_SESSION['user_name'] = $user->full_name;
        $_SESSION['user_role'] = $user->role;
        
        // Redirection différente si Admin
        if($user->role == 'admin'){
            redirect('admin');
        } else {
            redirect('users/account');
        }
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
        redirect('users/login');
    }

    // =========================================================
    // 2. DASHBOARD CLIENT (MON COMPTE)
    // =========================================================
    public function account(){
        if(!isLoggedIn()){ redirect('users/login'); }

        $userId = $_SESSION['user_id'];
        
        // Récupération de toutes les données pour les onglets
        $data = [
            'user' => $this->userModel->getUserById($userId),
            'orders' => $this->orderModel->getOrdersByUserId($userId),
            'cards' => $this->cardModel->getUserCards($userId),
            'wishlist' => $this->wishlistModel->getUserWishlist($userId),
            'my_reviews' => $this->reviewModel->getReviewsByUserId($userId),
            'tab' => isset($_GET['tab']) ? $_GET['tab'] : 'dashboard'
        ];

        // --- TRAITEMENT DES FORMULAIRES POST ---
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            
            // A. Mise à jour Profil (Nom / Email / MDP)
            if(isset($_POST['update_details'])){
                $updateData = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'current_password' => trim($_POST['current_password']),
                    'new_password' => trim($_POST['new_password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                    'password' => '' 
                ];

                // Vérif MDP actuel
                if(!empty($updateData['current_password']) && !$this->userModel->checkPassword($userId, $updateData['current_password'])){
                    flash('product_message', 'Mot de passe actuel incorrect.', 'bg-red-100 text-red-700');
                } else {
                    // Changement MDP
                    if(!empty($updateData['new_password'])){
                        if($updateData['new_password'] != $updateData['confirm_password']){
                            flash('product_message', 'Les nouveaux mots de passe ne correspondent pas.', 'bg-red-100 text-red-700');
                            redirect('users/account?tab=details');
                            return;
                        }
                        $updateData['password'] = password_hash($updateData['new_password'], PASSWORD_DEFAULT);
                    }

                    if($this->userModel->updateDetails($userId, $updateData)){
                        $_SESSION['user_name'] = $updateData['name']; // Update session
                        flash('product_message', 'Détails mis à jour avec succès.');
                    }
                }
                redirect('users/account?tab=details');
            }

            // B. Mise à jour Adresses
            if(isset($_POST['update_address'])){
                $type = $_POST['address_type']; // billing ou shipping
                $addressData = [
                    'phone' => trim($_POST[$type.'_phone']),
                    'address' => trim($_POST[$type.'_address']),
                    'city' => trim($_POST[$type.'_city']),
                    'region' => trim($_POST[$type.'_region'])
                ];

                if($this->userModel->updateAddress($userId, $addressData, $type)){
                    flash('product_message', 'Adresse mise à jour.');
                }
                redirect('users/account?tab=addresses');
            }
        }

        $this->view('front/users/account', $data);
    }

    // =========================================================
    // 3. PAIEMENT & CARTES (PAYSTACK)
    // =========================================================
    
    // Callback pour enregistrer une carte après un paiement de vérification
    public function verify_payment_method(){
        if(!isLoggedIn()){ redirect('users/login'); }

        $reference = isset($_POST['paystack_ref']) ? $_POST['paystack_ref'] : '';

        if(!$reference){
            flash('product_message', 'Erreur de référence Paystack', 'bg-red-100 text-red-700');
            redirect('users/account?tab=payment');
        }

        // Vérification API Paystack
        $url = 'https://api.paystack.co/transaction/verify/' . $reference;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . PAYSTACK_SECRET_KEY]);
        $request = curl_exec($ch);
        curl_close($ch);

        $result = $request ? json_decode($request, true) : [];

        if (isset($result['data']) && $result['data']['status'] === 'success') {
            $auth = $result['data']['authorization'];
            
            // On vérifie si la carte est réutilisable
            if($auth['reusable']){
                $cardData = [
                    'user_id' => $_SESSION['user_id'],
                    'authorization_code' => $auth['authorization_code'],
                    'email' => $result['data']['customer']['email'],
                    'last4' => $auth['last4'],
                    'card_type' => $auth['card_type'],
                    'bank' => $auth['bank']
                ];

                if($this->cardModel->addCard($cardData)){
                    flash('product_message', 'Méthode de paiement sauvegardée !');
                } else {
                    flash('product_message', 'Cette carte existe déjà.', 'bg-orange-100 text-orange-700');
                }
            } else {
                flash('product_message', 'Cette méthode ne peut pas être sauvegardée.', 'bg-orange-100 text-orange-700');
            }
        } else {
            flash('product_message', 'Échec de la vérification.', 'bg-red-100 text-red-700');
        }

        redirect('users/account?tab=payment');
    }

    public function delete_card($id){
        if(!isLoggedIn()){ redirect('users/login'); }
        
        if($this->cardModel->deleteCard($id, $_SESSION['user_id'])){
            flash('product_message', 'Carte supprimée.');
        }
        redirect('users/account?tab=payment');
    }

    // =========================================================
    // 4. AVIS (REVIEWS)
    // =========================================================
    
    public function add_review(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            if(!isLoggedIn()){ redirect('users/login'); }
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'full_name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'],
                'product_id' => $_POST['product_id'], // Assure-toi que ce champ est dans le formulaire
                'rating' => $_POST['rating'],
                'comment' => trim($_POST['comment'])
            ];
            
            if($this->reviewModel->addReview($data)){
                flash('product_message', 'Merci ! Votre avis a été soumis pour modération.');
            }
            redirect('users/account?tab=review'); // Ou rediriger vers la page produit
        }
    }

    public function delete_review($id){
        if(!isLoggedIn()){ redirect('users/login'); }
        
        // Supprime l'avis seulement s'il appartient à l'utilisateur
        // (Méthode deleteOwnReview à ajouter dans ReviewModel ou utiliser une vérif manuelle)
        // Ici on suppose que le modèle vérifie ou que l'on vérifie avant
        $this->db->query("DELETE FROM reviews WHERE id = :id AND user_id = :uid");
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $_SESSION['user_id']);
        
        if($this->db->execute()){
            flash('product_message', 'Avis supprimé.');
        }
        redirect('users/account?tab=review');
    }

    // =========================================================
    // 5. FACTURE PDF (INVOICE)
    // =========================================================
    public function invoice($order_id){
        if(!isLoggedIn()){ redirect('users/login'); }

        // Vérifier si Dompdf est installé
        if(!class_exists('Dompdf\Dompdf')){
            die("Erreur : La librairie Dompdf n'est pas installée. Veuillez exécuter 'composer require dompdf/dompdf'.");
        }

        $order = $this->orderModel->getOrderById($order_id);

        // Sécurité : Vérifier que la commande appartient bien au user
        if($order->user_id != $_SESSION['user_id'] && $_SESSION['user_role'] != 'admin'){
            flash('product_message', 'Accès interdit.', 'bg-red-100 text-red-700');
            redirect('users/account');
        }

        $items = $this->orderModel->getOrderItems($order_id);
        
        // Capture du HTML
        ob_start();
        require APPROOT . '/Views/front/orders/invoice_pdf.php';
        $html = ob_get_clean();

        // Génération PDF
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Téléchargement
        $dompdf->stream("Facture-Exotikha-#".$order->order_number.".pdf", ["Attachment" => false]);
    }
}