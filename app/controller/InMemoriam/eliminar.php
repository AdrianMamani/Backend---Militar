<?php
require_once __DIR__ . '/../../../config/Database.php';

$db = new Database();
$conn = $db->getConexion();
header("Content-Type: application/json");

// Leer datos JSON si $_POST está vacío
$inputJSON = file_get_contents("php://input");
$datos = json_decode($inputJSON, true) ?? $_POST;

if (!empty($datos['id_inmemoriam']) && !empty($datos['id_miembro'])) {
    $id_inmemoriam = intval($datos['id_inmemoriam']);
    $id_miembro = intval($datos['id_miembro']);

    $conn->begin_transaction();
    try {
        // 🔹 Verificar que el registro exista en InMemoriam
        $stmt = $conn->prepare("SELECT id_inmemoriam FROM InMemoriam WHERE id_inmemoriam = ? AND id_miembro = ?");
        $stmt->bind_param("ii", $id_inmemoriam, $id_miembro);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("El registro no existe en InMemoriam.");
        }

        // 🔹 Obtener la imagen asociada antes de eliminar
        $stmt = $conn->prepare("SELECT ruta_archivo FROM Galeria WHERE id_miembro = ? LIMIT 1");
        $stmt->bind_param("i", $id_miembro);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $imagen = $resultado->fetch_assoc()['ruta_archivo'] ?? null;

        // 🔹 Eliminar los logros asociados
        $stmt = $conn->prepare("DELETE FROM Miembros_Logros WHERE id_miembro = ?");
        $stmt->bind_param("i", $id_miembro);
        $stmt->execute();

        // 🔹 Eliminar el registro en InMemoriam
        $stmt = $conn->prepare("DELETE FROM InMemoriam WHERE id_inmemoriam = ? AND id_miembro = ?");
        $stmt->bind_param("ii", $id_inmemoriam, $id_miembro);
        $stmt->execute();

        // 🔹 Eliminar la imagen si existe
        if ($imagen) {
            $ruta_imagen = __DIR__ . "/../../../public_html/img/" . basename($imagen);
            if (file_exists($ruta_imagen)) {
                unlink($ruta_imagen);
            }
        }

        // 🔹 Eliminar la referencia en Galeria
        $stmt = $conn->prepare("DELETE FROM Galeria WHERE id_miembro = ?");
        $stmt->bind_param("i", $id_miembro);
        $stmt->execute();

        $conn->commit();
        echo json_encode(["message" => "Registro eliminado correctamente"], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["error" => "Error al eliminar el registro: " . $e->getMessage()], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(["error" => "Datos incompletos"], JSON_PRETTY_PRINT);
}
