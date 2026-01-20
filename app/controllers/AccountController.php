<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Order;
use App\Models\User;

class AccountController extends Controller {

    public function __construct() {
        // 1. SÉCURITÉ ANTI-RETOUR (Force le navigateur à ne pas mettre en cache)
        header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1
        header('Pragma: no-cache'); // HTTP 1.0
        header('Expires: 0'); // Proxies

        // 2. Vérification de connexion
        if (!isset($_SESSION['user_id'])) {
            flash('error', 'Veuillez vous connecter pour accéder à votre compte.');
            $this->redirect('/login');
        }
    }

    public function index() {
        $userModel = new User();
        $orderModel = new Order();
        $userId = $_SESSION['user_id'];

        $user = $userModel->findById($userId);
        $tab = $_GET['tab'] ?? 'dashboard';
        
        $data = ['user' => $user, 'activeTab' => $tab];

        switch ($tab) {
            case 'orders':
                $data['orders'] = $orderModel->getByUserId($userId);
                break;
            case 'settings':
                break;
            case 'dashboard':
            default:
                $orders = $orderModel->getByUserId($userId);
                $data['last_order'] = $orders[0] ?? null;
                $data['total_orders'] = count($orders);
                break;
        }

        $this->view('account/dashboard', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $userModel = new User();
            
            $userModel->updateProfile($_SESSION['user_id'], [
                'name' => strip_tags($_POST['name']),
                'phone' => strip_tags($_POST['phone']),
                'address' => strip_tags($_POST['address']),
                'city' => strip_tags($_POST['city']),
                'region' => strip_tags($_POST['region'])
            ]);

            if (!empty($_POST['new_password'])) {
                $userModel->updatePassword($_SESSION['user_id'], password_hash($_POST['new_password'], PASSWORD_DEFAULT));
            }

            flash('success', 'Profil mis à jour !');
            $this->redirect('/account?tab=settings');
        }
    }
}