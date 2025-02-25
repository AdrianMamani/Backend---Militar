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


    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data["titulo"], $data["descripcion"], $data["fechaPublicacion"])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $result = $this->noticiaModel->putData($id, $data['titulo'], $data['descripcion'], $data["fechaPublicacion"]);

        if ($result) {
            Response::json(['message' => 'Noticia actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar la Noticia'], 500);
        }
    }


    public function deleteById($id)
    {
        $result = $this->noticiaModel->deleteData($id);

        if (!$result) {
            Response::json(["Error" => "No se pudo eliminar el registro con ID $id"]);
        }

        if ($result === null) {
            Response::json(["Message" => "La Noticia con ID $id No Existe"]);
        }
        Response::json(["message" => "Noticia con ID $id Eliminado Correctamente"]);
    }
}
