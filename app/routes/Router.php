<?php
require_once __DIR__ . '/../utils/Response.php';
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';
class Router
{
    private $routes = [];

    public function addRoute($method, $path, $handler, $protected = false)
    {
        $this->routes[] = [
            'method'    => strtoupper($method),
            'pattern'   => $this->createPattern($path),
            'handler'   => $handler,
            'protected' => $protected
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

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);
                // Si la ruta está protegida, se ejecuta el middleware para validar el token
                if ($route['protected']) {
                    $userData = AuthMiddleware::handle();
                    array_unshift($matches, $userData);
                }
                return call_user_func_array($route['handler'], $matches);
            }
        }

        // Si no se encontró la ruta, responde con error 404.
        Response::json(['error' => 'Ruta no encontrada'], 404);
    }
}
