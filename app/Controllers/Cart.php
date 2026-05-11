<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}

// Load helper and composer autoload for Dompdf
require_once '../app/Helpers/mail_helper.php';
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
        
        // Initialize cart in session if it doesn't exist
        if(!isset($_SESSION['cart'])){
            $_SESSION['cart'] = [];
        }
    }

    // =========================================================
    // 1. AFFICHER LE PANIER
    // =========================================================
    public function index(){
        $cartItems = [];
        $subtotal = 0;

        // Fetch fresh product data based on session cart
        if(!empty($_SESSION['cart'])){
            foreach($_SESSION['cart'] as $key => $item){
                $product = $this->productModel->getProductById($item['product_id']);
                
                if($product){
                    $price = $product->promo_price > 0 ? $product->promo_price : $product->price;
                    $variantName = '';
                    $maxStock = $product->stock;

                    // Handle variants if applicable
                    if(!empty($item['variant_id'])){
                        if(method_exists($this->productModel, 'getVariantById')){
                            $variant = $this->productModel->getVariantById($item['variant_id']);
                            if($variant){
                                $variantName = $variant->size . ($variant->color ? ' - ' . $variant->color : '');
                                $maxStock = $variant->stock;
                            }
                        }
                    }

                    $totalItem = $price * $item['qty'];
                    $subtotal += $totalItem;

                    // Store as object for view consistency
                    $cartObj = new stdClass();
                    $cartObj->key = $key;
                    $cartObj->id = $product->id;
                    $cartObj->name = (isset($_SESSION['lang']) && $_SESSION['lang']=='fr' && !empty($product->name_fr)) ? $product->name_fr : $product->name;
                    $cartObj->slug = $product->slug;
                    $cartObj->image = $product->image;
                    $cartObj->price = $price;
                    $cartObj->qty = $item['qty'];
                    $cartObj->variant = $variantName;
                    $cartObj->variant_id = $item['variant_id'];
                    $cartObj->line_total = $totalItem; // Standardized name
                    $cartObj->max_stock = $maxStock;

                    $cartItems[] = $cartObj;
                }
            }
        }

        $data = [
            'cart_items' => $cartItems,
            'subtotal' => $subtotal
        ];

        $this->view('front/cart/index', $data);
    }

    // =========================================================
    // 2. AJOUTER AU PANIER
    // =========================================================
    public function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // verifyCsrfToken(); 

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
            $variantId = isset($_POST['variant_id']) && !empty($_POST['variant_id']) ? (int)$_POST['variant_id'] : null;
            $quantity  = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

            if($quantity <= 0) $quantity = 1;

            $product = $this->productModel->getProductById($productId);
            if(!$product){
                flash('cart_msg', 'Produit introuvable.', 'alert-danger');
                redirect('shop');
                return;
            }

            // Check stock availability
            $availableStock = $product->stock;
            if($variantId && method_exists($this->productModel, 'getVariantById')){
                $variant = $this->productModel->getVariantById($variantId);
                if($variant) $availableStock = $variant->stock;
            }

            if($availableStock < $quantity){
                flash('product_message', 'Stock insuffisant.', 'alert-danger');
                redirect('shop/details/' . $product->slug); 
                return;
            }

            // Generate unique cart key
            $cartKey = $productId;
            if($variantId){
                $cartKey .= '_' . $variantId;
            }

            // Add or update session cart
            if(isset($_SESSION['cart'][$cartKey])){
                $newQty = $_SESSION['cart'][$cartKey]['qty'] + $quantity;
                $_SESSION['cart'][$cartKey]['qty'] = ($newQty <= $availableStock) ? $newQty : $availableStock;
            } else {
                $_SESSION['cart'][$cartKey] = [
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'qty' => $quantity,
                    'stock' => $availableStock // Cache max stock for update checks
                ];
            }

            // Update global counter
            $totalQty = 0;
            foreach($_SESSION['cart'] as $item){ $totalQty += $item['qty']; }
            $_SESSION['cart_count'] = $totalQty;

            flash('cart_msg', 'Produit ajouté au panier !');
            
            if(isset($_SERVER['HTTP_REFERER'])){
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                redirect('cart');
            }
        } else {
            redirect('shop');
        }
    }

    // =========================================================
    // 3. METTRE À JOUR (UPDATE)
    // =========================================================
    public function update(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $key = $_POST['cart_key']; // Changed to match view (usually cart_key or product_id depending on impl)
            // If using product_id from previous impl, map it logic here. Assuming cart_key is passed.
            // If the view sends product_id, we need to iterate to find it. 
            // Better to send the key generated in index.
            
            // Fallback if view sends product_id instead of key
            if(!isset($_POST['cart_key']) && isset($_POST['product_id'])) {
                 // Logic to find key based on product_id would go here, 
                 // but let's assume the view sends the cart key for simplicity.
                 // If your view sends product_id, adapt this loop.
                 $productId = $_POST['product_id'];
                 foreach($_SESSION['cart'] as $k => $val) {
                     if($val['product_id'] == $productId) { $key = $k; break; }
                 }
            }

            $qty = (int)$_POST['quantity']; // changed from 'qty' to match generic post

            if(isset($_SESSION['cart'][$key])){
                if($qty <= 0){
                    unset($_SESSION['cart'][$key]);
                } else {
                    $max = isset($_SESSION['cart'][$key]['stock']) ? $_SESSION['cart'][$key]['stock'] : 100;
                    $_SESSION['cart'][$key]['qty'] = ($qty > $max) ? $max : $qty;
                }
                
                // Recalculate count
                $totalQty = 0;
                foreach($_SESSION['cart'] as $item){ $totalQty += $item['qty']; }
                $_SESSION['cart_count'] = $totalQty;
                
                flash('cart_msg', 'Panier mis à jour.');
            }
            redirect('cart');
        }
    }

    // =========================================================
    // 4. SUPPRIMER (REMOVE)
    // =========================================================
    public function remove($key){
        // Decode if needed, or handle raw key
        if(isset($_SESSION['cart'][$key])){
            unset($_SESSION['cart'][$key]);
            
            $totalQty = 0;
            foreach($_SESSION['cart'] as $item){ $totalQty += $item['qty']; }
            $_SESSION['cart_count'] = $totalQty;
            
            flash('cart_msg', 'Produit retiré du panier.');
        } else {
            // Try finding by ID if key didn't match (fallback)
            foreach($_SESSION['cart'] as $k => $item){
                if($item['product_id'] == $key){
                    unset($_SESSION['cart'][$k]);
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
        flash('cart_msg', 'Votre panier est vide.');
        redirect('shop');
    }

    // =========================================================
    // 6. CHECKOUT
    // =========================================================
    public function checkout(){
        if(!isLoggedIn()){ 
            redirect('users/login'); 
        }

        if(empty($_SESSION['cart'])){ redirect('shop'); }

        // Get user data for pre-filling
        $user = $this->userModel->getUserById($_SESSION['user_id']); // Updated to getUserById

        // Prepare items for display
        $cartItems = [];
        $total = 0;

        foreach($_SESSION['cart'] as $key => $item){
            $product = $this->productModel->getProductById($item['product_id']);
            if($product){
                $price = $product->promo_price > 0 ? $product->promo_price : $product->price;
                $lineTotal = $price * $item['qty'];
                $total += $lineTotal;
                
                // Variant info
                $variantName = '';
                if(!empty($item['variant_id']) && method_exists($this->productModel, 'getVariantById')){
                     $variant = $this->productModel->getVariantById($item['variant_id']);
                     if($variant) $variantName = $variant->size . ' ' . $variant->color;
                }

                $obj = new stdClass();
                $obj->name = $product->name; // or translated
                $obj->price = $price;
                $obj->qty = $item['qty'];
                $obj->variant = $variantName;
                $obj->line_total = $lineTotal;
                $cartItems[] = $obj;
            }
        }

        $data = [
            'user' => $user,
            'cart_items' => $cartItems, 
            'total' => $total
        ];

        $this->view('front/cart/checkout', $data);
    }

    // =========================================================
    // 7. PLACE ORDER
    // =========================================================
    public function place_order(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if(!isLoggedIn()){ redirect('users/login'); }

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $payment_method = $_POST['payment_method']; 
            
            // Calculate totals
            $cartTotal = 0;
            $orderItems = []; // Prepared for insertion
            
            foreach($_SESSION['cart'] as $item){
                $product = $this->productModel->getProductById($item['product_id']);
                if($product){
                    $price = $product->promo_price > 0 ? $product->promo_price : $product->price;
                    $cartTotal += $price * $item['qty'];
                    
                    // Add price to item array for storage
                    $item['price'] = $price; // Store snapshot of price
                    $item['name'] = $product->name;
                    $orderItems[] = $item;
                }
            }
            
            $shippingCost = isset($_POST['shipping_cost']) ? (float)$_POST['shipping_cost'] : 0;
            $finalTotal = $cartTotal + $shippingCost;

            $orderData = [
                'user_id' => $_SESSION['user_id'],
                'full_name' => trim($_POST['full_name']), 
                'shipping_address' => trim($_POST['address']),
                'shipping_city' => trim($_POST['city']),
                'shipping_region' => trim($_POST['region']),
                'shipping_phone' => trim($_POST['phone']),
                'gps_coordinates' => isset($_POST['gps_coordinates']) ? trim($_POST['gps_coordinates']) : '',
                'payment_method' => $payment_method,
                'total_amount' => $finalTotal, 
                'shipping_cost' => $shippingCost
            ];

            // Create Order
            $order_id = $this->orderModel->createOrder($orderData);

            if($order_id){
                // Add Items
                $this->orderModel->addOrderItems($order_id, $orderItems);
                
                // Clear Cart
                unset($_SESSION['cart']);
                unset($_SESSION['cart_count']);

                // Payment Handling
                if($payment_method == 'paystack'){
                    $this->paystack_initialize($order_id, $finalTotal, $_SESSION['user_email']);
                } else {
                    redirect('cart/success/' . $order_id);
                }

            } else {
                die('System error: Unable to create order.');
            }
        } else {
            redirect('cart');
        }
    }

    // =========================================================
    // 8. PAYSTACK INIT
    // =========================================================
    private function paystack_initialize($order_id, $amount, $email){
        $url = "https://api.paystack.co/transaction/initialize";
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
        
        // WAMP Fix
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if($err){ die("cURL Error: " . $err); }

        $response = json_decode($result, true);

        if(isset($response['status']) && $response['status'] == true){
            header('Location: ' . $response['data']['authorization_url']);
            exit;
        } else {
            echo "<h1>Paystack Error</h1><pre>";
            print_r($response);
            echo "</pre>";
            die();
        }
    }

    // =========================================================
    // 9. PAYSTACK CALLBACK
    // =========================================================
    public function payment_callback(){
        $reference = isset($_GET['reference']) ? $_GET['reference'] : null;
        if(!$reference){ die('No reference provided'); }

        $url = 'https://api.paystack.co/transaction/verify/' . $reference;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . PAYSTACK_SECRET_KEY]);
        
        // WAMP Fix
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $request = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if($err){ die("Paystack Verify Error: " . $err); }

        $result = json_decode($request, true);

        if(isset($result['status']) && $result['status'] && $result['data']['status'] == 'success'){
            $order_id = $result['data']['metadata']['order_id'];
            
            // Update to Paid
            if($this->orderModel->updatePaymentStatus($order_id, 'paid')){
                // Also likely want to set status to processing if it was pending_payment
                $this->orderModel->updateStatus($order_id, 'processing');
                redirect('cart/success/' . $order_id);
            } else {
                die("Payment verified but DB update failed.");
            }
        } else {
            echo "<h1>Payment Failed</h1><pre>";
            print_r($result);
            echo "</pre>";
            die();
        }
    }

    // =========================================================
    // 10. SUCCESS & INVOICE
    // =========================================================
    public function success($order_id){
        $order = $this->orderModel->getOrderById($order_id);
        
        if(!$order){ redirect('shop'); }

        // Generate PDF
        ob_start();
        // Ensure you have this template view created or point to a generic invoice view
        // $items = $this->orderModel->getOrderItems($order_id); // Needed for template
        // require_once APPROOT . '/Views/admin/orders/invoice_template.php'; 
        // For now, simple placeholder HTML if template missing
        echo "<h1>Invoice #{$order_id}</h1>";
        $html = ob_get_clean();

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfContent = $dompdf->output();

        // Send Email
        if(class_exists('Mail')){
            $mail = new Mail();
            $subject = "Order #{$order_id} Confirmed - Exotikha";
            $body = "<h1>Thank you for your order!</h1><p>Your invoice is attached.</p>";
            // Ensure send() supports attachments or modify accordingly
            // $mail->send($order->email, $subject, $body, $pdfContent, "Invoice_{$order_id}.pdf");
        }

        $data = ['order' => $order];
        $this->view('front/cart/success', $data);
    }
}