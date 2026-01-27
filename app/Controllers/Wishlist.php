<?php
class Wishlist extends Controller {
    private $wishlistModel;

    public function __construct(){
        // Sécurité : Seuls les utilisateurs connectés peuvent gérer leur wishlist
        if(!isLoggedIn()){ 
            redirect('users/login'); 
        }
        
        $this->wishlistModel = $this->model('WishlistModel');
    }

    // Action: Toggle (Ajouter ou Supprimer)
    public function toggle($product_id){
        $user_id = $_SESSION['user_id'];

        // On vérifie d'abord si le produit existe (sécurité supplémentaire optionnelle)
        // $this->model('Product')->getProductById($product_id);

        if($this->wishlistModel->check($user_id, $product_id)){
            // Si existe déjà -> On supprime
            $this->wishlistModel->remove($user_id, $product_id);
        } else {
            // Sinon -> On ajoute
            $this->wishlistModel->add($user_id, $product_id);
        }

        // GESTION DE LA REDIRECTION
        // Si la requête vient d'une page précédente (ex: page shop ou page détails)
        if(isset($_SERVER['HTTP_REFERER'])){
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            // Sinon retour à la boutique par défaut
            redirect('shop');
        }
    }
}