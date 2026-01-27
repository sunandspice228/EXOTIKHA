<?php
// DB Params
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'exotikha'); // Assure-toi que c'est le bon nom

// App Root
define('APPROOT', dirname(dirname(__FILE__)));

// URL Root (IMPORTANT : pas de slash à la fin)
define('URLROOT', 'http://localhost/exotikha'); 

// Site Name
define('SITENAME', 'Exotikha');

// Currency
define('CURRENCY', 'GHS');
define('CURRENCY_SYMBOL', '₵');

// Paystack API Keys
define('PAYSTACK_PUBLIC_KEY', 'pk_test_a789bb5265210eb99bb497e0e2c2111cf8f180c4');
define('PAYSTACK_SECRET_KEY', 'sk_test_04d38cc27292308f78c9a88005d29a213dc715f2');


// --- CONFIGURATION EMAIL (SMTP) ---
// Exemple avec Mailtrap (Change avec tes infos)
define('SMTP_HOST', 'sandbox.smtp.mailtrap.io');
define('SMTP_USER', '297f6d527bb6bc');
define('SMTP_PASS', '615159b2d0e390');
define('SMTP_PORT', 2525);
define('SMTP_FROM', 'no-reply@exotikha.com');
define('SMTP_NAME', 'Exotikha Ghana');
// Configuration Livraison (OpenStreetMap)
define('WAREHOUSE_LAT', 5.6709); // Latitude de ton entrepôt (Ex: Accra Mall)
define('WAREHOUSE_LNG', -0.1652); // Longitude de ton entrepôt
define('SHIPPING_PER_KM', 2); // Prix en GHS par kilomètre
