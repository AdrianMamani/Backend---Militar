<?php
require_once __DIR__ . '/../utils/Response.php';

class Router {   
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => $this->createPattern($path),
            'handler' => $handler
        ];
    }

    private function createPattern($path) {
        $basePath = '/api';

        $normalizedPath = $path[0] !== '/' ? '/' . $path : $path;
        $fullPath = $basePath . $normalizedPath;

        // Reemplaza ":id" por una expresión regular
        $pattern = preg_replace('/:\w+/', '([^/]+)', $fullPath);
        return "#^" . $pattern . "$#";
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); 
                return call_user_func_array($route['handler'], $matches);
            }
        }

        // Si no se encontró la ruta, responde con error 404.
        Response::json(['error' => 'Ruta no encontrada'], 404);
    }
}
?>
