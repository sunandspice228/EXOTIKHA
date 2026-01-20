<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Order;

class AdminOrderController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['admin_id'])) $this->redirect('/admin/login');
    }

    public function index() {
        $model = new Order();
        $this->view('admin/orders/index', ['orders' => $model->getAll()]);
    }

    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Order();
            $model->updateStatus($id, $_POST['status']);
            flash('success', 'Statut mis à jour.');
            $this->redirect('/admin/orders');
        }
    }
}