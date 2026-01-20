<?php
// Fichier : app/init.php

// 1. Charger la Configuration
// Vérifie bien que config.php est dans app/config/
require_once __DIR__ . '/config/config.php';

// 2. Charger les Fonctions d'aide (Helpers)
// Vérifie bien que helpers.php est dans app/helpers/
require_once __DIR__ . '/helpers/helpers.php';

// 3. AUTOLOADER
// C'est la partie magique : quand tu fais "new Router()", 
// ce code va chercher automatiquement le fichier "app/core/Router.php"
spl_autoload_register(function ($className) {
    
    // On est dans le dossier /app
    $appRoot = __DIR__;

    // A. Si on cherche une classe "Core" (ex: Core\Router)
    // On cherche dans app/core/Router.php
    if (strpos($className, 'Core\\') === 0) {
        $path = $appRoot . '/core/' . str_replace('Core\\', '', $className) . '.php';
    } 
    
    // B. Si on cherche une classe "App" (ex: App\Controllers\HomeController)
    // On cherche dans app/controllers/HomeController.php
    elseif (strpos($className, 'App\\') === 0) {
        // On enlève "App\" du début et on remplace les "\" par des "/"
        $cleanName = str_replace('App\\', '', $className);
        $path = $appRoot . '/' . str_replace('\\', '/', $cleanName) . '.php';
    }
    
    // Si le fichier existe, on le charge
    if (isset($path) && file_exists($path)) {
        require_once $path;
    }
});