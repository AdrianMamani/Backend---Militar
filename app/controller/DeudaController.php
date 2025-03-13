<?php
require_once __DIR__ . '/../model/Deuda.php';
class DeudaController
{
    private Deuda $deudaModel;

    public function __construct(Database $db)
    {
        $this->deudaModel = new Deuda($db);
    }


    public function getAll()
    {
        $deudas = $this->deudaModel->getAll();

        header("Content-Type: application/json");
        if ($deudas) {
            echo json_encode($deudas);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "no hay deudas"]);
        }
    }


    public function getById($authData, $id)
    {
        $id = intval($id);
        $deudas = $this->deudaModel->getById($id);

        header("Content-Type: application/json");
        if ($deudas) {
            Response::json($deudas);
        } else {
            Response::json(["message" => "Ocurrio un Error"], 404);
        }
    }

    public function create()
    {

        $data = json_decode(file_get_contents("php://input"), true);


        if (!isset($data['fecha']) || !isset($data['monto']) || !isset($data['id_asociado'])) {
            Response::json(['error' => 'Faltan datos de deuda'], 400);
            return;
        }

        $id_deuda = $this->deudaModel->create(
            $data['fecha'],
            $data['monto']
            ,
            $data['id_asociado']
        );

        if ($id_deuda) {
            Response::json(["message" => "Deuda asignado", $id_deuda]);
        } else {
            Response::json(["message" => "Ocurrio Un Error al crear"], 404);
        }


    }

    public function update($authData, $id)
    {
        $data = json_decode(file_get_contents("php://input"), false);

        $id =intval($id);
        if (
            !isset($data->fecha) || !isset($data->monto) || !isset($data->id_asociado)
        ) {
            Response::json(['error' => 'Faltan datos de deuda'], 400);
            return;
        }

        $result = $this->deudaModel->update($id, $data->fecha
        , $data->monto, $data->id_asociado);

        if ($result) {
            Response::json(["message" => "Deuda Actualizada"]);
        }else {
            Response::json(['error' => 'Ocurrio un problema al Actualizar la Deuda'], 400);
        }

    }

    public function delete($authData, $id) {
        $id = intval($id);
        $result = $this-> deudaModel -> delete($id);

        if ($result) {
            Response::json(["message" => "Se Elimino Correctamente"]);
        } else {
            Response::json(["error" => "Ocurrio un Problema al eliminar Deuda"]);
        }
        
    }

}

?>