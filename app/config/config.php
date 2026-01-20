<?php
// app/config/config.php

// URL DE BASE (Important pour WAMP)
// Si ton site est accessible via http://localhost/EXOTIKHA/public
define('ROOT_URL', 'http://localhost/EXOTIKHA/public');

// CHEMIN PHYSIQUE
define('ROOT_PATH', dirname(__DIR__, 2));

// BASE DE DONNÉES
define('DB_HOST', 'localhost');
define('DB_NAME', 'exotikha'); // Vérifie le nom de ta BDD
define('DB_USER', 'root');
define('DB_PASS', '');

// PAYSTACK (Tes clés, à garder secrètes)
define('PAYSTACK_SECRET_KEY', 'sk_test_04d38cc27292308f78c9a88005d29a213dc715f2'); 
define('PAYSTACK_PUBLIC_KEY', 'pk_test_a789bb5265210eb99bb497e0e2c2111cf8f180c4');

// DEVISES
define('CURRENCY_RATES', [
    'GHS' => ['rate' => 1,       'symbol' => 'GH₵',  'decimal' => 2],
    'XOF' => ['rate' => 43.5,    'symbol' => 'FCFA', 'decimal' => 0],
    'USD' => ['rate' => 0.065,   'symbol' => '$',    'decimal' => 2]
]);
define('DEFAULT_CURRENCY', 'GHS');