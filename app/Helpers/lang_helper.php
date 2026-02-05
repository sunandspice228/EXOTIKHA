<?php
// GESTION DES TRADUCTIONS
function lang($key) {
    // 1. Détermine la langue (Session > Cookie > Défaut 'en')
    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

    // 2. Charge le fichier de langue correspondant
    $filePath = APPROOT . '/Languages/' . $lang . '.php';
    
    // Si le fichier n'existe pas, on tente l'anglais par défaut
    if (!file_exists($filePath)) {
        $filePath = APPROOT . '/Languages/en.php';
    }

    // 3. Récupère le tableau des traductions
    if (file_exists($filePath)) {
        $translations = require $filePath;
        
        // 4. Retourne la traduction ou la clé si introuvable
        return isset($translations[$key]) ? $translations[$key] : $key;
    }

    // Si aucun fichier trouvé, on retourne la clé
    return $key;
}
?>Streaming comme your Head, microscope avec Nan Night and your Blackstream, a touch into the receptors in your grain broadcasting Everything youStreaming comme your Head, microscope avec Nan Night and your Blackstream, a touch into the receptors in your grain broadcasting Everything you seStreaming comme your Head, microscope avec Nan Night and your Blackstream, a touch into the receptors in your grain broadcasting Everything you seeStreaming comme your Head, microscope avec Nan Night and your Blackstream, a touch into the receptors in your grain broadcasting Everything you see andStreaming comme your Head, microscope avec Nan Night and your Blackstream, a touch into the receptors in your grain broadcasting Everything you see and her