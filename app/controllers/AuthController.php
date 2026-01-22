<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class AuthController extends Controller {

    // --- LOGIN ---
    public function login() {
        if (isset($_SESSION['user_id'])) $this->redirect('/profile'); // Déjà connecté ?
        $this->view('auth/login');
    }

    public function loginPost() {
        verify_csrf(); // Sécurité CSRF

        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        $userModel = new User();
        $user = $userModel->login($email, $password);

        if ($user) {
            // Création de la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];

            flash('success', 'Welcome back, ' . $user['full_name'] . '!');
            
            // Redirection intelligente (Admin ou User)
            if($user['role'] === 'admin') {
                $this->redirect('/admin/dashboard');
            } else {
                $this->redirect('/profile');
            }
        } else {
            flash('error', 'Identifiants incorrects.');
            $this->redirect('/login');
        }
    }

    // --- REGISTER ---
    public function register() {
        if (isset($_SESSION['user_id'])) $this->redirect('/profile');
        $this->view('auth/register');
    }

    public function registerPost() {
        verify_csrf();

        $name = htmlspecialchars(trim($_POST['full_name']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if ($password !== $confirm) {
            flash('error', 'Les mots de passe ne correspondent pas.');
            $this->redirect('/register');
        }

        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            flash('error', 'Cet email est déjà utilisé.');
            $this->redirect('/register');
        }

        // Création
        $userModel->register([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        // Connexion automatique après inscription
        $user = $userModel->login($email, $password);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['user_role'] = 'user';

        flash('success', 'Compte créé avec succès ! Bienvenue.');
        $this->redirect('/profile');
    }

    // --- LOGOUT ---
    public function logout() {
        session_destroy();
        header('Location: ' . url('/login'));
        exit;
    }
}