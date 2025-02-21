<?php

require_once __DIR__ . '/../controller/UserController.php';

if (!isset($GLOBALS['router'])) {
    die("Error: El enrutador no está inicializado.");
}

$router = $GLOBALS['router']; // Obtiene la instancia global


// Instancia del controlador con la base de datos
$userController = new UserController(new Database());

// Rutas de autenticación
$router->addRoute('POST', '/auth/login', [$userController, 'login']);
$router->addRoute('POST', '/auth/register', [$userController, 'register']);
$router->addRoute('POST', '/auth/logout', [$userController, 'logout']);

// Rutas de usuarios
$router->addRoute('GET', '/users', [$userController, 'getAll']);
$router->addRoute('GET', '/users/:id', [$userController, 'getById']);
$router->addRoute('POST', '/users', [$userController, 'create']);
$router->addRoute('PUT', '/users/:id', [$userController, 'update']);
$router->addRoute('DELETE', '/users/:id', [$userController, 'delete']);

?>
