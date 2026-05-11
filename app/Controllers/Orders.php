<?php

if (!defined('APPROOT')) {
    die('Accès interdit');
}

// Chargement de l'autoloader Composer pour Dompdf
// On utilise dirname(APPROOT) pour remonter à la racine du projet de manière sûre
if(file_exists(dirname(APPROOT) . '/vendor/autoload.php')){
    require_once dirname(APPROOT) . '/vendor/autoload.php';
}

use Dompdf\Dompdf;
use Dompdf\Options;

class Orders extends Controller {
    private $orderModel;
    private $userModel;

    public function __construct(){
        // Sécurité globale : Il faut être connecté
        if(!isLoggedIn()){
            redirect('users/login');
        }
        $this->orderModel = $this->model('Order');
        $this->userModel = $this->model('User');
    }

    // =========================================================
    // 1. LISTE DES COMMANDES (Redirection vers Mon Compte)
    // =========================================================
    public function index(){
        // La liste est gérée dans le dashboard utilisateur
        redirect('users/account?tab=orders');
    }

    // =========================================================
    // 2. DÉTAILS D'UNE COMMANDE
    // =========================================================
    public function details($id){
        // 1. Récupérer la commande
        $order = $this->orderModel->getOrderById($id);

        // 2. SÉCURITÉ : Vérifier l'appartenance
        if(!$this->checkOwnership($order)){
            return; // La redirection est gérée dans checkOwnership
        }

        // 3. Récupérer les articles
        $items = $this->orderModel->getOrderItems($id);

        $data = [
            'order' => $order,
            'items' => $items
        ];

        $this->view('front/orders/details', $data);
    }

    // =========================================================
    // 3. TÉLÉCHARGER LA FACTURE (PDF)
    // =========================================================
    public function invoice($id){
        // 1. Récupérer les données
        $order = $this->orderModel->getOrderById($id);
        
        // 2. SÉCURITÉ
        if(!$this->checkOwnership($order)){
            return;
        }

        $items = $this->orderModel->getOrderItems($id);

        // 3. Générer le HTML
        // On capture le HTML de la vue facture
        ob_start();
        require APPROOT . '/Views/admin/orders/invoice_template.php'; 
        $html = ob_get_clean();

        // 4. Configuration Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true); // Pour charger le logo
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // 5. Forcer le téléchargement
        // 'Attachment' => false permet de l'ouvrir dans le navigateur (preview)
        // Mets 'true' pour forcer le téléchargement direct
        $dompdf->stream("Facture_" . $order->order_number . ".pdf", ["Attachment" => false]);
    }

    // =========================================================
    // HELPER PRIVÉ : VÉRIFICATION PROPRIÉTAIRE
    // =========================================================
    private function checkOwnership($order){
        // Si la commande n'existe pas
        if(!$order){
            // TRADUCTION : "Order not found"
            flash('product_message', lang('err_order_not_found'), 'bg-red-50 text-red-600');
            redirect('users/account?tab=orders');
            return false;
        }

        // Si l'ID utilisateur de la commande ne correspond pas à la session
        if($order->user_id != $_SESSION['user_id']){
            // TRADUCTION : "Not authorized"
            flash('product_message', lang('err_not_authorized'), 'bg-red-50 text-red-600');
            redirect('users/account?tab=orders');
            return false;
        }

        return true;
    }
}