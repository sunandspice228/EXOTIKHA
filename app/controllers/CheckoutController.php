<?php
namespace App\Controllers;

use Core\Controller;
use Core\Database; // <-- Indispensable pour utiliser new Database()
use App\Models\Product;

class CheckoutController extends Controller {

    public function __construct() {
        // On vérifie que la session est démarrée
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Vérification connexion utilisateur
        if (!isset($_SESSION['user_id'])) {
            flash('error', 'Veuillez vous connecter pour procéder au paiement.');
            $this->redirect('/login');
        }
    }

    public function index() {
        // Si le panier est vide, on renvoie au panier
        if (empty(get_cart())) {
            $this->redirect('/cart');
        }
        // Affiche une vue simple (optionnel, ou redirige vers process)
        $this->process(); 
    }

    // 1. INITIALISATION DU PAIEMENT PAYSTACK
    public function process() {
        // Protection CSRF (assure-toi que la fonction existe dans helpers)
        if (function_exists('verify_csrf')) {
            verify_csrf();
        }
        
        $prodModel = new Product();
        $cart = get_cart();
        $totalGHS = 0;

        // Calcul du total
        foreach ($cart as $id => $qty) {
            $product = $prodModel->findById($id);
            if ($product) {
                // Utilisation du helper is_on_promotion
                $price = (function_exists('is_on_promotion') && is_on_promotion($product)) 
                         ? $product['promo_price'] 
                         : $product['price'];
                
                $totalGHS += $price * $qty;
            }
        }

        // Sécurité montant minimum
        if ($totalGHS <= 0) {
            flash('error', 'Panier invalide.');
            $this->redirect('/cart');
        }

        // Conversion en Pesewas (Centimes) pour Paystack
        // round() évite les problèmes de virgule flottante
        $amountInKobo = (int) round($totalGHS * 100); 
        
        $email = $_SESSION['user_email'] ?? 'client@exotikha.com';

        // Configuration Paystack
        $url = "https://api.paystack.co/transaction/initialize";
        $fields = [
            'email' => $email,
            'amount' => $amountInKobo,
            'currency' => 'GHS',
            'callback_url' => url('/checkout/callback'),
            'metadata' => [
                'user_id' => $_SESSION['user_id'],
                'cart_count' => count($cart)
            ]
        ];

        $fields_string = http_build_query($fields);
        
        // Initialisation cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer " . PAYSTACK_SECRET_KEY,
            "Cache-Control: no-cache"
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        
        // Exécution
        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        // Gestion des erreurs cURL (Connexion internet, etc)
        if ($error) {
            flash('error', 'Erreur de connexion Paystack : ' . $error);
            $this->redirect('/cart');
        }

        $response = json_decode($result, true);

        // Vérification de la réponse API
        if (isset($response['status']) && $response['status'] === true) {
            // Redirection vers la page de paiement sécurisée
            header("Location: " . $response['data']['authorization_url']);
            exit;
        } else {
            $msg = $response['message'] ?? 'Erreur inconnue lors de l\'initialisation.';
            flash('error', 'Paystack : ' . $msg);
            $this->redirect('/cart');
        }
    }

    // 2. RETOUR APRÈS PAIEMENT (CALLBACK)
    public function callback() {
        $reference = $_GET['reference'] ?? null;

        if (!$reference) {
            flash('error', 'Aucune référence de paiement trouvée.');
            $this->redirect('/cart');
        }

        // --- VÉRIFICATION CÔTÉ SERVEUR ---
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . PAYSTACK_SECRET_KEY,
                "Cache-Control: no-cache"
            ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            flash('error', 'Impossible de vérifier le paiement : ' . $err);
            $this->redirect('/cart');
        }

        $result = json_decode($response, true);

        // SI LE PAIEMENT EST VALIDÉ PAR PAYSTACK
        if ($result['status'] && $result['data']['status'] === 'success') {
            
            // Instanciation directe de la BDD pour gérer la transaction
            $db = new Database(); 
            $prodModel = new Product();
            $cart = get_cart();

            // Vérification anti-doublon (si l'utilisateur rafraîchit la page)
            $existingOrder = $db->query("SELECT id FROM orders WHERE reference = :ref", ['ref' => $reference])->fetch();
            if ($existingOrder) {
                // Commande déjà enregistrée, on redirige vers l'historique
                $this->redirect('/account?tab=orders');
            }

            try {
                // --- DÉBUT TRANSACTION ---
                // Attention : Assurez-vous que votre classe Database expose $pdo ou gérez via query brute
                // Si $db->pdo est privé, utilisez $db->query("START TRANSACTION");
                if (isset($db->pdo)) {
                    $db->pdo->beginTransaction();
                } else {
                    // Fallback si pdo n'est pas accessible directement
                    // Cela suppose que votre méthode query supporte les commandes SQL directes
                    // Note: C'est moins sûr sans try/catch transactionnel réel, mais ça marche souvent
                }

                // 1. Enregistrer la commande
                $amountPaid = $result['data']['amount'] / 100; // Retour en GHS
                
                $sqlOrder = "INSERT INTO orders (user_id, reference, amount, currency, status, created_at) 
                             VALUES (:uid, :ref, :amt, 'GHS', 'paid', NOW())";
                
                $db->query($sqlOrder, [
                    'uid' => $_SESSION['user_id'],
                    'ref' => $reference,
                    'amt' => $amountPaid
                ]);
                
                // Récupérer l'ID de la commande créée
                // Si votre classe Database n'a pas lastInsertId(), on utilise PDO direct
                $orderId = isset($db->pdo) ? $db->pdo->lastInsertId() : $db->query("SELECT LAST_INSERT_ID()")->fetchColumn();

                if (!$orderId) throw new \Exception("Impossible de récupérer l'ID commande.");

                // 2. Enregistrer les produits et mettre à jour le stock
                foreach ($cart as $prodId => $qty) {
                    $product = $prodModel->findById($prodId);
                    
                    if ($product) {
                        $price = (function_exists('is_on_promotion') && is_on_promotion($product)) 
                                 ? $product['promo_price'] 
                                 : $product['price'];
                        
                        // Ajout ligne commande
                        $db->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:oid, :pid, :qty, :price)", [
                            'oid' => $orderId,
                            'pid' => $prodId,
                            'qty' => $qty,
                            'price' => $price
                        ]);

                        // Mise à jour stock
                        $newStock = max(0, $product['stock'] - $qty); // Empêche stock négatif
                        $db->query("UPDATE products SET stock = :stock WHERE id = :id", [
                            'stock' => $newStock,
                            'id' => $prodId
                        ]);
                    }
                }

                // --- VALIDATION TRANSACTION ---
                if (isset($db->pdo)) {
                    $db->pdo->commit();
                }

                // 3. Vider le panier
                unset($_SESSION['cart']);

                // 4. Afficher la page de succès
                $this->view('checkout/success', ['reference' => $reference]);

            } catch (\Exception $e) {
                // --- ANNULATION TRANSACTION ---
                if (isset($db->pdo)) {
                    $db->pdo->rollBack();
                }
                
                // Log l'erreur pour le développeur (optionnel)
                // error_log($e->getMessage());

                flash('error', 'Erreur système lors de l\'enregistrement : ' . $e->getMessage());
                $this->redirect('/cart');
            }

        } else {
            // Echec Paystack
            flash('error', 'Le paiement a échoué ou a été annulé.');
            $this->redirect('/cart');
        }
    }
}