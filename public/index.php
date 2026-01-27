<?php
// 1. Charger la config
require_once '../app/Config/config.php';
require_once '../app/bootstrap.php';



// 2. Charger les Helpers (INDISPENSABLE pour les fonctions flash() et redirect())
// Ces fichiers contiennent des fonctions, pas des classes, donc l'autoloader ne les charge pas.
require_once '../app/Helpers/session_helper.php';
require_once '../app/Helpers/url_helper.php';

// 3. Autoloader pour charger les Classes automatiquement (Core, Models, Controllers)
spl_autoload_register(function($className){
    // Vérifie dans Core
    if (file_exists('../app/Core/' . $className . '.php')) {
        require_once '../app/Core/' . $className . '.php';
    }
    // Vérifie dans Models (Utile si on instancie un modèle directement)
    elseif (file_exists('../app/Models/' . $className . '.php')) {
        require_once '../app/Models/' . $className . '.php';
    }
    // Vérifie dans Controllers
    elseif (file_exists('../app/Controllers/' . $className . '.php')) {
        require_once '../app/Controllers/' . $className . '.php';
    }
});
$init = new Router;