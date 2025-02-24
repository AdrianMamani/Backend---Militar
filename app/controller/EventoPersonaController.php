<?php
require_once __DIR__ . '/../model/EventoPersona.php';
class EventoPersonaController
{

    private $eventoPersonaModel;

    public function __construct(Database $db)
    {
        $this->eventoPersonaModel = new EventoPersona($db);
    }

    public function listEventoPersona()
    {
        $resultEventoPersona = $this->eventoPersonaModel->getData();

        if ($resultEventoPersona === null) {
            Response::json(["Message" => "No se encontraron registros en la tabla Persona"]);
        } else {
            Response::json($resultEventoPersona);
        }
    }

    public function getById($id)
    {
        $result =  $this->eventoPersonaModel->getById($id);
        if ($result === null) {
            echo json_encode(["message" => "El ID $id No Existe"]);
        }
        Response::json($result);
    }

    public function createEventoPersona()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["idEvento"], $data["idPersona"])) {
            Response::json(["message" => "faltan campos en el JSON"], 400);
        }

        $result = $this->eventoPersonaModel->postData($data["idEvento"], $data["idPersona"]);

        if ($result === null) {
            Response::json(["Error" => "Error al crear el Registro"], 500);
        }
        Response::json(["message" => "Persona Guardado al Evento Exitosamente."]);
    }



    public function deleteById($idPersona, $idEvento)
    {
        $result = $this->eventoPersonaModel->deleteData($idPersona, $idEvento);

        if (!$result) {
            Response::json(["Message" => "El Evento Persona con ID $idPersona No Existe"]);
        }

        if ($result === null) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $idPersona"]);
        }
        Response::json(["message" => "Se Elimino a la Persona con ID $idPersona del Evento Correctamente"]);
    }
}
