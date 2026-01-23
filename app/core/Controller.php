<?php
class Controller {
    // Charger le modèle
    public function model($model) {
        require_once '../app/Models/' . $model . '.php';
        return new $model();
    }

    // Charger la vue
    public function view($view, $data = []) {
        if(file_exists('../app/Views/' . $view . '.php')) {
            require_once '../app/Views/' . $view . '.php';
        } else {
            die('Vue inexistante : ' . $view);
        }
    }
    
    // Redirection simple
    public function redirect($url) {
        header('Location: ' . URLROOT . '/' . $url);
        exit();
    }
}