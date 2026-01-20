<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;

class CartController extends Controller {

    public function index() {
        $cart = get_cart();
        $prodModel = new Product();
        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $qty) {
            $product = $prodModel->findById($id);
            if ($product) {
                $product['qty'] = $qty;
                $product['real_price'] = $product['price']; // Tu pourras ajouter la logique promo ici
                $cartItems[] = $product;
                $total += $product['real_price'] * $qty;
            }
        }

        $this->view('cart/index', ['cartItems' => $cartItems, 'total' => $total]);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['product_id'];
            $qty = (int) $_POST['qty'];

            if (!isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id] = 0;
            }
            $_SESSION['cart'][$id] += $qty;

            flash('success', lang('add_to_cart') . ' OK');
            $this->redirect('/cart');
        }
    }

    public function remove($id) {
        unset($_SESSION['cart'][$id]);
        $this->redirect('/cart');
    }

    public function update($action, $id) {
        if (isset($_SESSION['cart'][$id])) {
            if ($action === 'inc') $_SESSION['cart'][$id]++;
            if ($action === 'dec' && $_SESSION['cart'][$id] > 1) $_SESSION['cart'][$id]--;
        }
        $this->redirect('/cart');
    }
}