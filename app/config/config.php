<?php
// =========================================================
// 1. DATABASE
// =========================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'exotikha'); 

// =========================================================
// 2. PATHS & URLS (DYNAMIC & AUTOMATIC)
// =========================================================
// App Root
define('APPROOT', dirname(dirname(__FILE__)));

// URL Root - DÉTECTION AUTOMATIQUE
// Ce bloc vérifie si vous êtes en http ou https, et quel est le domaine actuel.
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST']; // Récupère 'localhost' ou 'xxxx.ngrok-free.app'

// Résultat : Crée l'URL parfaite automatiquement
define('URLROOT', $protocol . '://' . $host . '/EXOTIKHA');

// Site Name
define('SITENAME', 'Exotikha');
define('APPVERSION', '1.0.0');

// =========================================================
// 3. CURRENCY & REGION
// =========================================================
define('CURRENCY', 'GHS');
define('CURRENCY_SYMBOL', '₵');

// =========================================================
// 4. PAYMENT (PAYSTACK)
// =========================================================
define('PAYSTACK_PUBLIC_KEY', 'pk_test_a789bb5265210eb99bb497e0e2c2111cf8f180c4');
define('PAYSTACK_SECRET_KEY', 'sk_test_04d38cc27292308f78c9a88005d29a213dc715f2');

// =========================================================
// 5. EMAIL (SMTP - Mailtrap)
// =========================================================
define('SMTP_HOST', 'sandbox.smtp.mailtrap.io');
define('SMTP_USER', '297f6d527bb6bc');
define('SMTP_PASS', '615159b2d0e390');
define('SMTP_PORT', 2525);

define('SMTP_FROM', 'no-reply@exotikha.com');
define('SMTP_NAME', 'Exotikha Ghana');

// =========================================================
// 6. SHIPPING (Geo-Location)
// =========================================================
define('WAREHOUSE_LAT', 5.6231); 
define('WAREHOUSE_LNG', -0.1738); 

define('SHIPPING_BASE_FEE', 15); 
define('SHIPPING_PER_KM', 2.5);