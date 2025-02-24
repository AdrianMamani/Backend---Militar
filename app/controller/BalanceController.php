<?php
require_once __DIR__ . "/../model/Balance.php";
require_once __DIR__ . "/../utils/Response.php";

class BalanceController
{
    private $balanceModel;

    public function __construct(Database $db)
    {
        $this->balanceModel = new Balance($db);
    }

    // Obtener todos los balances
    public function getAll()
    {
        $balances = $this->balanceModel->getAll();
        Response::json($balances);
    }

    // Obtener un balance por ID
    public function getById($id)
    {
        $balance = $this->balanceModel->getBalanceById($id);
        if ($balance) {
            Response::json($balance);
        } else {
            Response::json(['error' => 'Balance no encontrado'], 404);
        }
    }

    // Crear un nuevo balance
    public function create()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            !isset($data['descripcion']) ||
            !isset($data['debe']) ||
            !isset($data['haber']) ||
            !isset($data['fecha'])
        ) {
            Response::json(['error' => 'Faltan datos requeridos'], 400);
            return;
        }

        $success = $this->balanceModel->create(
            $data['descripcion'],
            $data['debe'],
            $data['haber'],
            $data['fecha']
        );

        if ($success) {
            Response::json(['message' => 'Balance creado exitosamente']);
        } else {
            Response::json(['error' => 'Error al crear el balance'], 500);
        }
    }

    // Actualizar balance
    public function update($id)
    {
        $data = json_decode(file_get_contents("php://input"), true);
        if (
            !isset($data['descripcion']) ||
            !isset($data['debe']) ||
            !isset($data['haber']) ||
            !isset($data['fecha'])
        ) {
            Response::json(['error' => 'Faltan datos requeridos'], 400);
            return;
        }

        $success = $this->balanceModel->update(
            $id,
            $data['descripcion'],
            $data['debe'],
            $data['haber'],
            $data['fecha']
        );

        if ($success) {
            Response::json(['message' => 'Balance actualizado exitosamente']);
        } else {
            Response::json(['error' => 'Error al actualizar el balance'], 500);
        }
    }

    // Eliminar balance
    public function delete($id)
    {
        $success = $this->balanceModel->delete($id);
        if ($success) {
            Response::json(['message' => 'Balance eliminado']);
        } else {
            Response::json(['error' => 'Error al eliminar el balance'], 500);
        }
    }
}
?>
