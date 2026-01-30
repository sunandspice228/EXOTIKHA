<?php
class Wishlist extends Controller {
    private $wishlistModel;

    public function __construct(){
        // Sécurité : Seuls les clients connectés ont accès
        if(!isLoggedIn()){ 
            // On pourrait rediriger vers le login en sauvegardant l'URL, 
            // mais pour l'instant une redirection simple suffit.
            redirect('users/login'); 
        }
        
        $this->wishlistModel = $this->model('Wishlist');
    }

    // =========================================================
    // ACTION : TOGGLE (Ajouter / Supprimer)
    // =========================================================
    public function toggle($product_id){
        // On récupère l'ID du client connecté
        $user_id = $_SESSION['user_id']; 

        // Vérification : Est-ce que le produit est déjà dans la liste ?
        if($this->wishlistModel->check($user_id, $product_id)){
            // OUI -> On le supprime
            if($this->wishlistModel->remove($user_id, $product_id)){
                // Message de succès (Gris ou Rouge pour indiquer le retrait)
                flash('wishlist_msg', 'Produit retiré de vos favoris.', 'bg-slate-100 text-slate-600');
            }
        } else {
            // NON -> On l'ajoute
            if($this->wishlistModel->add($user_id, $product_id)){
                // Message de succès (Vert pour indiquer l'ajout)
                flash('wishlist_msg', 'Produit ajouté à vos favoris !');
            }
        }

        // GESTION INTELLIGENTE DE LA REDIRECTION
        // Si la requête vient d'une page précédente (Shop, Détail produit, etc.)
        if(isset($_SERVER['HTTP_REFERER'])){
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            // Sinon retour à la boutique par défaut
            redirect('shop');
        }
    }

    // =========================================================
    // ACTION : SUPPRIMER (Spécifique pour la page "Mon Compte")
    // =========================================================
    // Parfois, on veut juste un bouton "Poubelle" qui ne fait que supprimer
    public function remove($product_id){
        $user_id = $_SESSION['user_id'];
        
        $this->wishlistModel->remove($user_id, $product_id);
        flash('wishlist_msg', 'Produit retiré.', 'bg-slate-100 text-slate-600');

        // Si on est sur la page mon compte, on recharge l'onglet wishlist
        if(isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'account') !== false){
            redirect('users/account?tab=wishlist');
        } else {
            redirect('shop');
        }
    }
}