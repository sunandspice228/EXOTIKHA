<?php
// app/config/config.php

// URL Racine (Adapter selon ton dossier Wamp)
define('ROOT_URL', 'http://localhost/EXOTIKHA/public'); 
define('ROOT_PATH', dirname(dirname(__DIR__)));

// Base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'exotikha');
define('DB_USER', 'root');
define('DB_PASS', '');

// Paystack (Clés Test)
define('PAYSTACK_SECRET_KEY', 'sk_test_xxxxxxxxxxxxxxxxxxxx'); 
define('PAYSTACK_PUBLIC_KEY', 'pk_test_xxxxxxxxxxxxxxxxxxxx');