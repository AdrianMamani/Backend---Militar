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
        $categorias = $this->categoriaModel->getAll();
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

    public function getById($authData, $id)
    {

        $id = intval($id);

        $categoria = $this->categoriaModel->getById($id);
        if ($categoria) {
            Response::json($categoria);
        } else {
            Response::json(["message" => "Ocurrio un Error"], 404);
        }
    }

    public function update($authData, $id) {

        $data = json_decode(file_get_contents("php://input"),true);

        if (!isset($data['nombre']) || !isset($data['descripcion'])) {
            Response::json(['error' => 'Faltan datos de Categoria'], 400);
            return;
        }

        $result = $this ->categoriaModel -> update($id, $data['nombre'], $data['descripcion']);

        if ($result) {
            Response::json(['message' => 'Actualizado Correctamente']);
        }else{
            Response::json(['error' => 'Error al Actualizar'], 400);
        }
        
    }
    

    public function delete($authData, $id)  {
        $id = intval($id);

        $categoria = $this -> categoriaModel ->getById($id);
        
        if (empty($categoria)) {
            Response::json(['message' => 'No se Encontro la Categoria'],400);
        }

        $result = $this -> categoriaModel ->delete($id);

        if ($result) {
            Response::json(['message' => 'Categoria Eliminado']);
        }else{
            Response::json(['message' => 'Error al eliminar'],400);
        }


    }

}
