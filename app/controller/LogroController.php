<?php

require_once(__DIR__ . "/../model/Logro.php");
/**
 * Controlador para la gestión de logros.
 */
class LogroController
{
    /**
     * Instancia del modelo Logro.
     * @var Logro
     */
    private $logroModel;

     /**
     * Constructor de la clase.
     * 
     * @param Database $db Instancia de la base de datos.
     */
    public function __construct(Database $db)
    {
        $this->logroModel = new Logro($db);
    }

    /**
     * Cuenta el total de logros registrados en la base de datos.
     * 
     * @return void
     */
    public function countTotalLogro()
    {
        $cantidadLogro = $this->logroModel->countLogro();

        if ($cantidadLogro === null) {
            Response::json(["Message" => "Nose Encontraron Registros en la tabla Logros"]);
        }

        Response::json($cantidadLogro);
    }

    /**
     * Lista todos los logros almacenados en la base de datos.
     * 
     * @return void
     */
    public function listLogros()
    {
        $resultLogro = $this->logroModel->getData();

        if ($resultLogro === null) {
            Response::json(["Message" => "No se encontraron registros en la tabla Logro"]);
        } else {
            Response::json($resultLogro);
        }
    }

    /**
     * Crea un nuevo logro en la base de datos.
     * 
     * @return void
     */
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

    /**
     * Actualiza un logro existente en la base de datos.
     * 
     * @param array $authData Datos de autenticación del usuario.
     * @param int $id Identificador del logro a actualizar.
     * 
     * @return void
     */
    public function update($authData, $id)
    {
        $id = intval($id);
        error_log("ID recibido en el controlador: $id");


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

    /**
     * Elimina un logro de la base de datos por su ID.
     * 
     * @param array $authData Datos de autenticación del usuario.
     * @param int $id Identificador del logro a eliminar.
     * 
     * @return void
     */
    public function deleteById($authData, $id)
    {
        $id = intval($id);
        error_log("ID recibido en el controlador: $id");

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
