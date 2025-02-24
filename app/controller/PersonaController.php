<?php
require_once(__DIR__ . "/../model/Persona.php");

class PersonaController
{

    private $personaModel;

    public function __construct(Database $db)
    {
        $this->personaModel = new Persona($db);
    }

    public function listPersonas()
    {
        $resultLogro = $this->personaModel->getData();

        if ($resultLogro === null) {
            Response::json(["Message" => "No se encontraron registros en la tabla Persona"]);
        } else {
            Response::json($resultLogro);
        }
    }

    public function getById($id)
    {
        $result =  $this->personaModel->getDataById($id);
        if ($result === null) {
            echo json_encode(["message" => "El ID $id No Existe"]);
        }
        Response::json($result);
    }


    public function createPersona()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["contacto"], $data["nombres"])) {
            Response::json(["message" => "faltan campos en el JSON"], 400);
        }

        $result = $this->personaModel->postData($data["contacto"], $data["nombres"]);

        if ($result === null) {
            Response::json(["Error" => "Error al crear el Registro"], 500);
        }
        Response::json(["message" => "Persona Guardado Exitosamente."]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["contacto"], $data["nombres"])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $result = $this->personaModel->putData($id, $data['contacto'], $data['nombres']);

        if ($result) {
            Response::json(['message' => 'Persona actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar la Persona'], 500);
        }
    }


    public function deleteById($id)
    {
        $result = $this->personaModel->deleteData($id);

        if (!$result) {
            Response::json(["Message" => "El Logro con ID $id No Existe"]);
        }

        if ($result === null) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $id"]);
        }
        Response::json(["message" => "Persona con ID $id Eliminado Correctamente"]);
    }
}
