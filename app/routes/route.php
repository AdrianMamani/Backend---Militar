<?php
require_once __DIR__ . '/../controller/AportacionController.php';
require_once __DIR__ . '/../controller/AsociadoController.php';
require_once __DIR__ . '/../controller/BalanceController.php';
require_once __DIR__ . '/../controller/UserController.php';
require_once __DIR__ . '/../controller/TesoreroController.php';

if (!isset($GLOBALS['router'])) {
    die("Error: El enrutador no está inicializado.");
}

$router = $GLOBALS['router']; // Obtiene la instancia global
$db = new Database();

// Instancia del controlador con la base de datos
$userController = new UserController($db);

// -----------------------
// Rutas para Authenticacion
// -----------------------
$router->addRoute('POST', '/auth/login', [$userController, 'login']);
$router->addRoute('POST', '/auth/register', [$userController, 'register']);
$router->addRoute('POST', '/auth/logout', [$userController, 'logout']);

// -----------------------
// Rutas para Usuarios
// -----------------------
$router->addRoute('GET', '/users', [$userController, 'getAll']);
$router->addRoute('GET', '/users/:id', [$userController, 'getById']);
$router->addRoute('POST', '/users', [$userController, 'create']);
$router->addRoute('PUT', '/users/:id', [$userController, 'update']);
$router->addRoute('DELETE', '/users/:id', [$userController, 'delete']);

// -----------------------
// Rutas para Aportación
// -----------------------
$aportacionController = new AportacionController($db);
$router->addRoute('GET', '/aportaciones', [$aportacionController, 'getAll']);
$router->addRoute('GET', '/aportaciones/:id', [$aportacionController, 'getById']);
$router->addRoute('POST', '/aportaciones', [$aportacionController, 'create']);
$router->addRoute('PUT', '/aportaciones/:id', [$aportacionController, 'update']);
$router->addRoute('DELETE', '/aportaciones/:id', [$aportacionController, 'delete']);

// -----------------------
// Rutas para Asociado
// -----------------------
$asociadoController = new AsociadoController($db);
$router->addRoute('GET', '/asociados', [$asociadoController, 'getAll']);
$router->addRoute('GET', '/asociados/:id', [$asociadoController, 'getById']);
$router->addRoute('POST', '/asociados', [$asociadoController, 'create']);
$router->addRoute('PUT', '/asociados/:id', [$asociadoController, 'update']);
$router->addRoute('DELETE', '/asociados/:id', [$asociadoController, 'delete']);

// -----------------------
// Rutas para Balance 
// -----------------------
// falta Crear Tabla
$balanceController = new BalanceController($db);
$router->addRoute('GET', '/balances', [$balanceController, 'getAll']);
$router->addRoute('GET', '/balances/:id', [$balanceController, 'getById']);
$router->addRoute('POST', '/balances', [$balanceController, 'create']);
$router->addRoute('PUT', '/balances/:id', [$balanceController, 'update']);
$router->addRoute('DELETE', '/balances/:id', [$balanceController, 'delete']);


// -----------------------
// Rutas para Tesorero
// -----------------------
$tesoreroController = new TesoreroController($db);
$router-> addRoute('POST','/tesorero',[$tesoreroController, 'create']);
$router-> addRoute('GET','/tesorero',[$tesoreroController,'getAll']);