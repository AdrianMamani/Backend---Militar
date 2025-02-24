<?php
require_once __DIR__ . '/../model/Aportacion.php';
require_once __DIR__ . '/../model/Asociado.php';
class AportacionController {
    private $aportacionModel;
    private $asociadoModel;

    public function __construct(Database $db) {
        $this->aportacionModel = new Aportacion($db);
        $this->asociadoModel = new Asociado($db);
    }

    // GET /aportaciones
    public function getAll() {
        $aportaciones = $this->aportacionModel->getAll();
        header("Content-Type: application/json");
        echo json_encode($aportaciones);
    }

    // GET /aportaciones/{id}
    public function getById($id) {
        $aportacion = $this->aportacionModel->getById($id);
        header("Content-Type: application/json");
        if ($aportacion) {
            echo json_encode($aportacion);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Aportación no encontrada"]);
        }
    }
    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);

        // Validar que existan los datos para el asociado y la aportación
        if (!isset($data['asociado']) || !isset($data['aportacion'])) {
            Response::json(['error' => 'Faltan datos de asociado o aportación'], 400);
            return;
        }

        // Validar datos del asociado
        $asociadoData = $data['asociado'];
        if (
            !isset($asociadoData['nombre_completo']) ||
            !isset($asociadoData['lugar']) ||
            !isset($asociadoData['fecha_creacion']) ||
            !isset($asociadoData['fecha_modificacion'])
        ) {
            Response::json(['error' => 'Faltan datos requeridos para el asociado'], 400);
            return;
        }

        // Crear el asociado y obtener su ID
        $id_asociado = $this->asociadoModel->create(
            $asociadoData['nombre_completo'],
            $asociadoData['lugar'],
            $asociadoData['fecha_creacion'],
            $asociadoData['fecha_modificacion']
        );
        if (!$id_asociado) {
            Response::json(['error' => 'Error al crear el asociado'], 500);
            return;
        }

        // Validar datos de la aportación
        $aportacionData = $data['aportacion'];
        if (
            !isset($aportacionData['id_categoria']) ||
            !isset($aportacionData['id_tesorero']) ||
            !isset($aportacionData['montos']) ||
            !isset($aportacionData['total']) ||
            !isset($aportacionData['fecha_creacion']) ||
            !isset($aportacionData['fecha_modificacion'])
        ) {
            Response::json(['error' => 'Faltan datos requeridos para la aportación'], 400);
            return;
        }

        // Crear la aportación y obtener su ID
        $id_aportacion = $this->aportacionModel->create(
            $aportacionData['id_categoria'],
            $aportacionData['id_tesorero'],
            $aportacionData['montos'],
            $aportacionData['total'],
            $aportacionData['fecha_creacion'],
            $aportacionData['fecha_modificacion']
        );
        if (!$id_aportacion) {
            Response::json(['error' => 'Error al crear la aportación'], 500);
            return;
        }

        // Asociar el aportación con el asociado
        $association = $this->asociadoModel->associateAportacion($id_asociado, $id_aportacion);
        if (!$association) {
            Response::json(['error' => 'Error al asociar la aportación con el asociado'], 500);
            return;
        }

        Response::json([
            'message' => 'Aportación y asociado creados y asociados exitosamente',
            'id_asociado' => $id_asociado,
            'id_aportacion' => $id_aportacion
        ]);
    }

    // PUT/PATCH /aportaciones/{id}
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['id_categoria'], $data['id_tesorero'], $data['montos'], $data['lugar'], $data['total'])) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos"]);
            return;
        }
        $success = $this->aportacionModel->update(
            $id,
            $data['id_categoria'],
            $data['id_tesorero'],
            $data['montos'],
            $data['lugar'],
            $data['total']
        );
        header("Content-Type: application/json");
        if ($success) {
            echo json_encode(["message" => "Aportación actualizada"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar la aportación"]);
        }
    }

    // DELETE /aportaciones/{id}
    public function delete($id) {
        $success = $this->aportacionModel->delete($id);
        header("Content-Type: application/json");
        if ($success) {
            echo json_encode(["message" => "Aportación eliminada"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar la aportación"]);
        }
    }
}
?>
