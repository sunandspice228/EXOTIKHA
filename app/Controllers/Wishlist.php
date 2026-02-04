<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}

class Wishlist extends Controller {
    private $wishlistModel;

    public function __construct(){
        // Sécurité : Seuls les clients connectés ont accès
        if(!isLoggedIn()){ 
            redirect('users/login'); 
        }
        
        $this->wishlistModel = $this->model('Wishlist');
    }

    // =========================================================
    // ACTION : TOGGLE (Ajouter / Supprimer)
    // =========================================================
    public function toggle($product_id){
        $user_id = $_SESSION['user_id']; 

        // Vérification : Est-ce que le produit est déjà dans la liste ?
        if($this->wishlistModel->check($user_id, $product_id)){
            // OUI -> On le supprime
            if($this->wishlistModel->remove($user_id, $product_id)){
                // Message traduit : "Produit retiré des favoris"
                flash('wishlist_msg', lang('msg_wishlist_remove'), 'bg-slate-100 text-slate-600');
            }
        } else {
            // NON -> On l'ajoute
            if($this->wishlistModel->add($user_id, $product_id)){
                // Message traduit : "Produit ajouté aux favoris"
                flash('wishlist_msg', lang('msg_wishlist_add'));
            }
        }

        // REDIRECTION
        if(isset($_SERVER['HTTP_REFERER'])){
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            redirect('shop');
        }
    }

    // =========================================================
    // ACTION : SUPPRIMER (Bouton Poubelle)
    // =========================================================
    public function remove($product_id){
        $user_id = $_SESSION['user_id'];
        
        $this->wishlistModel->remove($user_id, $product_id);
        
        // Message traduit
        flash('wishlist_msg', lang('msg_wishlist_remove'), 'bg-slate-100 text-slate-600');

        if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'account') !== false){
            redirect('users/account?tab=wishlist');
        } else {
            redirect('shop');
        }
    }
}