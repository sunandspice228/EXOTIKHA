<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class AuthController extends Controller {

    // Afficher Login / Traiter Login
    public function login() {
        // Si déjà connecté, on redirige
        if (isset($_SESSION['user_id'])) $this->redirect('/account');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            
            $userModel = new User();
            $user = $userModel->findByEmail($_POST['email']);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                // Session User
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirection selon le rôle
                if ($user['role'] === 'admin') {
                    $this->redirect('/admin/dashboard');
                } else {
                    $this->redirect('/account');
                }
            } else {
                flash('error', 'Email ou mot de passe incorrect.');
            }
        }

        $this->view('auth/login');
    }

    // Inscription
    public function register() {
        if (isset($_SESSION['user_id'])) $this->redirect('/account');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            if ($_POST['password'] !== $_POST['confirm_password']) {
                flash('error', 'Les mots de passe ne correspondent pas.');
                $this->redirect('/register');
            }

            $userModel = new User();
            // Vérifier si email existe
            if ($userModel->findByEmail($_POST['email'])) {
                flash('error', 'Cet email est déjà utilisé.');
            } else {
                $userId = $userModel->create($_POST);
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $_POST['name'];
                $_SESSION['user_role'] = 'user';
                
                flash('success', 'Bienvenue ! Compte créé avec succès.');
                $this->redirect('/account');
            }
        }

        $this->view('auth/register');
    }

    // Dans AuthController.php

    public function logout() {
        // On vide toutes les variables de session
        session_unset();
        // On détruit la session côté serveur
        session_destroy();
        
        // On redirige
        $this->redirect('/login');
    }
}