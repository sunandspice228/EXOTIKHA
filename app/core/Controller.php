<?php
/*
 * Contrôleur de Base
 * Charge les modèles et les vues
 */
class Controller {
    // Charger le modèle
    public function model($model){
        // Requiert le fichier modèle
        require_once '../app/Models/' . $model . '.php';

        // Instancier le modèle
        return new $model();
    }

    // Charger la vue
    public function view($view, $data = []){
        // Vérifier si le fichier vue existe
        if(file_exists('../app/Views/' . $view . '.php')){
            require_once '../app/Views/' . $view . '.php';
        } else {
            // La vue n'existe pas
            die('La vue n\'existe pas : ' . $view);
        }
    }
}