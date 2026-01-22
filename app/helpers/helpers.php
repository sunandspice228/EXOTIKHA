<?php
// app/helpers/helpers.php

if (session_status() === PHP_SESSION_NONE) session_start();

/**
 * 1. SÉCURITÉ & UTILITAIRES
 */

// Échapper les caractères spéciaux (Protection XSS)
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Générer une URL absolue
function url($path) {
    // On s'assure que ROOT_URL est défini dans config.php
    // S'il n'est pas défini, on met une chaine vide pour éviter le crash
    $root = defined('ROOT_URL') ? ROOT_URL : '';
    return rtrim($root, '/') . '/' . ltrim($path, '/');
}

// Debug facile (dd = dump and die)
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

/**
 * 2. TRADUCTION & LANGUES (C'est ici que j'ai ajouté les fonctions manquantes)
 */

// Traduction Texte Statique (Menu, Boutons...) -> INDISPENSABLE POUR TES VUES
function __($fr, $en) {
    return (isset($_SESSION['lang']) && $_SESSION['lang'] === 'en') ? $en : $fr;
}

// Vérifier si on est en anglais (Pour le sélecteur de langue)
function is_en() {
    return isset($_SESSION['lang']) && $_SESSION['lang'] === 'en';
}

// Récupérer un champ traduit depuis la BDD (ex: name vs name_en)
function get_tr($data, $field = 'name') {
    $currentLang = $_SESSION['lang'] ?? 'fr';
    
    // Si on est en Anglais et que le champ _en existe et n'est pas vide
    if ($currentLang === 'en' && !empty($data[$field . '_en'])) {
        return $data[$field . '_en'];
    }
    
    // Sinon on retourne le champ par défaut (Français)
    return $data[$field] ?? '';
}

/**
 * 3. GESTION DES DEVISES & PRIX
 */

// Récupère la devise choisie par le client (GHS par défaut)
function get_current_currency() {
    return $_SESSION['currency'] ?? 'GHS';
}

// Formater un prix (Base GHS -> Devise Client)
function format_price($amountGHS) {
    $code = get_current_currency();
    $rates = defined('CURRENCY_RATES') ? CURRENCY_RATES : [];
    
    // Si la devise n'est pas configurée, on reste en GHS
    if (!isset($rates[$code])) {
        return number_format($amountGHS, 2) . ' GH₵';
    }

    $currency = $rates[$code];

    // Conversion : Montant (GHS) * Taux
    $convertedAmount = $amountGHS * $currency['rate'];

    // Formatage (Nombre de décimales selon la devise)
    $formatted = number_format($convertedAmount, $currency['decimal'], '.', ' ');

    // Affichage Symbole (Avant pour USD, Après pour les autres)
    if ($code === 'USD') {
        return $currency['symbol'] . $formatted;
    } else {
        return $formatted . ' ' . $currency['symbol'];
    }
}

/**
 * 4. LOGIQUE PROMOTIONNELLE
 */

// Vérifie si un produit est réellement en promotion
function is_on_promotion($p) {
    return (
        isset($p['is_promo']) && 
        $p['is_promo'] == 1 && 
        !empty($p['promo_price']) && 
        $p['promo_price'] > 0 &&
        $p['promo_price'] < $p['price']
    );
}

// Génère le HTML du prix (Gère l'affichage barré si promo)
function format_product_price_html($p) {
    if (is_on_promotion($p)) {
        // Affichage Promo : Ancien prix barré + Nouveau prix rouge
        return '
            <span class="text-muted text-decoration-line-through small me-2">' . format_price($p['price']) . '</span>
            <span class="fw-bold text-danger">' . format_price($p['promo_price']) . '</span>
        ';
    } else {
        // Affichage Normal
        return '<span class="fw-bold text-dark">' . format_price($p['price']) . '</span>';
    }
}

/**
 * 5. PANIER
 */

// Récupérer le contenu du panier
function get_cart() {
    return $_SESSION['cart'] ?? [];
}

// Compter le nombre d'articles (quantité totale)
function cart_count() {
    return array_sum($_SESSION['cart'] ?? []);
}

/**
 * 6. PROTECTION CSRF (Formulaires)
 */

// Génère le champ caché pour les formulaires
function csrf_field() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

// Vérifie le jeton à la réception du formulaire
function verify_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Erreur de sécurité : Session expirée ou tentative CSRF invalide. Veuillez rafraîchir la page.');
    }
}

/**
 * 7. MESSAGES FLASH (Notifications)
 */

// Enregistrer un message
function flash($type, $msg) { 
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg]; 
}

// Afficher le message (Compatible Bootstrap & Tailwind)
function display_flash_message() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        
        // Classes Bootstrap standards
        $alertClass = ($f['type'] === 'success') ? 'alert-success' : 'alert-danger';
        
        echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show my-3 shadow-sm" role="alert">';
        echo $f['msg'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
        
        unset($_SESSION['flash']);
    }
}