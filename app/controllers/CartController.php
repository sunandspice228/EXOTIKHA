<?php
class CartController extends Controller {
    public function __construct(){}

    public function index(){
        $data = [
            'cart' => isset($_SESSION['cart']) ? $_SESSION['cart'] : [],
            'total' => $this->calculateTotal()
        ];
        $this->view('front/cart/index', $data);
    }

    public function add($id){
        // On a besoin du modèle produit juste pour récupérer le prix et le nom
        $productModel = $this->model('Product');
        $product = $productModel->getProductById($id);

        if($product){
            // Création de l'item
            $item = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => '', // On pourrait ajouter l'image principale ici
                'qty' => 1
            ];

            // Si le panier n'existe pas
            if(!isset($_SESSION['cart'])){
                $_SESSION['cart'] = [];
            }

            // Si le produit existe déjà, on augmente la quantité
            if(isset($_SESSION['cart'][$id])){
                $_SESSION['cart'][$id]['qty']++;
            } else {
                $_SESSION['cart'][$id] = $item;
            }
            
            flash('cart_msg', 'Produit ajouté au panier');
            redirect('shop');
        }
    }
    // 1. AFFICHER LE FORMULAIRE DE LIVRAISON
    public function checkout(){
        if(!isLoggedIn()) redirect('users/login');
        if(empty($_SESSION['cart'])) redirect('shop');

        // Charger les régions pour le select
        $orderModel = $this->model('Order');
        $regions = $orderModel->getGhanaRegions();

        $data = [
            'cart' => $_SESSION['cart'],
            'total' => $this->calculateTotal(),
            'regions' => $regions
        ];

        $this->view('front/cart/checkout', $data);
    }

    // 2. TRAITER LA VALIDATION FINALE
    public function process_order(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if(!isLoggedIn()) redirect('users/login');

            // Récupérer les données du formulaire
            $shippingData = [
                'phone' => trim($_POST['phone']),
                'region' => trim($_POST['region']),
                'city' => trim($_POST['city']),
                'address' => trim($_POST['address']) // Ex: GPS Address GA-123-4567
            ];

            $orderModel = $this->model('Order');
            $total = $this->calculateTotal();

            if($orderModel->createOrder($_SESSION['user_id'], $total, $_SESSION['cart'], $shippingData)){
                unset($_SESSION['cart']);
                flash('product_message', 'Medaase! Votre commande a été reçue.');
                redirect('shop');
            } else {
                die('Erreur système.');
            }
        }
    }

    public function remove($id){
        if(isset($_SESSION['cart'][$id])){
            unset($_SESSION['cart'][$id]);
        }
        redirect('cart');
    }

    public function clear(){
        unset($_SESSION['cart']);
        redirect('cart');
    }

    private function calculateTotal(){
        $total = 0;
        if(isset($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $item){
                $total += $item['price'] * $item['qty'];
            }
        }
        return $total;
    }
    // Validation de la commande
    
}