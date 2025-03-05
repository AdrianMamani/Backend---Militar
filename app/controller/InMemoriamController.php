<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../model/InMemoriam.php';

header("Content-Type: application/json");
$db = new Database();
$inMemoriam = new InMemoriam($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $data = $inMemoriam->getById($id);
        } else {
            $data = $inMemoriam->getAll();
        }
        echo json_encode($data);
        break;

    case 'POST':
        if (!isset($_POST['nombre_miembro'], $_POST['fecha_fallecimiento'], $_POST['descripcion'])) {
            echo json_encode(["error" => "Faltan datos requeridos"]);
            http_response_code(400);
            exit;
        }

        $nombre_miembro = $_POST['nombre_miembro'];
        $fecha_fallecimiento = $_POST['fecha_fallecimiento'];
        $descripcion = $_POST['descripcion'];
        $logros = isset($_POST['logros']) ? json_decode($_POST['logros'], true) : [];

        // Manejo de imagen
        $imagenRuta = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../galeria/'; // Ruta correcta

            // Crear la carpeta si no existe
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $imagenNombre = time() . '-' . basename($_FILES['imagen']['name']); // Evitar nombres duplicados
            $imagenRutaCompleta = $uploadDir . $imagenNombre;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $imagenRutaCompleta)) {
                $imagenRuta = "/galeria/" . $imagenNombre; // Ruta accesible desde el navegador
            } else {
                echo json_encode(["error" => "Error al mover la imagen"]);
                http_response_code(500);
                exit;
            }
        }

        $result = $inMemoriam->create($nombre_miembro, $fecha_fallecimiento, $descripcion, $imagenRuta, $logros);
        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Registro creado exitosamente",
                "data" => [
                    "nombre_miembro" => $nombre_miembro,
                    "fecha_fallecimiento" => $fecha_fallecimiento,
                    "descripcion" => $descripcion,
                    "imagen" => $imagenRuta,
                    "logros" => $logros
                ]
            ]);
            exit;
        } else {
            echo json_encode(["error" => "No se pudo crear el registro"]);
            http_response_code(500);
        }

    case 'PUT':
        $input = json_decode(file_get_contents("php://input"), true);
        if (!$input || !isset($input['id'], $input['nombre_miembro'], $input['fecha_fallecimiento'], $input['descripcion'], $input['imagen'])) {
            echo json_encode(["error" => "Faltan datos requeridos"]);
            http_response_code(400);
            exit;
        }

        $id = intval($input['id']);
        $nombre_miembro = $input['nombre_miembro'];
        $fecha_fallecimiento = $input['fecha_fallecimiento'];
        $descripcion = $input['descripcion'];
        $imagen = $input['imagen'];

        $result = $inMemoriam->update($id, $nombre_miembro, $fecha_fallecimiento, $descripcion, $imagen);
        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Registro actualizado exitosamente",
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Error al actualizar el registro",
            ]);
        }
        exit;
        break;

    case 'DELETE':
        $input = json_decode(file_get_contents("php://input"), true);
        if (!$input || !isset($input['id'])) {
            echo json_encode(["error" => "ID requerido"]);
            http_response_code(400);
            exit;
        }

        $id = intval($input['id']);
        $result = $inMemoriam->delete($id);
        if ($result) {
            echo json_encode([
                "success" => true,
                "message" => "Registro eliminado correctamente",
            ]);
        } else {
            echo json_encode(["error" => "No se pudo eliminar el registro"]);
            http_response_code(500);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
