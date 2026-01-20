<?php
namespace Core;

class Controller {
    // Charger une vue avec des données
    public function view($view, $data = []) {
        extract($data); // Transforme ['titre' => 'Accueil'] en variable $titre = 'Accueil'
        
        $file = '../app/views/' . $view . '.php';
        
        if (file_exists($file)) {
            require_once $file;
        } else {
            die("<h1>Erreur Vue</h1><p>Le fichier vue n'existe pas : <b>$file</b></p>");
        }
    }

    // Redirection facile
    public function redirect($url) {
        if (!headers_sent()) {
            header("Location: " . ROOT_URL . $url);
            exit;
        } else {
            // Fallback JS si les headers sont déjà envoyés
            echo '<script>window.location.href="'.ROOT_URL.$url.'";</script>';
            exit;
        }
    }
}