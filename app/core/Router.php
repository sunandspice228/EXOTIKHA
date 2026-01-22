<?php
namespace Core;

class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch($uri) {
        // 1. Nettoyage de l'URL (Gestion WAMP / Sous-dossiers)
        $uri = parse_url($uri, PHP_URL_PATH);
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        
        if ($scriptName !== '/' && $scriptName !== '\\') {
            $uri = str_ireplace($scriptName, '', $uri);
        }
        $uri = '/' . trim($uri, '/');
        
        $method = $_SERVER['REQUEST_METHOD'];

        // 2. Recherche Correspondance EXACTE (Rapide)
        if (isset($this->routes[$method][$uri])) {
            $this->execute($this->routes[$method][$uri]);
            return;
        }

        // 3. Recherche DYNAMIQUE (ex: /product/{id})
        foreach ($this->routes[$method] as $route => $callback) {
            // Si la route contient des accolades {}
            if (strpos($route, '{') !== false) {
                // On transforme /product/{id} en Regex : #^/product/([^/]+)$#
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
                $pattern = "#^" . $pattern . "$#";

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // On enlève la correspondance globale
                    $this->execute($callback, $matches); // On passe les arguments (l'ID)
                    return;
                }
            }
        }

        // 4. Si rien trouvé -> 404
        $this->handle404($uri, $scriptName);
    }

    // Exécution du Contrôleur
    private function execute($callback, $params = []) {
        if (is_callable($callback)) {
            call_user_func_array($callback, $params);
            return;
        }

        if (is_string($callback)) {
            $parts = explode('@', $callback);
            $controllerName = "App\\Controllers\\" . $parts[0];
            $methodName = $parts[1];

            if (class_exists($controllerName)) {
                $controller = new $controllerName();
                if (method_exists($controller, $methodName)) {
                    call_user_func_array([$controller, $methodName], $params);
                    return;
                }
            }
        }
        
        die("Erreur Configuration Route : Contrôleur ou Méthode introuvable ($callback)");
    }

    private function handle404($uri, $base) {
        http_response_code(404);
        echo "<div style='font-family:sans-serif; text-align:center; padding:50px;'>";
        echo "<h1>404 Not Found</h1>";
        echo "<p>L'URL demandée <strong>$uri</strong> n'existe pas.</p>";
        echo "<hr><p style='font-size:12px; color:#777'>Debug: BaseFolder=<strong>$base</strong> | Route reçue=<strong>$uri</strong></p>";
        echo "<a href='" . (defined('ROOT_URL') ? ROOT_URL : '/') . "'>Retour à l'accueil</a>";
        echo "</div>";
    }
}