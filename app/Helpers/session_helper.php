<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function flash($name = '', $message = '', $class = 'alert-success'){
    if(!empty($name)){
        if(!empty($message) && empty($_SESSION[$name])){
            if(!empty($_SESSION[$name. '_class'])){
                unset($_SESSION[$name. '_class']);
            }
            $_SESSION[$name] = $message;
            $_SESSION[$name. '_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])){
            $class = !empty($_SESSION[$name. '_class']) ? $_SESSION[$name. '_class'] : '';
            
            $tailwindClass = ($class == 'alert-danger') 
                ? 'bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-4 text-sm font-bold flex items-center gap-2 shadow-sm' 
                : 'bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl mb-4 text-sm font-bold flex items-center gap-2 shadow-sm';
            
            $icon = ($class == 'alert-danger') ? '<i class="fas fa-exclamation-circle"></i>' : '<i class="fas fa-check-circle"></i>';
            
            echo '<div class="'.$tailwindClass.'" id="msg-flash">'.$icon.' '.$_SESSION[$name].'</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name. '_class']);
        }
    }
}

function isLoggedIn(){
    // On vérifie que l'ID est là
    if(isset($_SESSION['user_id'])){
        return true;
    } else {
        return false;
    }
}

function isAdmin(){
    if(isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'){
        return true;
    }
    return false;
}

