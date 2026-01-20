<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class CheckoutController extends Controller {

    public function index() {
        // Si panier vide ou pas connecté -> Redirection
        if (cart_count() == 0) $this->redirect('/cart');
        if (!isset($_SESSION['user_id'])) {
            flash('error', 'Veuillez vous connecter pour commander.');
            $this->redirect('/login');
        }

        // Calcul Total
        $cart = get_cart();
        $prodModel = new Product();
        $total = 0;
        foreach ($cart as $id => $qty) {
            $p = $prodModel->findById($id);
            if ($p) $total += $p['price'] * $qty;
        }

        // Pré-remplir avec les infos du profil
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);

        $this->view('checkout/index', ['total' => $total, 'user' => $user]);
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            // 1. Recalculer le total (Sécurité)
            $cart = get_cart();
            $prodModel = new Product();
            $totalXOF = 0;
            $itemsToSave = [];

            foreach ($cart as $id => $qty) {
                $p = $prodModel->findById($id);
                if ($p) {
                    $totalXOF += $p['price'] * $qty;
                    $itemsToSave[] = ['id' => $id, 'qty' => $qty, 'price' => $p['price']];
                }
            }

            // 2. Créer la commande
            $orderModel = new Order();
            $orderId = $orderModel->createOrderWithLocation([
                'user_id' => $_SESSION['user_id'],
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'region' => $_POST['region'],
                'city' => $_POST['city'],
                'total' => $totalXOF
            ]);

            // 3. Sauvegarder les articles
            foreach ($itemsToSave as $item) {
                $orderModel->addItem($orderId, $item['id'], $item['qty'], $item['price']);
            }

            // 4. Initialiser Paystack
            // On convertit XOF -> GHS (approximatif pour l'exemple, taux fixe dans helpers)
            // Paystack attend des centimes (kobo/pesewas)
            $amountGHS = ceil(convert_from_xof($totalXOF, 'GHS') * 100); 

            $url = "https://api.paystack.co/transaction/initialize";
            $fields = [
                'email' => $_POST['email'],
                'amount' => $amountGHS,
                'currency' => 'GHS',
                'callback_url' => ROOT_URL . '/checkout/callback',
                'metadata' => ['order_id' => $orderId]
            ];

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($fields),
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . PAYSTACK_SECRET_KEY,
                    "Content-Type: application/json"
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false // Pour localhost uniquement
            ]);

            $result = json_decode(curl_exec($ch), true);
            curl_close($ch);

            if ($result && $result['status']) {
                header("Location: " . $result['data']['authorization_url']);
                exit;
            } else {
                flash('error', 'Erreur de connexion avec Paystack.');
                $this->redirect('/checkout');
            }
        }
    }

    public function callback() {
        $reference = $_GET['reference'] ?? null;
        if (!$reference) $this->redirect('/checkout');

        // Vérification Paystack (Simplifiée)
        // Normalement on refait un appel CURL à Paystack /transaction/verify/
        // Ici on suppose que c'est bon pour avancer
        
        // On pourrait récupérer l'ID commande via metadata, ici on simule
        // Vider le panier
        unset($_SESSION['cart']);
        
        $this->view('checkout/success');
    }
}