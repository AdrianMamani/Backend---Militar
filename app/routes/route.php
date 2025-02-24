<?php
require_once __DIR__ . '/../controller/AportacionController.php';
require_once __DIR__ . '/../controller/AsociadoController.php';
require_once __DIR__ . '/../controller/BalanceController.php';
require_once __DIR__ . '/../controller/UserController.php';

require_once __DIR__ . '/../controller/MiembroController.php';
require_once __DIR__ . '/../controller/LogroController.php';
require_once __DIR__ . '/../controller/ContactoController.php';
require_once __DIR__ . '/../controller/PersonaController.php';
require_once __DIR__ . '/../controller/EventoPersonaController.php';
require_once __DIR__ . '/../controller/EventoController.php';
require_once __DIR__ . '/../controller/EventoMiembroController.php';
require_once __DIR__ . '/../controller/NoticiaController.php';
require_once __DIR__ . '/../controller/NoticiaPersonaController.php';
require_once __DIR__ . '/../controller/MiembrosLogrosController.php';

require_once __DIR__ . '/../controller/TesoreroController.php';


if (!isset($GLOBALS['router'])) {
    die("Error: El enrutador no está inicializado.");
}

$router = $GLOBALS['router']; // Obtiene la instancia global
$db = new Database();

// Instancia del controlador con la base de datos

$userController = new UserController(new Database());
$miembroController = new MiembroController(new Database());
$logroController = new LogroController(new Database());
$contactoController = new ContactoController(new Database());
$personaController = new PersonaController(new Database());
$eventoPersonaController = new EventoPersonaController(new Database());
$eventoController = new EventoController(new Database());
$eventoMiembroController = new EventoMiembroController(new Database());
$noticiaController = new NoticiaController(new Database());
$noticiaPersonaController = new NoticiaPersonaController(new Database());
$miembrosLogrosController = new MiembrosLogrosController(new Database());
// Rutas de autenticación
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


// Ruta de Miembros
$router->addRoute('GET', '/miembros/:estado', [$miembroController, 'totalCountMiembro']);
$router->addRoute('GET', '/miembros', [$miembroController, 'listAll']);
$router->addRoute('GET', '/miembros/:id', [$miembroController, 'getById']);
$router->addRoute('PUT', '/miembros/:id', [$miembroController, 'updateById']);
$router->addRoute('DELETE', '/miembros/:id', [$miembroController, 'deleteById']);

// Ruta de Logros
$router->addRoute('GET', '/logros', [$logroController, 'listLogros']);
$router->addRoute('POST', '/logros', [$logroController, 'createLogro']);
$router->addRoute('PUT', '/logros/:id', [$logroController, 'update']);
$router->addRoute('DELETE', '/logros/:id', [$logroController, 'deleteById']);


// Ruta de Contactos
$router->addRoute('GET', '/contacto/:id', [$contactoController, 'getById']);
$router->addRoute('POST', '/contacto', [$contactoController, 'createContacto']);
$router->addRoute('PUT', '/contacto/:id', [$contactoController, 'update']);

// Ruta de Personas
$router->addRoute('GET', '/persona', [$personaController, 'listPersonas']);
$router->addRoute('GET', '/persona/:id', [$personaController, 'getById']);
$router->addRoute('POST', '/persona', [$personaController, 'createPersona']);
$router->addRoute('PUT', '/persona/:id', [$personaController, 'update']);
$router->addRoute('DELETE', '/persona/:id', [$personaController, 'deleteById']);

// Ruta de EventoPersonas
$router->addRoute('GET', '/evento/persona', [$eventoPersonaController, 'listEventoPersona']);
$router->addRoute('GET', '/evento/persona/:id', [$eventoPersonaController, 'getById']);
$router->addRoute('POST', '/evento/persona', [$eventoPersonaController, 'createEventoPersona']);
$router->addRoute('DELETE', '/evento/persona/:idPersona/:idEvento', [$eventoPersonaController, 'deleteById']);

// Ruta de Evento
$router->addRoute('GET', '/evento', [$eventoController, 'listEventos']);
$router->addRoute('GET', '/evento/:id', [$eventoController, 'getById']);
$router->addRoute('PUT', '/evento/:id', [$eventoController, 'update']);
$router->addRoute('DELETE', '/evento/:id', [$eventoController, 'deleteById']);

// Ruta de EventoMiembros
$router->addRoute('GET', '/evento/miembro/:id', [$eventoMiembroController, 'getById']);
$router->addRoute('POST', '/evento/miembro', [$eventoMiembroController, 'createEventoMiembro']);
$router->addRoute('PUT', '/evento/miembro/:idMiembro/:idEvento', [$eventoMiembroController, 'update']);
$router->addRoute('DELETE', '/evento/miembro/:idMiembro/:idEvento', [$eventoMiembroController, 'deleteById']);

// Ruta de Noticias
$router->addRoute('GET', '/noticia', [$noticiaController, 'listAll']);
$router->addRoute('PUT', '/noticia/:id', [$noticiaController, 'update']);
$router->addRoute('DELETE', '/noticia/:id', [$noticiaController, 'deleteById']);

// Ruta de NoticiaPersonas
$router->addRoute('GET', '/noticia/persona/:id', [$noticiaPersonaController, 'getById']);
$router->addRoute('POST', '/noticia/persona', [$noticiaPersonaController, 'createNoticiaPersona']);
$router->addRoute('DELETE', '/noticia/persona/:idPersona/:idNoticia', [$noticiaPersonaController, 'deleteById']);

// Ruta de MiembrosLogros
$router->addRoute('GET', '/miembros/logros/:id', [$miembrosLogrosController, 'getById']);
$router->addRoute('POST', '/miembros/logros', [$miembrosLogrosController, 'createMiembrosLogros']);
$router->addRoute('DELETE', '/miembros/logros/:idLogro/:idMiembro', [$miembrosLogrosController, 'deleteById']);

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
$router->addRoute('POST', '/tesorero', [$tesoreroController, 'create']);
$router->addRoute('GET', '/tesorero', [$tesoreroController, 'getAll']);
