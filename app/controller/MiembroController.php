<?php
require_once(__DIR__ . "/../model/Miembro.php");
require_once(__DIR__ . "/../model/Contacto.php");

class MiembroController
{
    private $miembroModel;
    private $contactoModel;

    function __construct(Database $db)
    {
        $this->miembroModel = new Miembro($db);
        $this->contactoModel = new Contacto($db);
    }

    public function listAll()
    {
        $miembros = $this->miembroModel->getData();

        if ($miembros == null) {
            echo "La tabla Miembros no cuenta con Registros ";
        }
        Response::json($miembros);
    }

    public function getById($id)
    {
        $result =  $this->miembroModel->getDataById($id);
        if ($result === null) {
            echo json_encode(["message" => "El ID $id No Existe"]);
        }
        Response::json($result);
    }

    public function deleteById($id)
    {
        $result = $this->miembroModel->deleteData($id);

        if (!$result) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $id"]);
        }

        if ($result === null) {
            Response::json(["Message" => "El Miembro con ID $id No Existe"]);
        }
        Response::json(["message" => "Miembro con ID $id Eliminado Correctamente"]);
    }


    public function updateById($id)
    {
        $resultMiembro = $this->miembroModel->getDataMiembroById($id);
        $idContacto = $resultMiembro["id_contacto"];

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["id_contacto"], $data["nombres"], $data["cargo"], $data["descripcion"])) {
            Response::json(["Error" => "Se requiere mas campos"], 400);
        }

        $this->miembroModel->putData($id, $idContacto, $data["nombres"], $data["cargo"], $data["descripcion"]);
        $resultContacto = $this->contactoModel->putData($idContacto, $data["id_contacto"]["numero"], $data["id_contacto"]["correo"], $data["id_contacto"]["lugar"]);

        if (!$resultContacto) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $idContacto"]);
        }

        if ($resultContacto === null) {
            Response::json(["Message" => "El Contacto con ID $id No Existe"]);
        }

        Response::json(["message" => "Miembro con ID $id Se Actualizo Correctamente"]);
    }
}
