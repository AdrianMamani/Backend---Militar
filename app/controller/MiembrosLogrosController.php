<?php
require_once __DIR__ . '/../model/MiembrosLogros.php';

class MiembrosLogrosController
{
    private $miembroLogroModel;

    public function __construct(Database $db)
    {
        $this->miembroLogroModel = new MiembrosLogros($db);
    }


    public function getById($id)
    {
        $result =  $this->miembroLogroModel->getById($id);
        if ($result === null) {
            echo json_encode(["message" => "El ID $id No Existe"]);
        }
        Response::json($result);
    }


    public function createMiembrosLogros()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["idMiembro"], $data["idLogro"], $data["idGaleria"], $data["fecha"])) {
            Response::json(["message" => "faltan campos en el JSON"], 400);
        }

        $result = $this->miembroLogroModel->postData($data["idMiembro"], $data["idLogro"], $data["idGaleria"], $data["fecha"]);

        if ($result === null) {
            Response::json(["Error" => "Error al crear el Registro"], 500);
        }
        Response::json(["message" => "Logro del Miembro Registrado Exitosamente."]);
    }



    public function deleteById($idLogro, $idMiembro)
    {
        $result = $this->miembroLogroModel->deleteData($idLogro, $idMiembro);

        if (!$result) {
            Response::json(["Message" => "El Logro del Miembro con ID $idLogro No Existe"]);
        }

        if ($result === null) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $idLogro"]);
        }
        Response::json(["message" => "Se Elimino el Logro con ID $idLogro del Miembro Correctamente"]);
    }
}
