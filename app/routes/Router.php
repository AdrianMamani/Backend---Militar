<?php
require_once __DIR__ . '/../utils/Response.php';

class Router
{
    private $routes = [];

    public function addRoute($method, $path, $handler)
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'pattern' => $this->createPattern($path),
            'handler' => $handler
        ];
    }

    private function createPattern($path)
    {

        $normalizedPath = $path[0] !== '/' ? '/' . $path : $path;
        $pattern = preg_replace('/:\w+/', '([^/]+)', $normalizedPath);
        return "#^" . $pattern . "$#";
    }

    public function handleRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Eliminar el prefijo "/Backend---Militar/public" de la URI
        $basePath = '/Backend---Militar/public';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        /*
        echo "Método: $method\n";
        echo "URI procesada: $uri\n";
        echo "Rutas registradas:\n";
        print_r($this->routes);
        */

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
