<?php
// 1. Redirection simple
function redirect($page){
    header('location: ' . URLROOT . '/' . $page);
    exit; // Important pour stopper le script
}

// 2. Nettoyage des inputs (Protection XSS)
function sanitize($data){
    return htmlspecialchars(strip_tags(trim($data)));
}

// 3. Génération Token CSRF
function generateCsrfToken(){
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// 4. Input caché pour les formulaires
function csrfField(){
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="'.$token.'">';
}

// 5. VÉRIFICATION CSRF
// À appeler au début des méthodes POST dans le contrôleur (mais pas ici !)
function verifyCsrfToken(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // J'AI SUPPRIMÉ LA LIGNE QUI FAISAIT LA BOUCLE INFINIE ICI
        
        if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']){
            // On arrête tout si le token est mauvais
            die('Erreur de sécurité (CSRF) : Session expirée. Veuillez rafraîchir la page.');
        }
    }
}