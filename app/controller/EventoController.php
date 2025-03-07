<?php
require_once __DIR__ . '/../model/Evento.php';

class EventoController
{
    private $eventoModel;

    public function __construct(Database $db)
    {
        $this->eventoModel = new Evento($db);
    }

    public function listEventos()
    {
        $resultEvento = $this->eventoModel->getData();

        if ($resultEvento === null) {
            Response::json(["Message" => "No se encontraron registros en la tabla Evento"]);
        } else {
            Response::json($resultEvento);
        }
    }


    public function getById($authData, $id)
    {
        $id = intval($id);
        error_log("ID recibido en el controlador: $id");

        $result =  $this->eventoModel->getDataById($id);
        if ($result === null) {
            echo json_encode(["message" => "El ID $id No Existe"]);
            return;
        }
        Response::json($result);
    }


    public function update($authData, $id)
    {
        $id = intval($id);
        error_log("ID recibido en el controlador: $id");
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["nombre_evento"], $data["lugar_evento"], $data["fecha_evento"], $data["descripcion"])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $result = $this->eventoModel->putData($id, $data['nombre_evento'], $data['lugar_evento'], $data["fecha_evento"], $data["descripcion"]);

        if ($result === null) {
            Response::json(['error' => "No Existe el Evento con Id $id"], 404);
        }

        if ($result) {
            Response::json(['message' => 'Evento actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar el Evento'], 500);
        }
    }

    public function deleteById($authData, $id)
    {
        $id = intval($id);
        error_log("ID recibido en el controlador: $id");
        $result = $this->eventoModel->deleteData($id);

        if (!$result) {
            Response::json(["Message" => "El Evento con ID $id No Existe"]);
        }

        if ($result === null) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $id"]);
        }
        Response::json(["message" => "Evento con ID $id Eliminado Correctamente"]);
    }
}
