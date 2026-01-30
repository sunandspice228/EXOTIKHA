<?php
// On charge le helper
require_once '../app/Helpers/mail_helper.php';

// Si tu n'as pas installé Dompdf via Composer, commente ces lignes
require_once '../vendor/autoload.php'; 
use Dompdf\Dompdf;
use Dompdf\Options;

class Cart extends Controller {
    private $productModel;
    private $orderModel;
    private $userModel;

    public function __construct(){
        $this->productModel = $this->model('Product');
        $this->orderModel = $this->model('Order');
        $this->userModel = $this->model('User');
    }

    // =========================================================
    // 1. AFFICHER LE PANIER
    // =========================================================
    public function index(){
        $cartData = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $cartItems = [];
        $total = 0;

        foreach($cartData as $key => $item){
            $obj = (object) $item; 
            $lineTotal = $obj->price * $obj->qty;
            $obj->line_total = $lineTotal;
            $total += $lineTotal;
            $cartItems[] = $obj;
        }

        $data = [
            'cart_items' => $cartItems,
            'total' => $total
        ];

        $this->view('front/cart/index', $data);
    }

    // =========================================================
    // 2. AJOUTER UN PRODUIT
    // =========================================================
    public function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            verifyCsrfToken();
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $product_id = (int)$_POST['product_id'];
            $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            $variant_id = isset($_POST['variant_id']) && !empty($_POST['variant_id']) ? (int)$_POST['variant_id'] : null;

            if($qty < 1) $qty = 1;

            $product = $this->productModel->getProductById($product_id);
            if(!$product){ redirect('shop'); }

            $variant = null;
            if($variant_id && method_exists($this->productModel, 'getVariantById')){
                $variant = $this->productModel->getVariantById($variant_id);
            }

            $availableStock = ($variant) ? $variant->stock : $product->stock;

            if($availableStock < $qty){
                redirect('shop/product/' . $product_id); // Tu pourrais ajouter un flash message ici
                return;
            }

            $cartKey = $product_id . '-' . ($variant_id ?? '0');

            if(!isset($_SESSION['cart'])){ $_SESSION['cart'] = []; }

            if(isset($_SESSION['cart'][$cartKey])){
                $newQty = $_SESSION['cart'][$cartKey]['qty'] + $qty;
                $_SESSION['cart'][$cartKey]['qty'] = ($newQty <= $availableStock) ? $newQty : $availableStock;
            } else {
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
                    'stock' => $availableStock
                ];
            }

            // Compteur global
            $totalQty = 0;
            foreach($_SESSION['cart'] as $item){ $totalQty += $item['qty']; }
            $_SESSION['cart_count'] = $totalQty;
            
            redirect('cart');
        } else {
            redirect('shop');
        }
    }

    // =========================================================
    // 3. MISE À JOUR (UPDATE)
    // =========================================================
    public function update(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $productId = $_POST['product_id'];
            $qty = (int)$_POST['quantity'];

            foreach($_SESSION['cart'] as $key => $item){
                if($item['id'] == $productId){
                    if($qty <= 0){
                        unset($_SESSION['cart'][$key]);
                    } else {
                        $max = isset($item['stock']) ? $item['stock'] : 100;
                        $_SESSION['cart'][$key]['qty'] = ($qty > $max) ? $max : $qty;
                    }
                }
            }
            redirect('cart');
        }
    }

    // =========================================================
    // 4. SUPPRIMER (REMOVE)
    // =========================================================
    public function remove($id){
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

    // =========================================================
    // 5. VIDER LE PANIER
    // =========================================================
    public function clear(){
        unset($_SESSION['cart']);
        unset($_SESSION['cart_count']);
        redirect('cart');
    }

    // =========================================================
    // 6. CHECKOUT (PAGE D'AFFICHAGE)
    // =========================================================
    public function checkout(){
        // 1. Vérifications de base
        if(!isLoggedIn()){ 
            // flash('login_msg', 'Veuillez vous connecter pour commander');
            redirect('users/login'); 
        }

        $cartData = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if(empty($cartData)){ redirect('shop'); }

        // 2. RÉCUPÉRATION DU USER (C'est ça qui manquait et causait l'erreur)
        $user = $this->userModel->getCustomerById($_SESSION['user_id']);

        // 3. Préparation du panier
        $cartItems = [];
        $total = 0;

        foreach($cartData as $item){
            $obj = (object)$item; 
            $obj->line_total = (float)$obj->price * (int)$obj->qty;
            $total += $obj->line_total;
            $cartItems[] = $obj;
        }

        // 4. Envoi à la vue
        $data = [
            'user' => $user,        // INDISPENSABLE pour pré-remplir le formulaire
            'cart_items' => $cartItems, 
            'total' => $total
        ];

        $this->view('front/cart/checkout', $data);
    }

    // =========================================================
    // 7. TRAITEMENT DE LA COMMANDE (POST)
    // =========================================================
    public function place_order(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            if(!isLoggedIn()){ redirect('users/login'); }

            // 1. Nettoyage des données
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $payment_method = $_POST['payment_method']; 
            
            // Calcul du total
            $cartData = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
            if(empty($cartData)) redirect('shop');

            $cartTotal = 0;
            foreach($cartData as $item){
                $cartTotal += $item['price'] * $item['qty'];
            }
            
            // Récupération du coût de livraison (calculé par JS et mis dans l'input hidden)
            $shippingCost = isset($_POST['shipping_cost']) ? (float)$_POST['shipping_cost'] : 0;
            $finalTotal = $cartTotal + $shippingCost;

            // 2. Préparer les données de la commande
            $orderData = [
                'user_id' => $_SESSION['user_id'],
                // CORRECTION ICI : on utilise 'full_name' comme dans la vue
                'full_name' => trim($_POST['full_name']), 
                'shipping_address' => trim($_POST['address']),
                'shipping_city' => trim($_POST['city']),
                'shipping_region' => trim($_POST['region']),
                'shipping_phone' => trim($_POST['phone']),
                'gps_coordinates' => trim($_POST['gps_coordinates']),
                'payment_method' => $payment_method,
                'total_amount' => $finalTotal, 
                'shipping_cost' => $shippingCost
            ];

            // 3. Créer la commande via le NOUVEAU Modèle (qui accepte un tableau)
            $order_id = $this->orderModel->createOrder($orderData);

            if($order_id){
                // 4. Ajouter les articles
                $this->orderModel->addOrderItems($order_id, $cartData);
                
                // 5. Vider le panier
                unset($_SESSION['cart']);
                unset($_SESSION['cart_count']);

                // 6. Paiement
                if($payment_method == 'paystack'){
                    $this->paystack_initialize($order_id, $orderData['total_amount'], $_SESSION['user_email']);
                } else {
                    redirect('cart/success/' . $order_id);
                }

            } else {
                die('Erreur système : Impossible de créer la commande.');
            }
        } else {
            redirect('cart');
        }
    }

    // =========================================================
    // 8. INITIALISATION PAYSTACK
    // =========================================================
    // =========================================================
    // 8. INITIALISATION PAYSTACK (CORRIGÉ POUR WAMP)
    // =========================================================
    private function paystack_initialize($order_id, $amount, $email){
        $url = "https://api.paystack.co/transaction/initialize";
        
        // On s'assure que le montant est un entier (pas de décimales pour Paystack)
        $amountKobo = ceil($amount * 100);

        $fields = [
            'email' => $email,
            'amount' => $amountKobo,
            'currency' => 'GHS', 
            'reference' => 'ORD-' . $order_id . '-' . time(),
            'callback_url' => URLROOT . '/cart/payment_callback',
            'metadata' => ['order_id' => $order_id]
        ];

        $fields_string = http_build_query($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . PAYSTACK_SECRET_KEY,
            "Cache-Control: no-cache",
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        
        // ----------------------------------------------------
        // 🚑 FIX SPÉCIAL WAMP / LOCALHOST
        // Désactive la vérification SSL qui bloque souvent sous Windows
        // ----------------------------------------------------
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // ----------------------------------------------------
        
        $result = curl_exec($ch);
        $err = curl_error($ch); // On capture l'erreur technique
        curl_close($ch);

        if($err){
            // Si cURL a planté (ex: pas d'internet), on affiche pourquoi
            die("Erreur technique cURL : " . $err);
        }

        $response = json_decode($result, true);

        if(isset($response['status']) && $response['status'] == true){
            header('Location: ' . $response['data']['authorization_url']);
            exit;
        } else {
            // Si Paystack répond une erreur (ex: mauvaise clé API)
            echo "<h1>Erreur Paystack</h1>";
            echo "<pre>";
            print_r($response); // Affiche la réponse brute de Paystack
            echo "</pre>";
            die();
        }
    }

    // =========================================================
    // 9. RETOUR PAYSTACK
    // =========================================================
    // =========================================================
    // 9. RETOUR PAYSTACK (CORRIGÉ POUR WAMP)
    // =========================================================
    public function payment_callback(){
        $reference = isset($_GET['reference']) ? $_GET['reference'] : null;

        if(!$reference){ die('Pas de référence fournie'); }

        $url = 'https://api.paystack.co/transaction/verify/' . $reference;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . PAYSTACK_SECRET_KEY]);
        
        // ----------------------------------------------------
        // 🚑 FIX SPÉCIAL WAMP / LOCALHOST (INDISPENSABLE)
        // ----------------------------------------------------
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // ----------------------------------------------------

        $request = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if($err){
            die("Erreur de connexion Paystack (Verify) : " . $err);
        }

        $result = json_decode($request, true);

        // Debug si le JSON est mal formé
        if(!$result){
            die("Erreur de lecture de la réponse Paystack. Réponse brute : " . $request);
        }

        if(isset($result['status']) && $result['status'] && $result['data']['status'] == 'success'){
            
            // On récupère l'ID commande
            $order_id = $result['data']['metadata']['order_id'];
            
            // Mise à jour : Payé
            if($this->orderModel->updatePaymentStatus($order_id, 'paid', 'processing')){
                // Rediriger vers succès
                redirect('cart/success/' . $order_id);
            } else {
                die("Paiement validé mais erreur lors de la mise à jour de la base de données.");
            }

        } else {
            // Affiche l'erreur exacte renvoyée par Paystack
            echo "<h1>Échec du paiement</h1>";
            echo "<p>Message Paystack : " . ($result['message'] ?? 'Inconnu') . "</p>";
            echo "<pre>";
            print_r($result);
            echo "</pre>";
            die();
        }
    }

    // =========================================================
    // 10. PAGE DE SUCCÈS
    // =========================================================
    // =========================================================
    // 10. PAGE DE SUCCÈS & ENVOI FACTURE
    // =========================================================
    public function success($order_id){
        // 1. Récupérer les infos complètes de la commande
        $order = $this->orderModel->getOrderById($order_id);
        $items = $this->orderModel->getOrderItems($order_id);
        
        if(!$order){ redirect('shop'); }

        // 2. Générer le PDF de la facture (en mémoire, sans l'afficher)
        // On utilise ob_start() pour capturer le HTML de la vue facture
        ob_start();
        require_once APPROOT . '/Views/admin/orders/invoice_template.php'; // On utilisera un template dédié
        $html = ob_get_clean();

        // Initialisation Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true); // Pour charger les images (logo)
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // On récupère le PDF sous forme de chaîne de caractères
        $pdfContent = $dompdf->output();

        // 3. Envoyer l'email avec la facture en pièce jointe
        // On charge la librairie Mail (si pas déjà fait via l'autoloader)
        require_once APPROOT . '/Libraries/Mail.php';
        $mail = new Mail();

        $subject = "Votre commande #{$order->order_number} sur Exotikha";
        $body = "<h1>Merci pour votre commande !</h1>
                 <p>Bonjour {$order->full_name},</p>
                 <p>Votre commande a bien été reçue et est en cours de traitement.</p>
                 <p>Vous trouverez votre facture en pièce jointe.</p>
                 <p>Cordialement,<br>L'équipe Exotikha</p>";

        // Envoi
        $mail->send($order->email, $subject, $body, $pdfContent, "Facture_{$order->order_number}.pdf");

        // 4. Afficher la vue de succès
        $data = ['order' => $order];
        $this->view('front/cart/success', $data);
    }
}