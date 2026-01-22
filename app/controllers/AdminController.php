<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Type;
use App\Models\Content; // Pour voir les leads

class AdminController extends Controller {

    public function __construct() {
        // SÉCURITÉ ADMIN
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/login');
        }
    }

 // ============================================================
    // 1. DASHBOARD (VUE D'ENSEMBLE)
    // ============================================================
    public function dashboard() {
        $productModel = new Product();
        $orderModel = class_exists('App\Models\Order') ? new Order() : null;
        $contentModel = new Content(); // Pour les ventes privées

        // 1. Statistiques Produits
        $products = $productModel->getAll();
        $countProducts = count($products);

        // 2. Statistiques Commandes & Chiffre d'Affaires
        $countOrders = 0;
        $recentOrders = [];
        $totalRevenue = 0;

        if ($orderModel && method_exists($orderModel, 'getAll')) {
            $allOrders = $orderModel->getAll();
            $countOrders = count($allOrders);
            $recentOrders = array_slice($allOrders, 0, 5); // Les 5 dernières

            // Calcul simple du CA (Si tu as une colonne 'total' dans orders)
            // Sinon on met 0 pour l'instant
            foreach($allOrders as $o) {
                if(isset($o['total_price'])) {
                    $totalRevenue += $o['total_price'];
                }
            }
        }

        // 3. Leads (Ventes Privées)
        $leads = $contentModel->getPrivateSaleLeads();
        $recentLeads = array_slice($leads, 0, 5); // Les 5 derniers inscrits

        // 4. Envoi à la vue
        $this->view('admin/dashboard', [
            'countProducts' => $countProducts,
            'countOrders'   => $countOrders,
            'countLeads'    => count($leads),
            'totalRevenue'  => $totalRevenue,
            'recentOrders'  => $recentOrders,
            'recentLeads'   => $recentLeads
        ]);
    }

    // ============================================================
    // 2. GESTION PRODUITS (CRUD + BULK)
    // ============================================================
    
    // LISTE
    public function products() {
        $search = $_GET['q'] ?? '';
        $model = new Product();
        $products = $model->getAll($search);
        $this->view('admin/products/index', ['products' => $products, 'search' => $search]);
    }

    // FORMULAIRE AJOUT
    public function createProduct() {
        $attrModel = new Attribute();
        $catModel = new Category();
        $typeModel = new Type();

        $this->view('admin/products/create', [
            'attributes' => $attrModel->getAll(),
            'categories' => $catModel->getAll(),
            'types'      => $typeModel->getAll()
        ]);
    }

    // SAUVEGARDE (CREATE & UPDATE unifié pour éviter doublons de code)
    private function saveProductLogic($mode) {
        verify_csrf();
        $model = new Product();
        
        // Données communes
        $price = (float) $_POST['price'];
        $promoPrice = !empty($_POST['promo_price']) ? (float) $_POST['promo_price'] : NULL;
        $isPromo = isset($_POST['is_promo']) ? 1 : 0;
        $start = !empty($_POST['promo_start_date']) ? $_POST['promo_start_date'] : NULL;
        $end = !empty($_POST['promo_end_date']) ? $_POST['promo_end_date'] : NULL;

        // Validation Promo
        if ($isPromo && $promoPrice >= $price) {
            flash('error', 'Le prix promo doit être inférieur au prix normal.');
            $this->redirect($_SERVER['HTTP_REFERER']); return;
        }

        // Image Logic
        $imageName = 'default.jpg';
        if ($mode === 'update') {
            $current = $model->findById($_POST['product_id']);
            $imageName = $current['image'];
        }
        
        if (!empty($_FILES['image']['name'])) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], ROOT_PATH . '/public/uploads/' . $imageName);
        }

        // Attributs
        $sizes = isset($_POST['sizes']) ? implode(',', $_POST['sizes']) : '';
        $colors = isset($_POST['colors']) ? implode(',', $_POST['colors']) : '';

        $data = [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'price' => $price,
            'promo_price' => $isPromo ? $promoPrice : NULL,
            'promo_start_date' => $isPromo ? $start : NULL,
            'promo_end_date' => $isPromo ? $end : NULL,
            'stock' => $_POST['stock'],
            'category' => $_POST['category'],
            'type' => $_POST['type'] ?? NULL,
            'is_promo' => $isPromo,
            'image' => $imageName,
            'sizes' => $sizes,
            'colors' => $colors
        ];

        if ($mode === 'create') {
            $model->create($data);
            flash('success', 'Produit créé.');
            $this->redirect('/admin/products');
        } else {
            $data['id'] = $_POST['product_id'];
            $model->update($data);
            
            // Galerie (Update seulement)
            if (!empty($_FILES['gallery']['name'][0])) {
                $total = count($_FILES['gallery']['name']);
                for ($i = 0; $i < $total; $i++) {
                    if ($_FILES['gallery']['error'][$i] == 0) {
                        $ext = pathinfo($_FILES['gallery']['name'][$i], PATHINFO_EXTENSION);
                        $gName = uniqid() . '_gal.' . $ext;
                        move_uploaded_file($_FILES['gallery']['tmp_name'][$i], ROOT_PATH . '/public/uploads/' . $gName);
                        $model->addGalleryImage($data['id'], $gName);
                    }
                }
            }
            flash('success', 'Produit mis à jour.');
            $this->redirect('/admin/products/edit/' . $data['id']);
        }
    }

    public function storeProduct() { $this->saveProductLogic('create'); }
    public function updateProduct() { $this->saveProductLogic('update'); }

    public function editProduct($id) {
        $prodModel = new Product();
        $product = $prodModel->findById($id);
        if (!$product) { $this->redirect('/admin/products'); }

        $gallery = $prodModel->getGallery($id);
        $attrModel = new Attribute();
        $catModel = new Category();
        $typeModel = new Type();

        $this->view('admin/products/edit', [
            'product' => $product,
            'gallery' => $gallery,
            'attributes' => $attrModel->getAll(),
            'categories' => $catModel->getAll(),
            'types' => $typeModel->getAll()
        ]);
    }

    public function deleteProduct($id) {
        $model = new Product();
        $model->delete($id);
        flash('success', 'Produit supprimé.');
        $this->redirect('/admin/products');
    }

    public function deleteGalleryImage($imgId) {
        $model = new Product();
        $model->deleteGalleryImage($imgId);
        header('Location: ' . $_SERVER['HTTP_REFERER']); exit;
    }

    // ACTIONS DE MASSE (Bulk)
    public function bulkAction() {
        verify_csrf();
        $ids = $_POST['selected_products'] ?? [];
        $action = $_POST['bulk_action'];

        if (empty($ids)) {
            flash('error', 'Aucun produit sélectionné.');
            $this->redirect('/admin/products');
        }

        $model = new Product();
        if ($action === 'delete') {
            $model->deleteBulk($ids);
            flash('success', count($ids) . ' produits supprimés.');
        } elseif ($action === 'promo') {
            $start = $_POST['bulk_start'];
            $end = $_POST['bulk_end'];
            $model->updatePromoBulk($ids, $start, $end);
            flash('success', 'Promo activée sur ' . count($ids) . ' produits.');
        }
        $this->redirect('/admin/products');
    }

    // ============================================================
    // 3. VENTES PRIVÉES (LEADS)
    // ============================================================
    public function privateSales() {
        $contentModel = new Content();
        $leads = $contentModel->getPrivateSaleLeads();
        
        // Crée une vue simple 'admin/leads/index.php' pour afficher ce tableau
        $this->view('admin/leads/index', ['leads' => $leads]);
    }

    // ============================================================
    // 4. COMMANDES & CLIENTS
    // ============================================================
    public function orders() {
        $m = new Order(); 
        $this->view('admin/orders/index', ['orders' => method_exists($m,'getAll')?$m->getAll():[]]);
    }
    
    public function orderShow($id) {
        $m = new Order(); 
        $order = $m->getById($id);
        if(!$order) $this->redirect('/admin/orders');
        $this->view('admin/orders/show', ['order'=>$order, 'items'=>$m->getItems($id)]);
    }

    public function updateOrderStatus() {
        (new Order())->updateStatus($_POST['order_id'], $_POST['status']);
        flash('success', 'Statut mis à jour.');
        $this->redirect('/admin/orders/show/' . $_POST['order_id']);
    }

    public function customers() {
        $m = new User();
        $this->view('admin/users/index', ['customers' => method_exists($m,'getAllClients')?$m->getAllClients():[]]);
    }
}