<?php
/*
 * App Core Class
 * Crée les URLs & charge le contrôleur principal
 * Format URL : /controller/method/params
 */
class Router {
    protected $currentController = 'Pages'; // Contrôleur par défaut (Accueil)
    protected $currentMethod = 'index';     // Méthode par défaut
    protected $params = [];

    public function __construct(){
        //print_r($this->getUrl()); // Decommenter pour debug

        $url = $this->getUrl();

        // 1. Chercher le contrôleur dans app/Controllers/
        if(isset($url[0]) && file_exists('../app/Controllers/' . ucwords($url[0]) . '.php')){
            $this->currentController = ucwords($url[0]);
            unset($url[0]);
        }

        // Requérir le contrôleur
        require_once '../app/Controllers/' . $this->currentController . '.php';

        // Instancier le contrôleur
        $this->currentController = new $this->currentController;

        // 2. Chercher la méthode
        if(isset($url[1])){
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // 3. Récupérer les paramètres
        $this->params = $url ? array_values($url) : [];

        // Appeler la méthode avec un tableau de params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}