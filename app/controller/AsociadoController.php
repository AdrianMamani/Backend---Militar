<?php
require_once __DIR__ . '/../model/Asociado.php';

class AsociadoController
{
    private Asociado $asociadoModel;

    public function __construct(Database $db)
    {
        $this->asociadoModel = new Asociado($db);
    }

    // GET /asociados
    public function getAll()
    {
        $asociados = $this->asociadoModel->getAll();
        header("Content-Type: application/json");
        echo json_encode($asociados);
    }

    // GET /asociados/{id}
    public function getById($authData, $id)
    {
        $id = intval($id);
        error_log("ID recibido en el controlador: $id");

        $asociado = $this->asociadoModel->getById($id);
        header("Content-Type: application/json");
        if ($asociado) {
            echo json_encode($asociado);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Asociado no encontrado"]);
        }
    }
    // POST /asociados
    public function create()
    {
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

    // PUT /asociados/{id}
    public function update($authData, $id)
    {
        $id = intval($id);
        error_log("ID recibido en el controlador: $id");

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
    // DELETE /asociados/{id}
    public function delete($authData, $id)
    {
        $id = intval($id);
        error_log("ID recibido en el controlador: $id");

        $success = $this->asociadoModel->delete($id);
        header("Content-Type: application/json");
        if ($success) {
            echo json_encode(["message" => "Asociado eliminado"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar el asociado"]);
        }
    }

}
