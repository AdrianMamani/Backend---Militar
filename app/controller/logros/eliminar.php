<?php
require_once __DIR__ . '/../../../config/Database.php';

$db = new Database();
$conn = $db->getConexion();
header("Content-Type: application/json");

// Leer datos JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['id_logro'])) {
    $id_logro = intval($data['id_logro']);

    // Iniciar transacción
    $conn->begin_transaction();
    try {
        // Eliminar relación en Miembros_Logros
        $stmt = $conn->prepare("DELETE FROM Miembros_Logros WHERE id_logro = ?");
        $stmt->bind_param("i", $id_logro);
        $stmt->execute();

        // Eliminar el logro de la tabla Logro
        $stmt = $conn->prepare("DELETE FROM Logro WHERE id_logro = ?");
        $stmt->bind_param("i", $id_logro);
        $stmt->execute();

        $conn->commit();
        echo json_encode(["message" => "Logro eliminado correctamente"], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["error" => "Error al eliminar el logro: " . $e->getMessage()], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(["error" => "ID de logro no proporcionado"], JSON_PRETTY_PRINT);
}
?>
