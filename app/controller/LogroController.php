<?php

require_once(__DIR__ . "/../model/Logro.php");
class LogroController
{
    private $logroModel;

    public function __construct(Database $db)
    {
        $this->logroModel = new Logro($db);
    }

    public function listLogros()
    {
        $resultLogro = $this->logroModel->getData();

        if ($resultLogro === null) {
            Response::json(["Message" => "No se encontraron registros en la tabla miembro"]);
        } else {
            Response::json($resultLogro);
        }
    }

    public function createLogro()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["titulo"], $data["descripcion"])) {
            Response::json(["message" => "faltan campos en el JSON"], 400);
        }

        $result = $this->logroModel->postData($data["titulo"], $data["descripcion"]);

        if ($result === null) {
            Response::json(["Error" => "Error al crear el Registro"], 500);
        }
        Response::json(["message" => "Logro Guardado Exitosamente."]);
    }

    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["titulo"], $data["descripcion"])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $result = $this->logroModel->putData($id, $data['titulo'], $data['descripcion']);

        if ($result) {
            Response::json(['message' => 'Logro actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar el Logro'], 500);
        }
    }


    public function deleteById($id)
    {
        $result = $this->logroModel->deleteData($id);

        if (!$result) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $id"]);
        }

        if ($result === null) {
            Response::json(["Message" => "El Logro con ID $id No Existe"]);
        }
        Response::json(["message" => "Logro con ID $id Eliminado Correctamente"]);
    }
}
