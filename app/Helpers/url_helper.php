<?php
// Redirection simple
function redirect($page){
    header('location: ' . URLROOT . '/' . $page);
    exit();
}

// Nettoyage des inputs (Protection XSS de base)
function sanitize($data){
    return htmlspecialchars(strip_tags(trim($data)));
}

// Génération Token CSRF
function generateCsrfToken(){
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Input caché pour les formulaires
function csrfField(){
    $token = generateCsrfToken();
    return '<input type="hidden" name="csrf_token" value="'.$token.'">';
}