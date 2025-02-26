<?php
require_once __DIR__ . '/../../../config/Database.php';

header("Content-Type: application/json");

$db = new Database();
$conn = $db->getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $id_miembro = $_POST['id_miembro'] ?? '';
    $fecha = $_POST['fecha'] ?? '';

    if (!empty($titulo) && !empty($descripcion) && !empty($id_miembro) && !empty($fecha)) {
        // Insertar el logro en la tabla Logro
        $stmt = $conn->prepare("INSERT INTO Logro (titulo, descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $titulo, $descripcion);

        if ($stmt->execute()) {
            $id_logro = $stmt->insert_id;

            // Insertar la relación en Miembros_Logros (sin id_galeria)
            $stmt = $conn->prepare("INSERT INTO Miembros_Logros (id_miembro, id_logro, fecha) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $id_miembro, $id_logro, $fecha);

            if ($stmt->execute()) {
                echo json_encode(["message" => "Logro agregado correctamente"], JSON_PRETTY_PRINT);
            } else {
                echo json_encode(["error" => "Error al asociar el logro al miembro"], JSON_PRETTY_PRINT);
            }
        } else {
            echo json_encode(["error" => "Error al agregar el logro"], JSON_PRETTY_PRINT);
        }
    } else {
        echo json_encode(["error" => "Datos incompletos"], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(["error" => "Método no permitido"], JSON_PRETTY_PRINT);
}

