<?php

require_once(__DIR__ . "/../model/Contacto.php");

class ContactoController
{
    private $contactoModel;

    public function __construct(Database $db)
    {
        $this->contactoModel = new Contacto($db);
    }

    public function getById($id)
    {
        $contacto = $this->contactoModel->getDataById($id);
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

        $result = $this->contactoModel->postData($data["numero"], $data["correo"], $data["lugar"]);

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

        $result = $this->contactoModel->putData($id, $data['numero'], $data['correo'], $data["lugar"]);

        if ($result) {
            Response::json(['message' => 'Contacto actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar el Contacto'], 500);
        }
    }
}
