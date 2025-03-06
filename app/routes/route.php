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
require_once __DIR__ . '/../controller/CategoriaController.php';
require_once __DIR__ . '/../controller/TesoreroController.php';
require_once __DIR__ . '/../controller/InMemoriamController.php';
require_once __DIR__ . '/../controller/InterfazController.php';




if (!isset($GLOBALS['router'])) {
    die("Error: El enrutador no está inicializado.");
}

$router = $GLOBALS['router']; // Obtiene la instancia global
$db = new Database();

// Instancia del controlador con la base de datos

$userController = new UserController($db);
$miembroController = new MiembroController($db);
$logroController = new LogroController($db);
$contactoController = new ContactoController($db);
$personaController = new PersonaController($db);
$eventoPersonaController = new EventoPersonaController($db);
$eventoController = new EventoController($db);
$eventoMiembroController = new EventoMiembroController($db);
$noticiaController = new NoticiaController($db);
$noticiaPersonaController = new NoticiaPersonaController($db);
$miembrosLogrosController = new MiembrosLogrosController($db);
$userController = new UserController($db);
$categoriaController  = new CategoriaController($db);
$inMemoriamController = new InMemoriamController($db);
$interfazController = new InterfazController($db);



// -----------------------
// Rutas para Authenticacion
// -----------------------

$router->addRoute('POST', '/auth/login', [$userController, 'login']);
$router->addRoute('POST', '/auth/register', [$userController, 'register']);
$router->addRoute('POST', '/auth/logout', [$userController, 'logout']);

// -----------------------
// Rutas para Usuarios
// -----------------------
$router->addRoute('GET', '/users', [$userController, 'getAll'],true);
$router->addRoute('GET', '/users/:id', [$userController, 'getById'],true);
$router->addRoute('POST', '/users', [$userController, 'create'],true);
$router->addRoute('PUT', '/users/:id', [$userController, 'update'],true);
$router->addRoute('DELETE', '/users/:id', [$userController, 'delete'],true);


// Ruta de Miembros
$router->addRoute('GET', '/miembros/total/:estado', [$miembroController, 'totalCountMiembro']);
$router->addRoute('GET', '/miembros', [$miembroController, 'listAll'],true);
$router->addRoute('GET', '/miembros/:id', [$miembroController, 'getById'],true);
$router->addRoute('PUT', '/miembros/:id', [$miembroController, 'updateById'],true);
$router->addRoute('DELETE', '/miembros/:id', [$miembroController, 'deleteById'],true);

// Ruta de Logros
$router->addRoute('GET', '/logros', [$logroController, 'listLogros'],true);
$router->addRoute('GET', '/logros/total', [$logroController, 'countTotalLogro'],true);
$router->addRoute('POST', '/logros', [$logroController, 'createLogro'],true);
$router->addRoute('PUT', '/logros/:id', [$logroController, 'update'],true);
$router->addRoute('DELETE', '/logros/:id', [$logroController, 'deleteById'],true);


// Ruta de Contactos
$router->addRoute('GET', '/contacto', [$contactoController, 'listContactos'],true);
$router->addRoute('GET', '/contacto/:id', [$contactoController, 'getById'],true);
$router->addRoute('POST', '/contacto', [$contactoController, 'createContacto'],true);
$router->addRoute('PUT', '/contacto/:id', [$contactoController, 'update'],true);
$router->addRoute('DELETE', '/contacto/:id', [$contactoController, 'deleteById'],true);

// Ruta de Personas
$router->addRoute('GET', '/persona', [$personaController, 'listPersonas'],true);
$router->addRoute('GET', '/persona/:id', [$personaController, 'getById'],true);
$router->addRoute('POST', '/persona', [$personaController, 'createPersona'],true);
$router->addRoute('PUT', '/persona/:id', [$personaController, 'update'],true);
$router->addRoute('DELETE', '/persona/:id', [$personaController, 'deleteById'],true);

// Ruta de EventoPersonas
$router->addRoute('GET', '/evento/persona', [$eventoPersonaController, 'listEventoPersona'],true);
$router->addRoute('GET', '/evento/persona/:id', [$eventoPersonaController, 'getById'],true);
$router->addRoute('POST', '/evento/persona', [$eventoPersonaController, 'createEventoPersona'],true);
$router->addRoute('DELETE', '/evento/persona/:idPersona/:idEvento', [$eventoPersonaController, 'deleteById'],true);

// Ruta de Evento
$router->addRoute('GET', '/evento', [$eventoController, 'listEventos'],true);
$router->addRoute('GET', '/evento/:id', [$eventoController, 'getById'],true);
$router->addRoute('PUT', '/evento/:id', [$eventoController, 'update'],true);
$router->addRoute('DELETE', '/evento/:id', [$eventoController, 'deleteById'],true);

// Ruta de EventoMiembros
$router->addRoute('GET', '/evento/miembro/:id', [$eventoMiembroController, 'getById'],true);
$router->addRoute('POST', '/evento/miembro', [$eventoMiembroController, 'createEventoMiembro'],true);
$router->addRoute('PUT', '/evento/miembro/:idMiembro/:idEvento', [$eventoMiembroController, 'update'],true);
$router->addRoute('DELETE', '/evento/miembro/:idMiembro/:idEvento', [$eventoMiembroController, 'deleteById'],true);

// Ruta de Noticias
$router->addRoute('GET', '/noticia', [$noticiaController, 'listAll'],true);
$router->addRoute('GET', '/noticia/total', [$noticiaController, 'countTotalNoticia'],true);
$router->addRoute('PUT', '/noticia/:id', [$noticiaController, 'update'],true);
$router->addRoute('DELETE', '/noticia/:id', [$noticiaController, 'deleteById'],true);

// Ruta de NoticiaPersonas
$router->addRoute('GET', '/noticia/persona/:id', [$noticiaPersonaController, 'getById'],true);
$router->addRoute('POST', '/noticia/persona', [$noticiaPersonaController, 'createNoticiaPersona'],true);
$router->addRoute('DELETE', '/noticia/persona/:idPersona/:idNoticia', [$noticiaPersonaController, 'deleteById'],true);

// Ruta de MiembrosLogros
$router->addRoute('GET', '/miembros/logros/:id', [$miembrosLogrosController, 'getById'],true);
$router->addRoute('POST', '/miembros/logros', [$miembrosLogrosController, 'createMiembrosLogros'],true);
$router->addRoute('DELETE', '/miembros/logros/:idLogro/:idMiembro', [$miembrosLogrosController, 'deleteById'],true);

// -----------------------
// Rutas para Aportación
// -----------------------
$aportacionController = new AportacionController($db);
$router->addRoute('GET', '/aportaciones', [$aportacionController, 'getAll'],true);
$router->addRoute('GET', '/aportaciones/:id', [$aportacionController, 'getById'],true);
$router->addRoute('POST', '/aportaciones', [$aportacionController, 'create'],true);
$router->addRoute('PUT', '/aportaciones/:id', [$aportacionController, 'update'],true);
$router->addRoute('DELETE', '/aportaciones/:id', [$aportacionController, 'delete'],true);

// -----------------------
// Rutas para Asociado
// -----------------------
$asociadoController = new AsociadoController($db);
$router->addRoute('GET', '/asociados', [$asociadoController, 'getAll'],true);
$router->addRoute('GET', '/asociados/:id', [$asociadoController, 'getById'],true);
$router->addRoute('POST', '/asociados', [$asociadoController, 'create'],true);
$router->addRoute('PUT', '/asociados/:id', [$asociadoController, 'update'],true);
$router->addRoute('DELETE', '/asociados/:id', [$asociadoController, 'delete'],true);

// -----------------------
// Rutas para Balance 
// -----------------------
// falta Crear Tabla
$balanceController = new BalanceController($db);
$router->addRoute('GET', '/balances', [$balanceController, 'getAll'],true);
$router->addRoute('GET', '/balances/:id', [$balanceController, 'getById'],true);
$router->addRoute('POST', '/balances', [$balanceController, 'create'],true);
$router->addRoute('PUT', '/balances/:id', [$balanceController, 'update'],true);
$router->addRoute('DELETE', '/balances/:id', [$balanceController, 'delete'],true);


// -----------------------
// Rutas para Tesorero
// -----------------------
$tesoreroController = new TesoreroController($db);
$router->addRoute('POST', '/tesorero', [$tesoreroController, 'create'],true);
$router->addRoute('GET', '/tesorero', [$tesoreroController, 'getAll'],true);
// -----------------------
// Rutas para Categoria
// -----------------------
$router->addRoute('GET','/categoria', [$categoriaController, 'getAll'],true);
$router->addRoute('POST','/categoria', [$categoriaController, 'create'],true);

// -----------------------
// Ruta de InMemoriam
// -----------------------
$router->addRoute('GET', '/inmemoriam', [$inMemoriamController, 'listAll'], true);
$router->addRoute('GET', '/inmemoriam/:id', [$inMemoriamController, 'getById'], true);
$router->addRoute('POST', '/inmemoriam', [$inMemoriamController, 'create'], true);
$router->addRoute('PUT', '/inmemoriam/:id', [$inMemoriamController, 'update'], true);
$router->addRoute('DELETE', '/inmemoriam/:id', [$inMemoriamController, 'delete'], true);

// -----------------------
// Ruta de Interfaz
// -----------------------
$router->addRoute("POST",'/subirArchivo', [$interfazController, 'subirArchivo']);
$router->addRoute("PUT",'/actualizarInformacion', [$interfazController, 'actualizarInformacion']);
$router->addRoute("PUT",'/actualizarGaleria', [$interfazController, 'actualizarGaleria']);
$router->addRoute("PUT",'/actualizarInformacionVideo', [$interfazController, 'actualizarInformacionVideo']);
$router->addRoute("PUT",'/editarImagenReferencia', [$interfazController, 'editarImagenReferencia']);
