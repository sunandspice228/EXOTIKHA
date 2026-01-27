<?php
// 1. Charger la Configuration
require_once 'config/config.php';

// 2. Charger les Helpers
require_once 'Helpers/url_helper.php';
require_once 'Helpers/session_helper.php';
require_once 'Helpers/mail_helper.php';

// 3. Autoloader (CORRIGÉ)
// On ajoute APPROOT pour forcer PHP à chercher dans "C:\wamp64\www\EXOTIKHA\app\Core\"
spl_autoload_register(function($className){
    require_once APPROOT . '/Core/' . $className . '.php';
});