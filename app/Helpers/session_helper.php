<?php
// Démarrage de session sécurisé (évite les erreurs si déjà démarrée)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction Flash Message
// Utilisation : flash('register_success', 'Vous êtes inscrit');
// Affichage : echo flash('register_success');
function flash($name = '', $message = '', $class = 'bg-green-100 text-green-700 border border-green-200 rounded p-4 mb-4'){
    if(!empty($name)){
        if(!empty($message) && empty($_SESSION[$name])){
            if(!empty($_SESSION[$name. '_class'])){
                unset($_SESSION[$name. '_class']);
            }
            $_SESSION[$name] = $message;
            $_SESSION[$name. '_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
            // J'ai ajouté un ID pour pouvoir le cibler en JS si besoin (ex: disparition auto)
            echo '<div class="'.$class.'" id="msg-flash">'.$_SESSION[$name].'</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name. '_class']);
        }
    }
}

// Vérifier si l'utilisateur est connecté
function isLoggedIn(){
    if(isset($_SESSION['user_id'])){
        return true;
    } else {
        return false;
    }
}

// Vérifier si Admin (Très utile pour protéger l'accès au Back-office)
function isAdmin(){
    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'){
        return true;
    }
    return false;
}