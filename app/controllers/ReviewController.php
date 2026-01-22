<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Review;

class ReviewController extends Controller {

    public function store() {
        if (!isset($_SESSION['user_id'])) {
            flash('error', 'Vous devez être connecté.');
            $this->redirectBack();
        }

        $userId = $_SESSION['user_id'];
        $productId = $_POST['product_id'];
        $rating = (int) $_POST['rating'];
        $comment = trim(htmlspecialchars($_POST['comment']));

        $reviewModel = new Review();

        // 1. Vérifier si l'utilisateur a acheté le produit
        if (!$reviewModel->hasBoughtProduct($userId, $productId)) {
            flash('error', 'Seuls les clients ayant acheté ce produit peuvent donner leur avis.');
            $this->redirectBack();
        }

        // 2. Vérifier s'il a déjà noté
        if ($reviewModel->hasAlreadyReviewed($userId, $productId)) {
            flash('error', 'Vous avez déjà noté ce produit.');
            $this->redirectBack();
        }

        // 3. Sauvegarder
        if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
            $reviewModel->create($userId, $productId, $rating, $comment);
            flash('success', 'Merci ! Votre avis a été publié.');
        } else {
            flash('error', 'Veuillez remplir tous les champs.');
        }

        $this->redirectBack();
    }

    private function redirectBack() {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}