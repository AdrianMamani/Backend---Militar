<?php
require_once __DIR__ . '/../../../config/Database.php';

$db = new Database();
$conn = $db->getConexion();
header("Content-Type: application/json");

parse_str(file_get_contents("php://input"), $_PUT);

if (!empty($_PUT['id_logro']) && !empty($_PUT['titulo']) && !empty($_PUT['descripcion'])) {
    $id_logro = intval($_PUT['id_logro']);
    $titulo = $_PUT['titulo'];
    $descripcion = $_PUT['descripcion'];
    
    // Iniciar transacción
    $conn->begin_transaction();
    try {
        // Actualizar Logro
        $stmt = $conn->prepare("UPDATE Logro SET titulo = ?, descripcion = ? WHERE id_logro = ?");
        $stmt->bind_param("ssi", $titulo, $descripcion, $id_logro);
        $stmt->execute();
        
        // Si también se quiere actualizar la fecha en Miembros_Logros
        if (!empty($_PUT['fecha']) && !empty($_PUT['id_miembro'])) {
            $fecha = $_PUT['fecha'];
            $id_miembro = intval($_PUT['id_miembro']);
            
            $stmt = $conn->prepare("UPDATE Miembros_Logros SET fecha = ? WHERE id_logro = ? AND id_miembro = ?");
            $stmt->bind_param("sii", $fecha, $id_logro, $id_miembro);
            $stmt->execute();
        }

        $conn->commit();
        echo json_encode(["message" => "Logro actualizado correctamente"], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["error" => "Error al actualizar el logro: " . $e->getMessage()], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(["error" => "Datos incompletos"], JSON_PRETTY_PRINT);
}

?>
