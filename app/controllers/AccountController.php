<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class AccountController extends Controller {

    public function __construct() {
        // Middleware : Si pas connecté, oust !
        if (!isset($_SESSION['user_id'])) {
            flash('error', 'Veuillez vous connecter pour accéder à votre compte.');
            $this->redirect('/login');
        }
    }

    public function index() {
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        $orders = $userModel->getOrdersByUserId($_SESSION['user_id']);

        $this->view('account/index', [
            'user' => $user,
            'orders' => $orders
        ]);
    }

    public function update() {
        verify_csrf();
        
        $data = [
            'name' => htmlspecialchars($_POST['full_name']),
            'phone' => htmlspecialchars($_POST['phone']),
            'address' => htmlspecialchars($_POST['address']),
            'city' => htmlspecialchars($_POST['city'])
        ];

        $userModel = new User();
        $userModel->updateProfile($_SESSION['user_id'], $data);
        
        // Mettre à jour la session nom
        $_SESSION['user_name'] = $data['name'];

        flash('success', 'Profil mis à jour.');
        $this->redirect('/profile');
    }
}