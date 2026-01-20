<?php
namespace Core;

class Router {
    protected $routes = [];

    public function add($method, $uri, $controller) {
        $uriRegex = '#^' . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $uri) . '$#';
        $this->routes[] = [
            'method' => $method,
            'uri' => $uriRegex,
            'controller' => $controller
        ];
    }

    public function get($uri, $controller) { $this->add('GET', $uri, $controller); }
    public function post($uri, $controller) { $this->add('POST', $uri, $controller); }

    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // 1. Récupérer le dossier du script (ex: /exotikha/public)
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        
        // 2. Normaliser les slashes (Windows met des \, on veut des /)
        $scriptName = str_replace('\\', '/', $scriptName);
        
        // 3. NETTOYAGE ROBUSTE (INSENSIBLE À LA CASSE)
        // On vérifie si l'URI commence par le dossier du script (peu importe majuscule/minuscule)
        if (stripos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }
        
        // 4. Nettoyage final
        $uri = '/' . trim($uri, '/');

        $method = $_SERVER['REQUEST_METHOD'];

        // 5. Recherche de la route
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['uri'], $uri, $matches)) {
                array_shift($matches);
                
                [$controllerName, $action] = explode('@', $route['controller']);
                $controllerClass = "App\\Controllers\\$controllerName";

                if (class_exists($controllerClass)) {
                    $instance = new $controllerClass();
                    if (method_exists($instance, $action)) {
                        call_user_func_array([$instance, $action], $matches);
                        return;
                    }
                }
            }
        }

        // 404
        echo "<div style='font-family:sans-serif; text-align:center; padding:50px;'>";
        echo "<h1>404 Not Found</h1>";
        echo "<p>L'URL demandée <b>$uri</b> n'existe pas.</p>";
        echo "<hr><small>Debug: ScriptName=$scriptName | URI Original=".$_SERVER['REQUEST_URI']."</small>";
        echo "</div>";
    }
}