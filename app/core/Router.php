<?php
namespace Core;

class Router {
    protected $routes = [];

    // Ajouter une route
    public function add($method, $uri, $controller) {
        // Transforme la route (ex: /product/{id}) en Expression Régulière
        $uriRegex = '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $uri) . '$#';
        
        $this->routes[] = [
            'method' => $method,
            'uri' => $uriRegex,
            'controller' => $controller
        ];
    }

    public function get($uri, $controller) { $this->add('GET', $uri, $controller); }
    public function post($uri, $controller) { $this->add('POST', $uri, $controller); }

    // Dispatcher l'URL actuelle
    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // 1. Correction spécifique pour WampServer/Windows
        // dirname renvoie des antislashes (\) sous Windows, on les remplace par des slashes (/)
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        
        // 2. On retire le nom du sous-dossier de l'URL (ex: on enlève /exotikha/public)
        if (strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }
        
        // 3. On s'assure que l'URI commence toujours par un seul slash "/"
        // Si l'URI est vide (racine), elle devient "/"
        $uri = '/' . trim($uri, '/');
        
        $method = $_SERVER['REQUEST_METHOD'];

        // 4. Recherche de correspondance
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['uri'], $uri, $matches)) {
                array_shift($matches); // On retire la correspondance globale pour ne garder que les paramètres
                
                [$controllerName, $action] = explode('@', $route['controller']);
                $controllerClass = "App\\Controllers\\$controllerName";

                if (class_exists($controllerClass)) {
                    $instance = new $controllerClass();
                    if (method_exists($instance, $action)) {
                        call_user_func_array([$instance, $action], $matches);
                        return;
                    } else {
                        die("Erreur : Méthode <b>$action</b> introuvable dans le contrôleur <b>$controllerName</b>.");
                    }
                } else {
                    die("Erreur : Contrôleur <b>$controllerName</b> introuvable (Vérifiez le namespace ou le nom du fichier).");
                }
            }
        }

        // Si on arrive ici, aucune route n'a fonctionné
        echo "<div style='text-align:center; padding:50px; font-family:sans-serif;'>";
        echo "<h1 style='color:red;'>404 Not Found</h1>";
        echo "<p>Désolé, l'URL demandée <b>$uri</b> n'existe pas.</p>";
        echo "<p><i>Debug Info: ScriptName: $scriptName</i></p>";
        echo "</div>";
    }
}