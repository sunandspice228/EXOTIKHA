<?php
/*
 * Classe Router
 * Crée les URL et charge le contrôleur principal
 * URL FORMAT - /controller/method/params
 */
class Router {
    protected $currentController = 'Pages'; // Contrôleur par défaut
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
        $url = $this->getUrl();

        // 1. Chercher le contrôleur dans app/Controllers/
        if(isset($url[0]) && file_exists('../app/Controllers/' . ucwords($url[0]) . '.php')){
            // Si le fichier existe, on le définit comme contrôleur actuel
            $this->currentController = ucwords($url[0]);
            // On retire la valeur du tableau url
            unset($url[0]);
        }

        // 2. REQUÉRIR LE CONTRÔLEUR (C'est l'étape qui échouait !)
        require_once '../app/Controllers/' . $this->currentController . '.php';

        // 3. Instancier la classe contrôleur (ex: new Wishlist)
        $this->currentController = new $this->currentController;

        // 4. Vérifier la deuxième partie de l'URL (la méthode)
        if(isset($url[1])){
            // Vérifier si la méthode existe dans le contrôleur (ex: index, add, delete...)
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // 5. Récupérer les paramètres
        $this->params = $url ? array_values($url) : [];

        // 6. Appeler la méthode du contrôleur avec les paramètres
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if(isset($_GET['url'])){
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}