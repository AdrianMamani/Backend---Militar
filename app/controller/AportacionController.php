<?php
require_once __DIR__ . '/../model/Aportacion.php';
require_once __DIR__ . '/../model/Asociado.php';
class AportacionController
{
    private $aportacionModel;
    private $asociadoModel;

    public function __construct(Database $db)
    {
        $this->aportacionModel = new Aportacion($db);
        $this->asociadoModel = new Asociado($db);
    }

    // GET /aportaciones
    public function getAll()
    {
        $aportaciones = $this->aportacionModel->getAll();
        header("Content-Type: application/json");
        echo json_encode($aportaciones);
    }

    // GET /aportaciones/{id}
    public function getById($userData, $id) {
        $id = intval($id);
        $aportacion = $this->aportacionModel->getById($id);
        header("Content-Type: application/json");
        if ($aportacion) {
            echo json_encode($aportacion);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Aportación no encontrada"]);
        }
    }

    // POST /aportaciones
    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);
    
        if (!isset($data['aportacion'])) {
            Response::json(['error' => 'Faltan datos de la aportación'], 400);
            return;
        }
    
        if (!isset($data['asociado']) && !isset($data['id_asociado'])) {
            Response::json(['error' => 'Debe proporcionar un asociado o un id_asociado'], 400);
            return;
        }
    
        if (isset($data['asociado'])) {
            $asociadoData = $data['asociado'];
            if (!isset($asociadoData['nombre_completo']) || !isset($asociadoData['lugar'])) {
                Response::json(['error' => 'Faltan datos requeridos para el asociado'], 400);
                return;
            }
    
            $id_asociado = $this->asociadoModel->create(
                $asociadoData['nombre_completo'],
                $asociadoData['lugar']
            );
    
            if (!$id_asociado) {
                Response::json(['error' => 'Error al crear el asociado'], 500);
                return;
            }
        } else {
            $id_asociado = $data['id_asociado'];
        }
    
        $aportacionData = $data['aportacion'];
        if (
            !isset($aportacionData['id_categoria']) ||
            !isset($aportacionData['id_tesorero']) ||
            !isset($aportacionData['montos'])
        ) {
            Response::json(['error' => 'Faltan datos requeridos para la aportación'], 400);
            return;
        }

        // Crear la aportación y obtener su ID
        $id_aportacion = $this->aportacionModel->create(
            $aportacionData['id_categoria'],
            $aportacionData['id_tesorero'],
            $aportacionData['montos'],
            $aportacionData['lugar']
        );
    
        if (!$id_aportacion) {
            Response::json(['error' => 'Error al crear la aportación'], 500);
            return;
        }
    
        // Asociar la aportación con el asociado
        if (!$this->asociadoModel->associateAportacion($id_asociado, $id_aportacion)) {
            Response::json(['error' => 'Error al asociar la aportación con el asociado'], 500);
            return;
        }
    
        // Respuesta exitosa
        Response::json([
            'message' => 'Created successfully',
            'id_asociado' => $id_asociado,
            'id_aportacion' => $id_aportacion
        ]);
    }

    // PATCH /aportaciones/{id}
    public function updateArgs($authData, $id) {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = intval($id);
        // 1. Obtener el registro actual de la base de datos
        $aportacionActual = $this->aportacionModel->getById($id);
        if (!$aportacionActual) {
            http_response_code(404);
            echo json_encode(["error" => "Registro no encontrado"]);
            return;
        }
    
        // 2. Combinar datos existentes con los nuevos (actualización parcial)
        $id_categoria = $data['id_categoria'] ?? $aportacionActual['id_categoria'];
        $id_tesorero = $data['id_tesorero'] ?? $aportacionActual['id_tesorero'];
        $montos = $data['montos'];
        // Manejar montos (si llegan, actualizas, sino, mantienes)
        $montos = [
            $montos['ene'] ?? $aportacionActual['monto_ene'],
            $montos['feb'] ?? $aportacionActual['monto_feb'],
            $montos['mar'] ?? $aportacionActual['monto_mar'],
            $montos['abr'] ?? $aportacionActual['monto_abr'],
            $montos['may'] ?? $aportacionActual['monto_may'],
            $montos['jun'] ?? $aportacionActual['monto_jun'],
            $montos['jul'] ?? $aportacionActual['monto_jul'],
            $montos['ago'] ?? $aportacionActual['monto_ago'],
            $montos['sep'] ?? $aportacionActual['monto_sep'],
            $montos['oct'] ?? $aportacionActual['monto_oct'],
            $montos['nov'] ?? $aportacionActual['monto_nov'],
            $montos['dic'] ?? $aportacionActual['monto_dic']
        ];
    
        $lugar = $data['lugar'] ?? $aportacionActual['lugar'];
    
        // 3. Llamar al modelo para hacer el update
        $success = $this->aportacionModel->update(
            $id,
            $id_categoria,
            $id_tesorero,
            $montos,
            $lugar
        );
    
        header("Content-Type: application/json");
        if ($success) {
            echo json_encode(["message" => "Aportación actualizada parcialmente"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar la aportación"]);
        }
    }
    

    // PUT/PATCH /aportaciones/{id}
    public function update($authData, $id)
    {
        $id = intval($id);
        error_log("ID recibido en el controlador: $id");

        $data = json_decode(file_get_contents("php://input"), true);

        $aportacionActual = $this->aportacionModel->getById($id);
        if (!$aportacionActual) {
            http_response_code(404);
            echo json_encode(["error" => "Registro no encontrado"]);
            return;
        }
        
        $id_categoria = isset($data['id_categoria']) ? $data['id_categoria'] : $aportacionActual['id_categoria'];
        $id_tesorero = isset($data['id_tesorero']) ? $data['id_tesorero'] : $aportacionActual['id_tesorero'];
        
        $montos = isset($data['montos']) && is_array($data['montos']) ? $data['montos'] : [
            $aportacionActual['monto_ene'],
            $aportacionActual['monto_feb'],
            $aportacionActual['monto_mar'],
            $aportacionActual['monto_abr'],
            $aportacionActual['monto_may'],
            $aportacionActual['monto_jun'],
            $aportacionActual['monto_jul'],
            $aportacionActual['monto_ago'],
            $aportacionActual['monto_sep'],
            $aportacionActual['monto_oct'],
            $aportacionActual['monto_nov'],
            $aportacionActual['monto_dic']
        ];
        
        $lugar = isset($data['lugar']) ? $data['lugar'] : $aportacionActual['lugar'];
        $total = isset($data['total']) ? $data['total'] : $aportacionActual['total'];

        $success = $this->aportacionModel->update(
            $id,
            $id_categoria,
            $id_tesorero,
            $montos,
            $lugar,
            $total
        );
        
        header("Content-Type: application/json");
        if ($success) {
            echo json_encode(["message" => "Aportación actualizada"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al actualizar la aportación"]);
        }
    }

    // DELETE /aportaciones/{id}
    public function delete($authData, $id) {
        $id = intval($id);
        $success = $this->aportacionModel->delete($id);
        
        header("Content-Type: application/json");
        if ($success) {
            echo json_encode(["message" => "Aportación eliminada"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Error al eliminar la aportación"]);
        }
    }
}
