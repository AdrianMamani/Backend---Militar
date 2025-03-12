<?php
require_once __DIR__ . '/../model/Interfaz.php';

/**
 * Controlador para la gestión de la interfaz.
 */
class InterfazController
{
    /**
     * Instancia del modelo Interfaz.
     * @var InterfazModel
     */
    private $model;

    /**
     * Constructor de la clase.
     * 
     * @param Database $db Instancia de la base de datos.
     */
    public function __construct(Database $db)
    {
        $this->model = new InterfazModel($db);
    }

     /**
     * Sube un archivo a la galería y lo guarda en la base de datos.
     * 
     * @return void
     */
    public function subirArchivo()
    {
        header("Content-Type: application/json");

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Método no permitido'], JSON_PRETTY_PRINT);
            http_response_code(405);
            return;
        }

        if (empty($_FILES['archivo']) || empty($_POST['tipo_archivo'])) {
            echo json_encode(['error' => 'Faltan datos'], JSON_PRETTY_PRINT);
            http_response_code(400);
            return;
        }

        $tipo_archivo = $_POST['tipo_archivo'];
        $informacion = $_POST['informacion'] ?? '';

        // Definir carpeta para guardar archivos
        $directorio = __DIR__ . '/../../galeria';

        // Crear carpeta si no existe
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        // Definir la ruta completa del archivo
        $ruta_archivo = $directorio . '/' . basename($_FILES['archivo']['name']);

        // Mover archivo a la carpeta
        if (!move_uploaded_file($_FILES['archivo']['tmp_name'], $ruta_archivo)) {
            echo json_encode(['error' => 'Error al subir archivo'], JSON_PRETTY_PRINT);
            http_response_code(500);
            return;
        }

        // Guardar en la base de datos
        $resultado = $this->model->subirGaleria($tipo_archivo, $ruta_archivo, $informacion);
        echo json_encode(['success' => $resultado, 'ruta' => $ruta_archivo], JSON_PRETTY_PRINT);
    }

    /**
     * Actualiza la información de una promoción en la base de datos.
     * 
     * @return void
     */
    public function actualizarInformacion()
    {
        header("Content-Type: application/json");

        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            echo json_encode(['error' => 'Método no permitido'], JSON_PRETTY_PRINT);
            http_response_code(405);
            return;
        }

        $datos = json_decode(file_get_contents("php://input"), true);
        if (!isset($datos['id_promocion'], $datos['descripcion'], $datos['resenia'])) {
            echo json_encode(['error' => 'Faltan datos'], JSON_PRETTY_PRINT);
            http_response_code(400);
            return;
        }

        $resultado = $this->model->actualizarInformacion($datos['id_promocion'], $datos);
        echo json_encode(['success' => $resultado], JSON_PRETTY_PRINT);
    }
}

// // Llamar automáticamente la función correcta según el método HTTP
// $controller = new InterfazController();

// switch ($_SERVER['REQUEST_METHOD']) {
//     case 'POST':
//         $controller->subirArchivo();
//         break;
//     case 'PUT':
//         $controller->actualizarInformacion();
//         break;
//     default:
//         echo json_encode(['error' => 'Método no soportado'], JSON_PRETTY_PRINT);
//         http_response_code(405);
//         break;
// }
