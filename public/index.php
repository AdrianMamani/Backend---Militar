<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../app/utils/Response.php';
require_once __DIR__ . '/../app/routes/Router.php';

// controladores
require_once __DIR__ . '/../app/controller/UserController.php';
require_once __DIR__ . '/../app/controller/AportacionController.php';
require_once __DIR__ . '/../app/controller/AsociadoController.php';
require_once __DIR__ . '/../app/controller/BalanceController.php';
require_once __DIR__ . '/../app/controller/TesoreroController.php';
require_once __DIR__ . '/../app/controller/MiembroController.php';
$router = new Router();

// Asignar el router a una variable global para que sea accesible en el archivo de rutas
$GLOBALS['router'] = $router;

// Cargar las rutas definidas
require_once __DIR__ . '/../app/routes/route.php';


try {
    // Maneja la solicitud entrante según las rutas configuradas
    $router->handleRequest();
} catch (Exception $e) {
    // En caso de error
    Response::json([
        'error' => 'Error del servidor',
        'message' => $e->getMessage()
    ], 500);
}
