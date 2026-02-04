<?php
// Load Config
require_once 'Config/config.php';

// Load Helpers
require_once 'Helpers/url_helper.php';
require_once 'Helpers/session_helper.php';
require_once 'Helpers/mail_helper.php'; // (Si vous l'avez)
require_once 'Helpers/lang_helper.php'; // <--- AJOUTEZ CETTE LIGNE ICI !

// Autoload Core Libraries
spl_autoload_register(function($className){
    require_once 'Core/' . $className . '.php';
});

// A. CHARGER COMPOSER (Pour Dompdf, Paystack, PHPMailer, etc.)
// C'est cette ligne qui manquait ou qui était mal placée !
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

// B. CHARGER LES LIBRAIRIES CORE (Ton Framework MVC)
// On utilise spl_autoload_register pour tes propres classes (Core, Controllers, Models)
spl_autoload_register(function($className){
    // On ne charge ICI que les fichiers qui sont dans le dossier 'Core' ou 'Libraries'
    // Pour éviter l'erreur sur Dompdf, on vérifie si le fichier existe avant de l'inclure
    
    $fileCore = APPROOT . '/Core/' . $className . '.php';
    $fileLib  = APPROOT . '/Libraries/' . $className . '.php';

    if(file_exists($fileCore)){
        require_once $fileCore;
    } elseif(file_exists($fileLib)){
        require_once $fileLib;
    }
    // Si le fichier n'existe pas ici, on laisse Composer (vendor/autoload) s'en occuper
});