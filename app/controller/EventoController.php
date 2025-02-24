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


    public function getById($id)
    {
        $result =  $this->eventoModel->getDataById($id);
        if ($result === null) {
            echo json_encode(["message" => "El ID $id No Existe"]);
        }
        Response::json($result);
    }


    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["nombreEvento"], $data["lugarEvento"], $data["fechaEvento"], $data["actividad"])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $result = $this->eventoModel->putData($id, $data['nombreEvento'], $data['lugarEvento'], $data["fechaEvento"], $data["actividad"]);

        if ($result) {
            Response::json(['message' => 'Evento actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar el Evento'], 500);
        }
    }

    public function deleteById($id)
    {
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
