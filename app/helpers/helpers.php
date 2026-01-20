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
    return rtrim(ROOT_URL, '/') . '/' . ltrim($path, '/');
}

// Debug facile (dd = dump and die)
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

/**
 * 2. TRADUCTION & LANGUES
 */

// Traduction simple (Clé -> Valeur) - À étendre si besoin
function lang($key) {
    return $key; 
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
    return $_SESSION['currency'] ?? DEFAULT_CURRENCY;
}

// Formater un prix (Base GHS -> Devise Client)
function format_price($amountGHS) {
    $code = get_current_currency();
    
    // On vérifie si la devise existe dans la config, sinon fallback sur GHS
    if (!defined('CURRENCY_RATES') || !array_key_exists($code, CURRENCY_RATES)) {
        return number_format($amountGHS, 2) . ' GH₵';
    }

    $currency = CURRENCY_RATES[$code];

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
        
        // Définition de la classe couleur selon le framework CSS détecté ou générique
        // 'error' -> rouge, 'success' -> vert
        $alertClass = ($f['type'] === 'error') ? 'alert-danger bg-red-100 text-red-700 border-red-400' : 'alert-success bg-green-100 text-green-700 border-green-400';
        
        echo '<div class="alert ' . $alertClass . ' border px-4 py-3 rounded relative mb-4" role="alert">';
        echo $f['msg'];
        echo '</div>';
        
        unset($_SESSION['flash']);
    }
}