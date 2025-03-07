<?php

require_once __DIR__ . '/../model/NoticiaPersona.php';
class NoticiaPersonaController
{

    private $noticiaPersonaModel;

    public function __construct(Database $db)
    {
        $this->noticiaPersonaModel = new NoticiaPersona($db);
    }

    public function getById($authData, $id)
    {
        $idEvento = intval($id);
        error_log("ID recibido en el controlador: $id");

        $result =  $this->noticiaPersonaModel->getById($id);
        if ($result === null) {
            echo json_encode(["message" => "El ID $id No Existe"]);
        }
        Response::json($result);
    }


    public function createNoticiaPersona()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["idNoticia"], $data["idPersona"])) {
            Response::json(["message" => "faltan campos en el JSON"], 400);
        }

        $result = $this->noticiaPersonaModel->postData($data["idNoticia"], $data["idPersona"]);

        if ($result === null) {
            Response::json(["Error" => "Error al crear el Registro"], 500);
        }
        Response::json(["message" => "Persona Registrada a la Noticia Exitosamente."]);
    }


    public function deleteById($authData, $idPersona, $idNoticia)
    {
        $idPersona = intval($idPersona);
        $idNoticia = intval($idNoticia);
        error_log("ID recibido en el controlador: $idPersona");
        error_log("ID recibido en el controlador: $idNoticia");

        $result = $this->noticiaPersonaModel->deleteData($idPersona, $idNoticia);

        if (!$result) {
            Response::json(["Message" => "La Persona con ID $idPersona ó la Noticia con ID $idNoticia No Existe"]);
        }

        if ($result === null) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $idPersona"]);
        }
        Response::json(["message" => "Se Elimino a la Persona con ID $idPersona de la Noticia Correctamente"]);
    }
}
