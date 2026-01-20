<?php
// app/helpers/helpers.php

if (session_status() === PHP_SESSION_NONE) session_start();

// 1. Sécurité XSS
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// 2. URL absolue
function url($path) {
    return rtrim(ROOT_URL, '/') . '/' . ltrim($path, '/');
}

// 3. Traduction simple
function lang($key) {
    return $key; // Simplifié pour l'instant
}

// 4. Prix
function format_price($amount) {
    return number_format($amount, 0, ',', ' ') . ' FCFA';
}

function format_product_price_html($p) {
    return '<span class="fw-bold">' . format_price($p['price']) . '</span>';
}

// 5. Panier
function get_cart() { return $_SESSION['cart'] ?? []; }
function cart_count() { return array_sum($_SESSION['cart'] ?? []); }

// 6. CSRF
function csrf_field() {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

function verify_csrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Session expirée (CSRF).');
    }
}

// 7. Messages Flash (Stockage)
function flash($type, $msg) { 
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg]; 
}

// 8. Affichage Flash (LA FONCTION QUI MANQUAIT)
function display_flash_message() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        // Classes compatibles Bootstrap (Public) et Tailwind (Admin)
        $color = ($f['type'] === 'error') ? 'red' : 'green';
        
        echo '<div style="padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius