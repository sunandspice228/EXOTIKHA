<?php
class Orders extends Controller {
    private $orderModel;
    private $userModel;

    public function __construct(){
        if(!isLoggedIn()){
            redirect('users/login');
        }
        $this->orderModel = $this->model('Order');
        $this->userModel = $this->model('User');
    }

    // LISTE DES COMMANDES (Historique)
    public function index(){
        $orders = $this->orderModel->getOrdersByUserId($_SESSION['user_id']);
        
        $data = [
            'orders' => $orders
        ];

        $this->view('front/users/orders', $data);
    }

    // DÉTAILS D'UNE COMMANDE (C'est ici que ça bloquait)
    public function details($id){
        // 1. Récupérer la commande
        $order = $this->orderModel->getOrderById($id);

        // 2. VÉRIFICATIONS DE SÉCURITÉ
        // Si la commande n'existe pas
        if(!$order){
            flash('msg', 'Commande introuvable', 'alert-danger');
            redirect(''); // Retour accueil
            return;
        }

        // Si la commande n'appartient pas à l'utilisateur connecté
        if($order->user_id != $_SESSION['user_id']){
            flash('msg', 'Accès non autorisé', 'alert-danger');
            redirect(''); // Retour accueil (C'est souvent ici que ça redirigeait)
            return;
        }

        // 3. Récupérer les articles
        $items = $this->orderModel->getOrderItems($id);

        $data = [
            'order' => $order,
            'items' => $items
        ];

        $this->view('front/orders/details', $data);
    }
}