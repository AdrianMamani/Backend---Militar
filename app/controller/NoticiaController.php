<?php

require_once __DIR__ . "/../model/Noticia.php";

class NoticiaController
{
    private $noticiaModel;

    public function __construct(Database $db)
    {
        $this->noticiaModel = new Noticia($db);
    }

    public function countTotalNoticia()
    {
        $totalNoticia =  $this->noticiaModel->countNoticia();

        if ($totalNoticia === null) {
            Response::json("No se Encontro Registros en la Tabla Noticias");
        }
        Response::json($totalNoticia);
    }
    public function listAll()
    {
        $noticias = $this->noticiaModel->getData();

        if ($noticias == null) {
            echo "La tabla Noticias no cuenta con Registros ";
        }
        Response::json($noticias);
    }
    public function update($authData, $id)
    {
        $idEvento = intval($id);
        error_log("ID recibido en el controlador: $id");

        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["titulo"], $data["descripcion"], $data["fecha_publicacion"])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $result = $this->noticiaModel->putData($id, $data['titulo'], $data['descripcion'], $data["fecha_publicacion"]);

        if ($result === null) {
            Response::json(['error' => "La Noticia con ID $id No Existe"], 404);
        }

        if ($result) {
            Response::json(['message' => 'Noticia actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar la Noticia'], 500);
        }
    }


    public function deleteById($authData, $id)
    {
        $idEvento = intval($id);
        error_log("ID recibido en el controlador: $id");

        $result = $this->noticiaModel->deleteData($id);

        if (!$result) {
            Response::json(["Error" => "La Noticia con ID $id No Existe"], 404);
        }

        if ($result === null) {
            Response::json(["Message" => "La Noticia con ID $id No Existe"]);
        }
        Response::json(["message" => "Noticia con ID $id Eliminado Correctamente"]);
    }
}
