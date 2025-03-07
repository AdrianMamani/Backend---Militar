<?php
require_once __DIR__ . '/../model/MiembrosLogros.php';

class MiembrosLogrosController
{
    private $miembroLogroModel;

    public function __construct(Database $db)
    {
        $this->miembroLogroModel = new MiembrosLogros($db);
    }


    public function getById($authData, $id)
    {
        $id = intval($id);
        error_log("ID recibido en el controlador: $id");

        $result =  $this->miembroLogroModel->getById($id);
        if ($result === null) {
            Response::json(["message" => "El ID $id No Existe"], 404);
            # echo json_encode(["message" => "El ID $id No Existe"], 404);
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



    public function deleteById($authData, $idLogro, $idMiembro)
    {
        $idLogro = intval($idLogro);
        $idMiembro = intval($idMiembro);
        error_log("ID recibido en el controlador: $idLogro");
        error_log("ID recibido en el controlador: $idMiembro");


        $result = $this->miembroLogroModel->deleteData($idLogro, $idMiembro);

        if (!$result) {
            Response::json(["Message" => "El Logro con ID $idLogro ó el Miembro con ID $idMiembro No Existe"], 404);
        }

        if ($result === null) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $idLogro"]);
        }
        Response::json(["message" => "Se Elimino el Logro con ID $idLogro del Miembro Correctamente"]);
    }
}
