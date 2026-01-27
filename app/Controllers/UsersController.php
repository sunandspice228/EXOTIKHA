<?php
class UsersController extends Controller {
    private $userModel;

    public function __construct(){
        $this->userModel = $this->model('User');
    }

    public function register(){
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

            if(empty($data['email'])){ $data['email_err'] = 'Email requis'; }
            elseif($this->userModel->findUserByEmail($data['email'])){
                $data['email_err'] = 'Cet email est déjà pris';
            }
            if(empty($data['name'])){ $data['name_err'] = 'Nom requis'; }
            if(empty($data['password'])){ $data['password_err'] = 'Mot de passe requis'; }
            if($data['password'] != $data['confirm_password']){ $data['confirm_password_err'] = 'Les mots de passe ne correspondent pas'; }

            if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
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
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
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

    // MODIFICATION IMPORTANTE ICI : On stocke l'image dans la session
    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->full_name;
        $_SESSION['user_role'] = $user->role;
        // On vérifie si l'utilisateur a une image, sinon on met une par défaut
        $_SESSION['user_image'] = !empty($user->image) ? $user->image : 'img/default-avatar.png';
        
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
        unset($_SESSION['user_image']); // Ne pas oublier de supprimer l'image
        session_destroy();
        redirect('users/login');
    }

    // Page Mon Profil (Affichage + Traitement Upload)
    public function profile(){
        if(!isLoggedIn()){ redirect('users/login'); }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Ajoute cette ligne pour protéger le formulaire
        verifyCsrfToken();
            
            if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0){
                
                // Sécurité : Vérifier le type de fichier
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['avatar']['name'];
                $fileext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if(in_array($fileext, $allowed)){
                    $targetDir = 'uploads/avatars/';
                    $absDir = APPROOT . '/../public/' . $targetDir;
                    
                    if (!file_exists($absDir)) {
                        mkdir($absDir, 0777, true);
                    }

                    // Nom unique pour éviter l'écrasement et les problèmes de cache
                    $newFileName = 'user_' . $userId . '_' . time() . '.' . $fileext;
                    $targetFile = $absDir . $newFileName;
                    $dbPath = $targetDir . $newFileName;

                    if(move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)){
                        // Suppression de l'ancienne image si elle existe et n'est pas celle par défaut
                        if(!empty($user->image) && file_exists(APPROOT . '/../public/' . $user->image)){
                            unlink(APPROOT . '/../public/' . $user->image);
                        }

                        if($this->userModel->updateAvatar($userId, $dbPath)){
                            $_SESSION['user_image'] = $dbPath; // Mise à jour immédiate de la session
                            flash('profile_msg', 'Photo de profil mise à jour !');
                            redirect('users/profile');
                        }
                    } else {
                        flash('profile_msg', 'Erreur lors du déplacement du fichier.', 'alert alert-danger');
                    }
                } else {
                    flash('profile_msg', 'Format incorrect (JPG, PNG, GIF uniquement).', 'alert alert-danger');
                }
            }
        }

        $data = ['user' => $user];
        $this->view('front/users/profile', $data);
    }
}