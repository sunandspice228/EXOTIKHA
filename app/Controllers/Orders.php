<?php
// Nécessaire pour la génération de facture PDF
require_once '../vendor/autoload.php'; 
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
        // Comme la liste est déjà gérée dans Users::account, 
        // on redirige simplement vers l'onglet commandes
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

        // 3. Générer le HTML (On utilise le même template que pour l'email)
        // On passe les variables $order et $items au template via le scope global ou extraction
        ob_start();
        require_once APPROOT . '/Views/admin/orders/invoice_template.php'; 
        $html = ob_get_clean();

        // 4. Générer le PDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // 5. Forcer le téléchargement (stream)
        // "Attachment" => true force le téléchargement, false l'ouvre dans le navigateur
        $dompdf->stream("Facture_" . $order->order_number . ".pdf", ["Attachment" => false]);
    }

    // =========================================================
    // HELPER PRIVÉ : VÉRIFICATION PROPRIÉTAIRE
    // =========================================================
    private function checkOwnership($order){
        // Si la commande n'existe pas
        if(!$order){
            flash('product_message', 'Cette commande est introuvable.', 'bg-red-50 text-red-600');
            redirect('users/account?tab=orders');
            return false;
        }

        // Si l'ID utilisateur de la commande ne correspond pas à la session
        if($order->user_id != $_SESSION['user_id']){
            flash('product_message', 'Vous n\'êtes pas autorisé à consulter cette commande.', 'bg-red-50 text-red-600');
            redirect('users/account?tab=orders');
            return false;
        }

        return true;
    }
}