<?php
if (!file_exists('../app/helpers/helpers.php')) {
    die("ERREUR FATALE : Le fichier helpers.php est introuvable ! Vérifiez le dossier app/helpers/");
}
// 1. Affichage des erreurs
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. CHARGEMENT DES FICHIERS CŒUR (L'ORDRE EST IMPORTANT !)
require_once '../app/config/config.php';
require_once '../app/core/Database.php';
require_once '../app/core/Model.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Router.php';

// C'EST CETTE LIGNE QUI VOUS MANQUE PROBABLEMENT :
require_once '../app/helpers/helpers.php'; 

// 3. Autoloader
spl_autoload_register(function ($class) {
    $path = '../' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) require_once $path;
});

// 4. Router
$router = new Core\Router();

// --- ROUTES ---
$router->get('/', 'HomeController@index');
$router->get('/product/{id}', 'HomeController@product');
$router->get('/lang/{lang}', 'HomeController@setLang');

$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->get('/cart/remove/{id}', 'CartController@remove');
$router->get('/cart/update/{action}/{id}', 'CartController@update');

$router->get('/checkout', 'CheckoutController@index');
$router->post('/checkout/process', 'CheckoutController@process');
$router->get('/checkout/callback', 'CheckoutController@callback');
$router->get('/checkout/success', 'CheckoutController@success');

$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

$router->get('/account', 'AccountController@index');
$router->post('/account/update', 'AccountController@update');

// Admin
$router->get('/admin/login', 'AdminController@login');
$router->post('/admin/login', 'AdminController@login');
$router->get('/admin/logout', 'AdminController@logout');
$router->get('/admin/dashboard', 'AdminController@dashboard');

$router->get('/admin/products', 'AdminProductController@index');
$router->get('/admin/products/create', 'AdminProductController@create');
$router->post('/admin/products/store', 'AdminProductController@store');
$router->get('/admin/products/edit/{id}', 'AdminProductController@edit');
$router->post('/admin/products/update/{id}', 'AdminProductController@update');
$router->get('/admin/products/delete/{id}', 'AdminProductController@delete');

$router->get('/admin/attributes', 'AdminAttributeController@index');
$router->post('/admin/attributes/store', 'AdminAttributeController@store');
$router->get('/admin/attributes/edit/{type}/{id}', 'AdminAttributeController@edit');
$router->post('/admin/attributes/update/{type}/{id}', 'AdminAttributeController@update');
$router->get('/admin/attributes/delete/{type}/{id}', 'AdminAttributeController@delete');

$router->get('/admin/orders', 'AdminOrderController@index');
$router->post('/admin/orders/update/{id}', 'AdminOrderController@updateStatus');

// 5. Lancement
$router->dispatch();