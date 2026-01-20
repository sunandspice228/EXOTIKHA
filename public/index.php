<?php
// public/index.php

require_once __DIR__ . '/../app/init.php';

use Core\Router;

$router = new Router();

// --- ROUTES PUBLIQUES ---
$router->get('/', 'HomeController@index');
$router->get('/product/{id}', 'HomeController@product');

// Panier
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->get('/cart/remove/{id}', 'CartController@remove');

// Paiement & Commande
$router->get('/checkout', 'CheckoutController@index');    // Page récap
$router->post('/checkout/process', 'CheckoutController@process'); // Envoi Paystack
$router->get('/checkout/callback', 'CheckoutController@callback'); // Retour Paystack

// Compte Client
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@loginPost');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@registerPost');
$router->get('/logout', 'AuthController@logout');
$router->get('/account', 'AccountController@index');
$router->get('/account/order/{id}', 'AccountController@order'); // Détail commande

// 👇👇👇 C'EST ICI QU'IL MANQUAIT LES ROUTES ! 👇👇👇
// Langue & Devise
$router->get('/lang/{code}', 'HomeController@setLang');
$router->get('/currency/{code}', 'HomeController@setCurrency');

// --- ROUTES ADMIN ---
$router->get('/admin/login', 'AdminAuthController@login');
$router->post('/admin/login', 'AdminAuthController@loginPost');
$router->get('/admin/logout', 'AdminAuthController@logout');
// Dans la section ADMIN
$router->get('/admin/products/image/delete/{id}', 'AdminProductController@deleteImage');

// Protection Admin (Middleware simplifié)
if (isset($_SESSION['admin_id'])) {
    $router->get('/admin/dashboard', 'AdminController@dashboard');
    
    // Produits
    $router->get('/admin/products', 'AdminProductController@index');
    $router->get('/admin/products/create', 'AdminProductController@create');
    $router->post('/admin/products/store', 'AdminProductController@store');
    $router->get('/admin/products/edit/{id}', 'AdminProductController@edit');
    $router->post('/admin/products/update/{id}', 'AdminProductController@update');
    $router->get('/admin/products/delete/{id}', 'AdminProductController@delete');

    // Attributs
    $router->get('/admin/attributes', 'AdminAttributeController@index');
    $router->post('/admin/attributes/store', 'AdminAttributeController@store');
    $router->get('/admin/attributes/delete/{type}/{id}', 'AdminAttributeController@delete');
}

// Lancement du routeur
$router->dispatch($_SERVER['REQUEST_URI']);