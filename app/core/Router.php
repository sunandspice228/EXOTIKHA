<?php
// ⛔ SÉCURITÉ OPTIMALE : Empêche l'accès direct au fichier via l'URL
if (!defined('APPROOT')) {
    die('Accès interdit');
}

/*
 * Classe Router (Cœur de l'application)
 * Crée les URL et charge le contrôleur principal
 * SÉCURISÉ CONTRE LES INJECTIONS ET L'ACCÈS DIRECT
 */
class Router {
    protected $currentController = 'Pages'; // Contrôleur par défaut
    protected $currentMethod = 'index';     // Méthode par défaut
    protected $params = [];

    public function __construct(){
        $url = $this->getUrl();

        // 1. RECHERCHE SÉCURISÉE DU CONTRÔLEUR
        if(isset($url[0])){
            // SÉCURITÉ : On nettoie le nom du fichier pour éviter les ".." (Directory Traversal)
            // On ne garde que les lettres, chiffres et underscores
            $cleanControllerName = preg_replace('/[^a-zA-Z0-9_]/', '', $url[0]);
            $controllerName = ucwords($cleanControllerName);

            if(file_exists('../app/Controllers/' . $controllerName . '.php')){
                $this->currentController = $controllerName;
                unset($url[0]);
            }
        }

        // 2. REQUÉRIR LE CONTRÔLEUR
        require_once '../app/Controllers/' . $this->currentController . '.php';

        // 3. INSTANCIER LA CLASSE
        $this->currentController = new $this->currentController;

        // 4. VÉRIFIER LA MÉTHODE
        if(isset($url[1])){
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
        }

        // 5. RÉCUPÉRER LES PARAMÈTRES
        $this->params = $url ? array_values($url) : [];

        // 6. LANCER LE CONTRÔLEUR
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
        if(isset($_GET['url'])){
            // 1. Supprimer le slash final
            $url = rtrim($_GET['url'], '/');
            
            // 2. SÉCURITÉ : Nettoyer l'URL (Retire les caractères illégaux comme <, >, etc.)
            $url = filter_var($url, FILTER_SANITIZE_URL);
            
            // 3. Découper en tableau
            $url = explode('/', $url);
            
            return $url;
        }
        return [];
    }
}