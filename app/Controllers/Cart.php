<?php
// On charge le helper
require_once '../app/Helpers/mail_helper.php';

// Si tu n'as pas installé Dompdf via Composer, commente ces lignes
// require_once '../vendor/autoload.php'; 
// use Dompdf\Dompdf;
// use Dompdf\Options;

class Cart extends Controller {
    private $productModel;
    private $orderModel;
    private $userModel;

    public function __construct(){
        $this->productModel = $this->model('Product');
        $this->orderModel = $this->model('Order');
        $this->userModel = $this->model('User');
    }

    // 1. AFFICHER LE PANIER (CORRIGÉ POUR L'AFFICHAGE)
    public function index(){
        $cartData = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $cartItems = [];
        $total = 0;

        // On transforme les tableaux de session en Objets pour la Vue
        foreach($cartData as $key => $item){
            // Conversion Array -> Object
            $obj = (object) $item; 
            
            // Calcul du total
            $lineTotal = $obj->price * $obj->qty;
            $obj->line_total = $lineTotal; // On ajoute le total de la ligne
            $total += $lineTotal;

            $cartItems[] = $obj;
        }

        $data = [
            'cart_items' => $cartItems, // J'ai renommé pour correspondre à la Vue
            'total' => $total
        ];

        $this->view('front/cart/index', $data);
    }

    // 2. AJOUTER UN PRODUIT
    public function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            // Protection CSRF
            verifyCsrfToken();
            
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $product_id = (int)$_POST['product_id'];
            $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $variant_id = isset($_POST['variant_id']) && !empty($_POST['variant_id']) ? (int)$_POST['variant_id'] : null;

            if($qty < 1) $qty = 1;

            $product = $this->productModel->getProductById($product_id);
            if(!$product){ redirect('shop'); }

            // Vérif Variante (Si ton modèle supporte getVariantById)
            $variant = null;
            if($variant_id && method_exists($this->productModel, 'getVariantById')){
                $variant = $this->productModel->getVariantById($variant_id);
            }

            // Vérif Stock
            $availableStock = ($variant) ? $variant->stock : $product->stock;

            if($availableStock < $qty){
                // flash('cart_msg', 'Stock insuffisant.', 'bg-red-500 text-white'); // Si tu as le helper flash
                redirect('shop/product/' . $product_id);
                return;
            }

            // Clé unique pour le panier
            $cartKey = $product_id . '-' . ($variant_id ?? '0');

            if(!isset($_SESSION['cart'])){ $_SESSION['cart'] = []; }

            // Ajout ou Mise à jour
            if(isset($_SESSION['cart'][$cartKey])){
                $newQty = $_SESSION['cart'][$cartKey]['qty'] + $qty;
                
                if($newQty <= $availableStock){
                    $_SESSION['cart'][$cartKey]['qty'] = $newQty;
                } else {
                    $_SESSION['cart'][$cartKey]['qty'] = $availableStock;
                }
            } else {
                // Prix Promo ou Normal
                $price = ($product->promo_price > 0) ? $product->promo_price : $product->price;

                $_SESSION['cart'][$cartKey] = [
                    'id' => $product_id,
                    'variant_id' => $variant_id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'image' => $product->image,
                    'price' => $price,
                    'qty' => $qty,
                    'size' => $variant ? $variant->size : null,
                    'color' => $variant ? $variant->color : null,
                    'stock' => $availableStock // Important pour la limite dans le panier
                ];
            }

            // Mise à jour du compteur global
            $totalQty = 0;
            foreach($_SESSION['cart'] as $item){ $totalQty += $item['qty']; }
            $_SESSION['cart_count'] = $totalQty;
            
            redirect('cart'); // On va directement au panier pour voir le résultat
        } else {
            redirect('shop');
        }
    }

    // 3. MISE À JOUR (UPDATE)
    public function update(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $productId = $_POST['product_id']; // Attention : ici on utilise l'ID comme clé simplifiée
            $qty = (int)$_POST['quantity'];

            // Recherche de la clé correcte dans le panier (car product_id n'est pas la clé complète si variantes)
            foreach($_SESSION['cart'] as $key => $item){
                if($item['id'] == $productId){
                    if($qty <= 0){
                        unset($_SESSION['cart'][$key]);
                    } else {
                        // Vérif stock max
                        $max = isset($item['stock']) ? $item['stock'] : 100;
                        $_SESSION['cart'][$key]['qty'] = ($qty > $max) ? $max : $qty;
                    }
                }
            }
            redirect('cart');
        }
    }

    // 4. SUPPRIMER (REMOVE)
    public function remove($id){
        // On parcourt pour trouver l'ID produit et supprimer la ligne
        if(isset($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $key => $item){
                if($item['id'] == $id){
                    unset($_SESSION['cart'][$key]);
                    break; 
                }
            }
        }
        redirect('cart');
    }

    // 5. VIDER LE PANIER
    public function clear(){
        unset($_SESSION['cart']);
        unset($_SESSION['cart_count']);
        redirect('cart');
    }

    // 6. CHECKOUT (Ta logique existante conservée)
    public function checkout(){
        // 1. Vérifications de base
        if(!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) redirect('shop');
        if(!isLoggedIn()){ 
            flash('login_msg', 'Veuillez vous connecter pour commander');
            redirect('users/login'); 
        }

        $cartItems = [];
        $subtotal = 0;

        // 2. Préparation des données pour la vue (Transformation en Objets)
        foreach($_SESSION['cart'] as $item){
            $obj = (object)$item; 
            // On s'assure que le prix et la qté sont numériques
            $obj->line_total = (float)$obj->price * (int)$obj->qty;
            $subtotal += $obj->line_total;
            $cartItems[] = $obj;
        }

        // 3. Traitement du Formulaire (POST)
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            verifyCsrfToken(); // Sécurité
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // A. Récupération des données calculées par le JS (Livraison & GPS)
            $shippingCost = isset($_POST['shipping_cost']) ? (float)$_POST['shipping_cost'] : 0;
            
            $gpsLat = $_POST['gps_lat'] ?? '';
            $gpsLng = $_POST['gps_lng'] ?? '';
            // On concatène pour stockage (ex: "5.6037,-0.1870") ou NULL si vide
            $gpsCoordinates = (!empty($gpsLat) && !empty($gpsLng)) ? "$gpsLat,$gpsLng" : null;

            // B. Calcul du Total Final à payer
            $finalTotal = $subtotal + $shippingCost;

            // C. Préparation des données de livraison
            $shippingData = [
                'full_name' => trim($_POST['full_name']),
                'email'     => $_SESSION['user_email'], // On prend l'email du compte connecté par sécurité
                'phone'     => trim($_POST['phone']),
                'address'   => trim($_POST['address']),
                'city'      => trim($_POST['city']),
                'region'    => trim($_POST['region'] ?? 'Accra'),
                'gps'       => $gpsCoordinates
            ];
            
            $paymentMethod = $_POST['payment_method']; 
            $paystackRef = $_POST['paystack_ref'] ?? null;

            // D. Appel au Modèle
            if($this->orderModel->createOrder($_SESSION['user_id'], $finalTotal, $shippingCost, $_SESSION['cart'], $shippingData, $paymentMethod, $paystackRef)){
                
                // E. Succès : Nettoyage et Redirection
                unset($_SESSION['cart']);
                unset($_SESSION['cart_count']);
                
                // On peut envoyer un email ici si besoin
                // $this->sendOrderEmail(...);

                redirect('cart/payment_success');
            } else {
                die('Erreur système critique lors de l\'enregistrement de la commande. Veuillez contacter le support.');
            }
        }
        
        // Affichage de la vue (GET)
        $data = [
            'cartItems' => $cartItems, 
            'total' => $subtotal // On envoie le sous-total produits à la vue, le JS ajoutera la livraison visuellement
        ];
        $this->view('front/cart/checkout', $data);
    }

    public function payment_success(){
        if(!isLoggedIn()){ redirect('users/login'); }
        $order = $this->orderModel->getLastOrder($_SESSION['user_id']);
        $this->view('front/cart/success', ['order' => $order]);
    }
}