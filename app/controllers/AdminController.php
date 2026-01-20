<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;

class AdminController extends Controller {

    public function login() {
        // Si déjà connecté, on redirige
        if (isset($_SESSION['admin_id'])) $this->redirect('/admin/dashboard');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $userModel = new User();
            $user = $userModel->findByEmail($_POST['email']);

            if ($user && $user['role'] === 'admin' && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['admin_id'] = $user['id'];
                // On met aussi user_id pour éviter les conflits si le header est partagé
                $_SESSION['user_name'] = $user['name'];
                
                $this->redirect('/admin/dashboard');
            } else {
                flash('error', 'Identifiants invalides ou accès non autorisé.');
            }
        }
        $this->view('admin/login');
    }

    public function dashboard() {
        // 1. SÉCURITÉ ANTI-RETOUR
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // 2. Vérification Admin
        if (!isset($_SESSION['admin_id'])) {
            $this->redirect('/admin/login');
        }

        $prodModel = new Product();
        $orderModel = new Order();
        
        $stats = [
            'products' => count($prodModel->findAll()),
            'orders' => count($orderModel->getAll()),
            'recent_orders' => array_slice($orderModel->getAll(), 0, 5)
        ];

        $this->view('admin/dashboard', $stats);
    }

    public function logout() {
        // Destruction totale de la session
        session_unset();
        session_destroy();
        
        // Redirection
        $this->redirect('/admin/login');
    }
}