<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/utils/Response.php';
require_once __DIR__ . '/../app/routes/Router.php';
require_once __DIR__ . '/../app/controller/UserController.php';
require_once __DIR__ . '/../app/controller/MiembroController.php';

// Instancias
$db = new Database();
$router = new Router();

$GLOBALS['router'] = $router; // Guardar en $GLOBALS

$userController = new UserController($db);
$miembroController = new MiembroController($db);

// Cargar rutas
require_once '../app/routes/route.php';

try {
    $router->handleRequest();
} catch (Exception $e) {
    Response::json([
        'error' => 'Error del servidor',
        'message' => $e->getMessage()
    ], 500);
}
