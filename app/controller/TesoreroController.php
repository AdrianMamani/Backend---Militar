<?php
require_once __DIR__ . "/../model/Tesorero.php";
require_once __DIR__ . "/../model/Contacto.php";
require_once __DIR__ . "/../utils/Response.php";

class TesoreroController
{
    private $tesoreroModel;
    private $contactoModel;

    public function __construct(Database $db)
    {
        $this->tesoreroModel = new Tesorero($db);
        $this->contactoModel = new Contacto($db);
    }

    // Obtener todos los tesoreros
    public function getAll()
    {
        $tesoreros = $this->tesoreroModel->getAll();
        Response::json($tesoreros);
    }

    // Obtener un tesorero por ID
    public function getById($authData, $id)
    {
        $id = intval($id);
        $tesorero = $this->tesoreroModel->getById($id);
        if ($tesorero) {
            Response::json($tesorero);
        } else {
            Response::json(['error' => 'Tesorero no encontrado'], 404);
        }
    }

    // Crear un nuevo tesorero (con contacto)
    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['num_contacto'], $data['correo'], $data['lugar'], $data['nombre_completo'])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        // Crear el contacto
        $id_contacto = $this->contactoModel->create($data['num_contacto'], $data['correo'], $data['lugar']);

        if (!$id_contacto) {
            Response::json(['error' => 'Error al crear contacto'], 500);
            return;
        }

        //Crear el tesorero con el ID del contacto
        $id_tesorero = $this->tesoreroModel->create($id_contacto, $data['nombre_completo']);

        if ($id_tesorero) {
            Response::json(['message' => 'Tesorero creado exitosamente']);
        } else {
            Response::json(['error' => 'Error al crear tesorero'], 500);
        }
    }

    // Actualizar tesorero
    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['nombre_completo'], $data["contacto"])) {
            Response::json(['error' => 'Faltan datos'], 400);
            return;
        }

        $result = $this->tesoreroModel->update($id, $data["contacto"], $data['nombre_completo']);

        if ($result) {
            Response::json(['message' => 'Tesorero actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar tesorero'], 500);
        }
    }

    // Eliminar tesorero
    public function delete($authData, $id)
    {
        $id = intval($id);
        $result = $this->tesoreroModel->delete($id);

        if ($result) {
            Response::json(['message' => 'Tesorero eliminado']);
        } else {
            Response::json(['error' => 'Error al eliminar tesorero'], 500);
        }
    }
}
