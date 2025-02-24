<?php
require_once __DIR__ . '/../model/EventoMiembro.php';

class EventoMiembroController
{

    private $eventoMiembroModel;

    public function __construct(Database $db)
    {
        $this->eventoMiembroModel = new EventoMiembro($db);
    }


    public function getById($id)
    {
        $result =  $this->eventoMiembroModel->getById($id);
        if ($result === null) {
            echo json_encode(["message" => "El ID $id No Existe"]);
        }
        Response::json($result);
    }


    public function createEventoMiembro()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["idEvento"], $data["idMiembro"])) {
            Response::json(["message" => "faltan campos en el JSON"], 400);
        }

        $result = $this->eventoMiembroModel->postData($data["idEvento"], $data["idMiembro"]);

        if ($result === null) {
            Response::json(["Error" => "Error al crear el Registro"], 500);
        }
        Response::json(["message" => "Organizador Registrado al Evento Exitosamente."]);
    }

    public function update($idMiembro, $idEvento)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["idNuevoMiembro"])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $result = $this->eventoMiembroModel->putData($idMiembro, $idEvento,  $data['idNuevoMiembro']);

        if ($result) {
            Response::json(['message' => 'Organizador actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar el Organizador'], 500);
        }
    }

    public function deleteById($idMiembro, $idEvento)
    {
        $result = $this->eventoMiembroModel->deleteData($idMiembro, $idEvento);

        if (!$result) {
            Response::json(["Message" => "El Organizador con ID $idMiembro No Existe"]);
        }

        if ($result === null) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $idMiembro"]);
        }
        Response::json(["message" => "Se Elimino el Organizador con ID $idMiembro del Evento Correctamente"]);
    }
}
