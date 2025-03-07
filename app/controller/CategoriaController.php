<?php

require_once __DIR__ . "/../model/Categoria.php";

class CategoriaController
{

    private $categoriaModel;


    public function __construct(Database $db)
    {
        $this->categoriaModel = new Categoria($db);
    }


    public function getAll()
    {
        $categorias =  $this->categoriaModel->getAll();
        Response::json($categorias);
    }

    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data["nombre"], $data["descripcion"])) {
            Response::json(["message" => "faltan campos en el JSON"], 400);
        }

        $result = $this->categoriaModel->create($data['nombre'], $data['descripcion']);

        if ($result) {
            Response::json(["message" => "Categoria Guardado Exitosamente."]);
        } else {
            Response::json(["message" => "Error al Crear Categoria"], 500);
        }
    }
}
