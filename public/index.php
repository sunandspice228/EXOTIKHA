<?php
// 1. Charger la Configuration en premier
require_once '../app/Config/config.php';

// 2. Gestion Sécurisée des Erreurs
// Si DEBUG_MODE est activé dans config.php, on affiche les erreurs.
// Sinon (en Production), on les cache pour ne pas révéler les failles aux pirates.
if (defined('DEBUG_MODE') && DEBUG_MODE === true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// 3. Charger le Bootstrap (Le moteur de l'app)
require_once '../app/bootstrap.php';

// 4. Initialiser le Routeur (Lancement du site)
// Assurez-vous que votre classe principale s'appelle bien 'Router' ou 'Core'
$init = new Router;