<?php
require_once __DIR__ . '/../model/Asociado.php';

class AsociadoController {
    private Asociado $asociadoModel;

    public function __construct(Database $db) {
        $this->asociadoModel = new Asociado($db);
    }

    // GET /asociados
    public function getAll() {
        $asociados = $this->asociadoModel->getAll();
        header("Content-Type: application/json");
        echo json_encode($asociados);
    }

    // GET /asociados/{id}
    public function getById($id) {
        $asociado = $this->asociadoModel->getById($id);
        header("Content-Type: application/json");
        if ($asociado) {
            echo json_encode($asociado);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Asociado no encontrado"]);
        }
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['nombre_completo'], $data['lugar'], $data['fecha_creacion'], $data['fecha_modificacion'])) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos"]);
            return;
        }
        $insertId = $this->asociadoModel->create(
            $data['nombre_completo'],
            $data['lugar'],
            $data['fecha_creacion'],
            $data['fecha_modificacion']
        );
        header("Content-Type: application/json");
        if ($insertId) {
            http_response_code(201);
            echo json_encode(["message" => "Asociado creado", "id" => $insertId]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al crear el asociado"]);
        }
    }

    // PUT/PATCH /asociados/{id}
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['nombre_completo'], $data['lugar'], $data['fecha_creacion'], $data['fecha_modificacion'])) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos"]);
            return;
        }
        $success = $this->asociadoModel->update(
            $id,
            $data['nombre_completo'],
            $data['lugar'],
            $data['fecha_creacion'],
            $data['fecha_modificacion']
        );
        header("Content-Type: application/json");
        if ($success) {
            echo json_encode(["message" => "Asociado actualizado"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar el asociado"]);
        }
    }

    public function delete($id) {
        $success = $this->asociadoModel->delete($id);
        header("Content-Type: application/json");
        if ($success) {
            echo json_encode(["message" => "Asociado eliminado"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar el asociado"]);
        }
    }

    // POST /asociados/associate
    // Para asociar una aportación a un asociado
    public function associateAportacion() {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['id_asociado'], $data['id_aportacion'])) {
            http_response_code(400);
            echo json_encode(["error" => "Faltan datos requeridos para la asociación"]);
            return;
        }
        $success = $this->asociadoModel->associateAportacion($data['id_asociado'], $data['id_aportacion']);
        header("Content-Type: application/json");
        if ($success) {
            echo json_encode(["message" => "Aportación asociada al asociado"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al asociar la aportación"]);
        }
    }
}
?>
