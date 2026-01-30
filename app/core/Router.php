<?php
/*
 * Classe Router (Cœur de l'application)
 * Crée les URL et charge le contrôleur principal
 * URL FORMAT - /controller/method/params
 */
class Router {
    protected $currentController = 'Pages'; // MODIFIÉ : On pointe vers notre nouveau contrôleur d'accueil
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
        // $url[0] = Contrôleur, $url[1] = Méthode, $url[2] = Paramètre
        $url = $this->getUrl();

        // 1. Chercher le contrôleur dans app/Controllers/
        // On vérifie si le premier segment de l'URL correspond à un fichier existant
        if(isset($url[0]) && file_exists('../app/Controllers/' . ucwords($url[0]) . '.php')){
            // Si le fichier existe, on le définit comme contrôleur actuel
            $this->currentController = ucwords($url[0]);
            // On retire la valeur du tableau url pour ne garder que méthode et params
            unset($url[0]);
        }

        // 2. REQUÉRIR LE CONTRÔLEUR
        require_once '../app/Controllers/' . $this->currentController . '.php';

        // 3. Instancier la classe contrôleur (ex: $this->currentController = new Cart())
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
        // S'il reste des éléments dans $url, ce sont des paramètres, sinon tableau vide
        $this->params = $url ? array_values($url) : [];

        // 6. Appeler la méthode du contrôleur avec les paramètres
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if(isset($_GET['url'])){
            // On retire le slash final
            $url = rtrim($_GET['url'], '/');
            // On nettoie l'URL (caractères illégaux)
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // On explose en tableau
            $url = explode('/', $url);
            return $url;
        }
        return [];
    }
}