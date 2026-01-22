<?php
// 1. Démarrage de la session (OBLIGATOIRE au tout début)
session_start();

// 2. Chargement de la config et des helpers
require_once '../app/config/config.php';
require_once '../app/helpers/helpers.php';

// 3. Autoloader (CORRIGÉ pour gérer Core\ et App\)
spl_autoload_register(function ($class) {
    $root = dirname(__DIR__); // Remonte à la racine (C:\wamp64\www\EXOTIKHA)
    
    // Si la classe commence par "Core\" (ex: Core\Router)
    if (strpos($class, 'Core\\') === 0) {
        $className = substr($class, 5);
        $file = $root . '/app/core/' . str_replace('\\', '/', $className) . '.php';
    } 
    // Si la classe commence par "App\" (ex: App\Controllers\Home)
    elseif (strpos($class, 'App\\') === 0) {
        $className = substr($class, 4);
        $file = $root . '/app/' . str_replace('\\', '/', $className) . '.php';
    }
    // Cas par défaut
    else {
        $file = $root . '/' . str_replace('\\', '/', $class) . '.php';
    }

    if (file_exists($file)) {
        require_once $file;
    }
});

use Core\Router;

$router = new Router();

// ==============================
// 1. ROUTES PUBLIQUES
// ==============================
$router->get('/', 'HomeController@index');
$router->get('/product/{id}', 'HomeController@product');
$router->get('/lang/{code}', 'HomeController@setLang');
$router->get('/currency/{code}', 'HomeController@setCurrency');

// Panier
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->get('/cart/remove/{id}', 'CartController@remove');

// Checkout
$router->get('/checkout', 'CheckoutController@index');
$router->post('/checkout/pay', 'CheckoutController@processPayment');

// Avis
$router->post('/reviews/store', 'ReviewController@store');

// ==============================
// 2. AUTHENTIFICATION (C'est là que ça se joue !)
// ==============================

// Connexion
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@loginPost');

// Inscription (IL FAUT BIEN CES DEUX LIGNES)
$router->get('/register', 'AuthController@register');       // Pour AFFICHER le formulaire (GET)
$router->post('/register', 'AuthController@registerPost');  // Pour TRAITER le formulaire (POST) <--- CELLE-CI MANQUAIT
// NOUVELLES ROUTES 👇
$router->get('/account/orders', 'AccountController@orders');       // Mes Commandes
$router->get('/account/profile', 'AccountController@profile');     // Mon Profil
$router->post('/account/profile', 'AccountController@updateProfile'); // Sauvegarder Profil
$router->get('/account/addresses', 'AccountController@addresses'); // Adresses
// Logout & Compte
$router->get('/logout', 'AuthController@logout');
$router->get('/account', 'AccountController@index');
// --- AUTH ---
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@loginPost');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@registerPost');
$router->get('/logout', 'AuthController@logout');

// --- ACCOUNT ---
$router->get('/profile', 'AccountController@index');
$router->post('/profile/update', 'AccountController@update');

// ==============================
// 3. ADMIN
// ==============================
$router->get('/admin/login', 'AdminController@login');
$router->post('/admin/login', 'AdminController@loginPost');

// --- ADMIN ROUTES ---
$router->get('/admin/dashboard', 'AdminController@dashboard');

// Produits
$router->get('/admin/products', 'AdminController@products');
$router->get('/admin/products/create', 'AdminController@createProduct');
$router->post('/admin/products/store', 'AdminController@storeProduct');
$router->get('/admin/products/delete/{id}', 'AdminController@deleteProduct');
$router->get('/admin/products/edit/{id}', 'AdminController@editProduct'); // Afficher form
$router->post('/admin/products/update', 'AdminController@updateProduct'); // Traiter form
// Route pour supprimer une image de la galerie
$router->get('/admin/gallery/delete/{id}', 'AdminController@deleteGalleryImage');
// Commandes
$router->get('/admin/orders', 'AdminController@orders');
$router->get('/admin/orders/show/{id}', 'AdminController@orderShow'); // Route détail
$router->post('/admin/orders/update', 'AdminController@updateOrderStatus'); // Route mise à jour
// Catégories
$router->get('/admin/categories', 'CategoryController@index');
$router->post('/admin/categories/store', 'CategoryController@store');
$router->get('/admin/categories/delete/{id}', 'CategoryController@delete');

// Types
$router->get('/admin/types', 'TypeController@index');
$router->post('/admin/types/store', 'TypeController@store');
$router->get('/admin/types/delete/{id}', 'TypeController@delete');
// Clients
$router->get('/admin/customers', 'AdminController@customers');
$router->post('/join-private-sale', 'HomeController@joinPrivateSale');
// ... autres routes ...

// Route pour l'admin (Voir les leads) - Si ce n'est pas déjà fait
$router->get('/admin/leads', 'AdminController@privateSales');

// ... dispatch ...
// Attributs
$router->get('/admin/attributes', 'AttributeController@index');
$router->post('/admin/attributes/store', 'AttributeController@store');
$router->get('/admin/attributes/delete/{id}', 'AttributeController@delete');
// ==============================
// 4. LANCEMENT DU ROUTEUR
// ==============================
$uri = $_SERVER['REQUEST_URI'];

// --- DÉBUT DU FORÇAGE MANUEL ---
// On nettoie l'URI comme le fait le routeur pour être sûr
$scriptName = dirname($_SERVER['SCRIPT_NAME']);
if ($scriptName !== '/' && $scriptName !== '\\') {
    $cleanUri = str_ireplace($scriptName, '', $uri);
} else {
    $cleanUri = $uri;
}
$cleanUri = '/' . trim($cleanUri, '/');

// Si c'est l'inscription en POST, on déclenche directement le contrôleur !
if ($cleanUri === '/register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\AuthController();
    $controller->registerPost();
    exit; // On arrête tout ici, on ne lance pas le routeur normal
}
// --- FIN DU FORÇAGE MANUEL ---

$router->dispatch($uri);

$router->dispatch($uri);