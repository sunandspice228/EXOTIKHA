<?php
class UsersController extends Controller {
    private $userModel;

    public function __construct(){
        $this->userModel = $this->model('User');
    }

    public function register(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            if(empty($data['email'])){ $data['email_err'] = 'Email requis'; }
            // Vérifier si l\'email existe déjà
            if($this->userModel->findUserByEmail($data['email'])){
                $data['email_err'] = 'Cet email est déjà pris';
            }
            if(empty($data['name'])){ $data['name_err'] = 'Nom requis'; }
            if(empty($data['password'])){ $data['password_err'] = 'Mot de passe requis'; }
            if($data['password'] != $data['confirm_password']){ $data['confirm_password_err'] = 'Les mots de passe ne correspondent pas'; }

            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Hash du mot de passe
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if($this->userModel->register($data)){
                    flash('register_success', 'Inscription réussie, connectez-vous');
                    redirect('users/login');
                }
            } else {
                $this->view('front/users/register', $data);
            }
        } else {
            $data = ['name' => '', 'email' => '', 'password' => '', 'confirm_password' => ''];
            $this->view('front/users/register', $data);
        }
    }

    public function login(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            
            $loggedInUser = $this->userModel->login($email, $password);

            if($loggedInUser){
                $this->createUserSession($loggedInUser);
            } else {
                $data = ['email' => $email, 'password' => '', 'email_err' => 'Email ou mot de passe incorrect'];
                $this->view('front/users/login', $data);
            }
        } else {
            $data = ['email' => '', 'password' => '', 'email_err' => ''];
            $this->view('front/users/login', $data);
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->full_name;
        $_SESSION['user_role'] = $user->role;
        
        if($user->role === 'admin'){
            redirect('admin/index');
        } else {
            redirect('shop/index');
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

    // Page Mon Profil (Affichage + Traitement Upload)
    public function profile(){
        // 1. Sécurité : Il faut être connecté
        if(!isLoggedIn()){
            redirect('users/login');
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);

        // 2. Traitement du Formulaire (Upload Image)
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            // Vérifier si une image a été envoyée
            if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0){
                
                // Dossier de destination (Crée le dossier s'il n'existe pas)
                $targetDir = 'uploads/avatars/';
                $absDir = APPROOT . '/../public/' . $targetDir;
                
                if (!file_exists($absDir)) {
                    mkdir($absDir, 0777, true);
                }

                $fileName = $_FILES['avatar']['name'];
                $fileTmp = $_FILES['avatar']['tmp_name'];
                
                // Renommer l'image (ID_User + Timestamp) pour éviter les doublons
                $newFileName = 'user_' . $userId . '_' . time() . '_' . $fileName;
                $targetFile = $absDir . $newFileName;
                $dbPath = $targetDir . $newFileName;

                // Déplacement du fichier
                if(move_uploaded_file($fileTmp, $targetFile)){
                    // Mise à jour BDD
                    if($this->userModel->updateAvatar($userId, $dbPath)){
                        // Mise à jour Session (pour l'affichage immédiat dans le header)
                        $_SESSION['user_image'] = $dbPath;
                        
                        flash('profile_msg', 'Photo de profil mise à jour !');
                        redirect('users/profile');
                    }
                } else {
                    flash('profile_msg', 'Erreur lors de l\'upload de l\'image.', 'alert alert-danger');
                }
            }
        }

        // 3. Affichage de la Vue
        $data = [
            'user' => $user
        ];
        $this->view('front/users/profile', $data);
    }
}