<?php

require_once(__DIR__ . "/../model/Contacto.php");

class ContactoController
{
    private $contactoModel;

    public function __construct(Database $db)
    {
        $this->contactoModel = new Contacto($db);
    }


    public function listContactos()
    {
        $resultLogro = $this->contactoModel->getAll();

        if ($resultLogro === null) {
            Response::json(["Message" => "No se encontraron registros en la tabla Contacto"]);
        } else {
            Response::json($resultLogro);
        }
    }

    public function getById($id)
    {
        $contacto = $this->contactoModel->getById($id);
        if ($contacto) {
            Response::json($contacto);
        } else {
            Response::json(['error' => 'Contacto no encontrado'], 404);
        }
    }
    public function createContacto()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["numero"], $data["correo"], $data["lugar"])) {
            Response::json(["message" => "faltan campos en el JSON"], 400);
        }

        $result = $this->contactoModel->create($data["numero"], $data["correo"], $data["lugar"]);

        if ($result === null) {
            Response::json(["Error" => "Error al crear el Registro Contacto"], 500);
        }
        Response::json(["message" => "Contacto Guardado Exitosamente."]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["numero"], $data["correo"], $data["lugar"])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $result = $this->contactoModel->update($id, $data['numero'], $data['correo'], $data["lugar"]);

        if ($result) {
            Response::json(['message' => 'Contacto actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar el Contacto'], 500);
        }
    }


    public function deleteById($id)
    {
        $result = $this->contactoModel->delete($id);

        if (!$result) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $id"]);
        }

        if ($result === null) {
            Response::json(["Message" => "El Contacto con ID $id No Existe"]);
        }
        Response::json(["message" => "Contacto con ID $id Eliminado Correctamente"]);
    }
}
