<?php
// Démarrage de session s'il n'est pas déjà actif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction Flash Message
// Utilisation : flash('register_success', 'Vous êtes inscrit');
// Affichage : echo flash('register_success');
function flash($name = '', $message = '', $class = 'alert alert-success'){
    if(!empty($name)){
        if(!empty($message) && empty($_SESSION[$name])){
            if(!empty($_SESSION[$name. '_class'])){
                unset($_SESSION[$name. '_class']);
            }
            $_SESSION[$name] = $message;
            $_SESSION[$name. '_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
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

// Vérifier si Admin
function isAdmin(){
    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'){
        return true;
    }
    return false;
}