<?php
// On charge le helper pour l'envoi d'email
require_once '../app/Helpers/mail_helper.php';

class Cart extends Controller {
    private $productModel;
    private $orderModel;

    public function __construct(){
        // Chargement des modèles nécessaires
        $this->productModel = $this->model('Product');
        $this->orderModel = $this->model('Order');
    }

    // =========================================================
    // 1. AFFICHER LE PANIER
    // =========================================================
    public function index(){
        $cartItems = [];
        $total = 0;

        // Si le panier existe en session
        if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0){
            foreach($_SESSION['cart'] as $id => $qty){
                $product = $this->productModel->getProductById($id);
                
                if($product){
                    // LOGIQUE PRIX : Si promo active, on prend le prix promo
                    $price = (!empty($product->promo_price) && $product->promo_price > 0) ? $product->promo_price : $product->price;
                    
                    // On prépare les données pour la vue
                    $cartItems[] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'image' => $product->image, // Image principale
                        'price' => $price,
                        'qty' => $qty,
                        'subtotal' => $price * $qty,
                        'stock' => $product->stock,
                        'sku' => $product->sku
                    ];
                    
                    // Calcul du total global
                    $total += ($price * $qty);
                }
            }
        }

        $data = [
            'title' => 'My Shopping Bag',
            'cartItems' => $cartItems,
            'total' => $total
        ];

        $this->view('front/cart/index', $data);
    }

    // =========================================================
    // 2. AJOUTER UN PRODUIT AU PANIER
    // =========================================================
    public function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Nettoyage simple
            $productId = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
            $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

            if($qty < 1) $qty = 1;

            // Vérifier si le produit existe et a du stock
            $product = $this->productModel->getProductById($productId);
            
            if($product && $product->stock >= $qty){
                
                // Si le produit est déjà dans le panier, on ajoute la quantité
                if(isset($_SESSION['cart'][$productId])){
                    $newQty = $_SESSION['cart'][$productId] + $qty;
                    
                    // On vérifie si la NOUVELLE quantité totale ne dépasse pas le stock
                    if($newQty <= $product->stock){
                        $_SESSION['cart'][$productId] = $newQty;
                        flash('cart_msg', 'Product quantity updated in bag!', 'bg-green-100 text-green-700 border-green-200');
                    } else {
                        // On met le max disponible
                        $_SESSION['cart'][$productId] = $product->stock;
                        flash('cart_msg', 'Stock limit reached. Max available added.', 'bg-orange-100 text-orange-700 border-orange-200');
                    }
                } else {
                    // Sinon on l'ajoute
                    $_SESSION['cart'][$productId] = $qty;
                    flash('cart_msg', 'Product added to bag successfully!', 'bg-green-100 text-green-700 border-green-200');
                }
                
            } else {
                flash('cart_msg', 'Sorry, not enough stock available.', 'bg-red-100 text-red-700 border-red-200');
            }
            
            // Redirection vers la page précédente (Shop ou Détails)
            if(isset($_SERVER['HTTP_REFERER'])){
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                redirect('shop');
            }
        }
    }

    // =========================================================
    // 3. METTRE À JOUR LA QUANTITÉ (Depuis la page Panier)
    // =========================================================
    public function update(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['product_id'];
            $qty = (int)$_POST['qty'];

            if($qty <= 0){
                // Si quantité 0 ou moins, on supprime
                unset($_SESSION['cart'][$id]);
            } else {
                // Vérification du stock avant mise à jour
                $product = $this->productModel->getProductById($id);
                
                if($product->stock >= $qty){
                    $_SESSION['cart'][$id] = $qty;
                } else {
                    // On bloque au maximum du stock
                    $_SESSION['cart'][$id] = $product->stock;
                    flash('cart_msg', 'Max stock reached for this item.', 'bg-orange-100 text-orange-700 border-orange-200');
                }
            }
            redirect('cart');
        }
    }

    // =========================================================
    // 4. SUPPRIMER UN ITEM
    // =========================================================
    public function remove($id){
        if(isset($_SESSION['cart'][$id])){
            unset($_SESSION['cart'][$id]);
        }
        flash('cart_msg', 'Item removed from bag.');
        redirect('cart');
    }

    // =========================================================
    // 5. CAISSE & VALIDATION COMMANDE (CHECKOUT)
    // =========================================================
    public function checkout(){
        // A. Vérifier si panier vide
        if(!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0){
            redirect('shop');
        }

        // B. Vérifier si utilisateur connecté
        if(!isLoggedIn()){
            flash('product_message', 'Please login to complete your order.', 'bg-blue-100 text-blue-700 border-blue-200');
            redirect('users/login');
        }

        // C. Préparer les données du panier pour le calcul final
        $cartItems = [];
        $total = 0;
        
        foreach($_SESSION['cart'] as $id => $qty){
            $product = $this->productModel->getProductById($id);
            if($product){
                // Vérif prix promo
                $price = (!empty($product->promo_price) && $product->promo_price > 0) ? $product->promo_price : $product->price;
                
                // Vérif stock une dernière fois (au cas où il a changé entre temps)
                if($product->stock < $qty){
                    flash('cart_msg', 'Stock changed for ' . $product->name . '. Please update cart.', 'bg-red-100 text-red-700');
                    redirect('cart');
                    return;
                }

                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'qty' => $qty,
                    'subtotal' => $price * $qty
                ];
                $total += ($price * $qty);
            }
        }

        // D. TRAITEMENT DU FORMULAIRE (POST)
        // D. TRAITEMENT DU FORMULAIRE (POST)
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $shippingData = [
                'phone' => trim($_POST['phone']),
                'region' => trim($_POST['region']),
                'city' => trim($_POST['city']),
                'address' => trim($_POST['address'])
            ];
            
            $paymentMethod = $_POST['payment_method']; // paystack ou cod
            $paystackRef = $_POST['paystack_ref']; // Référence renvoyée par le JS

            // Validation simple
            if(empty($shippingData['phone']) || empty($shippingData['address'])){
                flash('cart_msg', 'Please fill in all shipping details.', 'bg-red-100 text-red-700');
                $this->view('front/cart/checkout', ['cartItems' => $cartItems, 'total' => $total]);
                return;
            }

            // --- VÉRIFICATION PAIEMENT PAYSTACK ---
            $isPaid = false;
            if($paymentMethod == 'paystack'){
                if(empty($paystackRef)){
                    flash('cart_msg', 'Payment failed. No reference provided.', 'bg-red-100 text-red-700');
                    redirect('cart/checkout');
                    return;
                }

                // On vérifie la transaction via l'API Paystack (côté serveur)
                if($this->verifyPaystack($paystackRef)){
                    $isPaid = true; // C'est tout bon !
                } else {
                    flash('cart_msg', 'Payment verification failed. Please try again.', 'bg-red-100 text-red-700');
                    redirect('cart/checkout');
                    return;
                }
            }

            // --- CRÉATION DE LA COMMANDE ---
            // On passe $paymentMethod et $paystackRef au modèle
            if($this->orderModel->createOrder($_SESSION['user_id'], $total, $cartItems, $shippingData, $paymentMethod, $paystackRef)){
                
                // Email de confirmation (adapte le message selon le paiement)
                $paymentText = ($paymentMethod == 'paystack') ? "Paid via Paystack (Ref: $paystackRef)" : "Cash on Delivery";
                
                $userEmail = $_SESSION['user_email'];
                $userName = $_SESSION['user_name'];
                $subject = "Order Confirmation - Exotikha";
                $message = "
                <html>
                <body style='font-family: Arial, sans-serif;'>
                    <h1 style='color: #4f46e5;'>Thank you for your order!</h1>
                    <p>Hi $userName,</p>
                    <p>We received your order. Delivery to <b>" . $shippingData['city'] . "</b>.</p>
                    <p><b>Total:</b> " . CURRENCY_SYMBOL . " " . number_format($total, 2) . "</p>
                    <p><b>Payment:</b> $paymentText</p>
                    <br>
                    <p>The Exotikha Team</p>
                </body>
                </html>
                ";
                
                sendEmail($userEmail, $subject, $message);

                unset($_SESSION['cart']);
                flash('product_message', 'Order placed successfully!', 'bg-green-100 text-green-700 border-green-200');
                redirect('users/account?tab=orders');

            } else {
                die('System Error: Could not place order.');
            }
        }
    }
    // VÉRIFICATION PAYSTACK (Serveur à Serveur)
    private function verifyPaystack($reference){
        $url = "https://api.paystack.co/transaction/verify/" . rawurlencode($reference);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . PAYSTACK_SECRET_KEY,
            "Cache-Control: no-cache",
        ]);
        
        $result = curl_exec($ch);
        curl_close($ch);
        
        if($result){
            $json = json_decode($result, true);
            if($json['status'] && $json['data']['status'] == 'success'){
                return true; // Transaction valide
            }
        }
        return false; // Échec ou fraude
    }
}