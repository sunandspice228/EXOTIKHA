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