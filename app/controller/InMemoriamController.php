<?php
require_once __DIR__ . '/../model/InMemoriam.php';
require_once __DIR__ . '/../utils/Response.php';

class InMemoriamController
{
    private $model;

    public function __construct(Database $db)
    {
        $this->model = new InMemoriam($db);
    }

    // Obtener todos los registros con logros e imágenes
    public function getAll()
    {
        $data = $this->model->getData();
        
        if (empty($data)) {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron registros en InMemoriam."]);
            return;
        }

        http_response_code(200);
        echo json_encode($data);
    }

    // Obtener un registro por ID con logros e imágenes
    public function getById($id)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID inválido."]);
            return;
        }

        $data = $this->model->getById($id);

        if (empty($data)) {
            http_response_code(404);
            echo json_encode(["message" => "Registro no encontrado."]);
            return;
        }

        http_response_code(200);
        echo json_encode($data);
    }

    // Agregar un nuevo registro
    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['id_miembro'], $data['fecha_fallecimiento'], $data['descripcion'])) {
            http_response_code(400);
            echo json_encode(["error" => "Datos incompletos."]);
            return;
        }

        $result = $this->model->postData($data['id_miembro'], $data['fecha_fallecimiento'], $data['descripcion']);

        if (!$result) {
            http_response_code(500);
            echo json_encode(["error" => "Error al insertar el registro."]);
            return;
        }

        http_response_code(201);
        echo json_encode(["message" => "Registro agregado con éxito."]);
    }

    // Actualizar un registro
    public function update($id)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID inválido."]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['fecha_fallecimiento'], $data['descripcion'])) {
            http_response_code(400);
            echo json_encode(["error" => "Datos incompletos."]);
            return;
        }

        $result = $this->model->updateData($id, $data['fecha_fallecimiento'], $data['descripcion']);

        if ($result === null) {
            http_response_code(404);
            echo json_encode(["error" => "No se encontró el registro para actualizar."]);
            return;
        }

        if (!$result) {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar el registro."]);
            return;
        }

        http_response_code(200);
        echo json_encode(["message" => "Registro actualizado con éxito."]);
    }

    // Eliminar un registro
    public function delete($id)
    {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["error" => "ID inválido."]);
            return;
        }

        $result = $this->model->deleteData($id);

        if ($result === null) {
            http_response_code(404);
            echo json_encode(["error" => "No se encontró el registro para eliminar."]);
            return;
        }

        if (!$result) {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar el registro."]);
            return;
        }

        http_response_code(200);
        echo json_encode(["message" => "Registro eliminado con éxito."]);
    }
}
?>
