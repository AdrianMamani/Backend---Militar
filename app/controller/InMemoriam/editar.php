<?php
require_once __DIR__ . '/../../../config/Database.php';

$db = new Database();
$conn = $db->getConexion();
header("Content-Type: application/json");

if (!empty($_POST['id_inmemoriam']) && !empty($_POST['id_miembro']) && !empty($_POST['nombres']) && !empty($_POST['fecha_nac']) && !empty($_POST['fecha_fallecimiento']) && !empty($_POST['descripcion']) && !empty($_POST['logros'])) {
    
    $id_inmemoriam = intval($_POST['id_inmemoriam']);
    $id_miembro = intval($_POST['id_miembro']);
    $logros = json_decode($_POST['logros'], true);

    if (!is_array($logros)) {
        $logros = json_decode(stripslashes(trim($_POST['logros'])), true);
    }

    if (!is_array($logros)) {
        echo json_encode([
            "error" => "Formato de logros incorrecto",
            "logros_recibido" => $_POST['logros'],
            "json_error" => json_last_error_msg()
        ], JSON_PRETTY_PRINT);
        exit;
    }

    $conn->begin_transaction();
    try {
        // 🔹 Verificar que el miembro esté en InMemoriam
        $stmt = $conn->prepare("SELECT id_inmemoriam FROM InMemoriam WHERE id_inmemoriam = ? AND id_miembro = ?");
        $stmt->bind_param("ii", $id_inmemoriam, $id_miembro);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("El miembro no está en InMemoriam");
        }

        // 🔹 Actualizar datos del miembro
        $stmt = $conn->prepare("UPDATE Miembro SET nombres = ?, fecha_nac = ? WHERE id_miembro = ?");
        $stmt->bind_param("ssi", $_POST['nombres'], $_POST['fecha_nac'], $id_miembro);
        $stmt->execute();

        // 🔹 Actualizar datos en InMemoriam
        $stmt = $conn->prepare("UPDATE InMemoriam SET fecha_fallecimiento = ?, descripcion = ? WHERE id_inmemoriam = ?");
        $stmt->bind_param("ssi", $_POST['fecha_fallecimiento'], $_POST['descripcion'], $id_inmemoriam);
        $stmt->execute();

        // 🔹 Borrar logros antiguos
        $stmt = $conn->prepare("DELETE FROM Miembros_Logros WHERE id_miembro = ?");
        $stmt->bind_param("i", $id_miembro);
        $stmt->execute();

        // 🔹 Obtener id_galeria del miembro
        $stmt = $conn->prepare("SELECT id_galeria FROM Galeria WHERE id_miembro = ? LIMIT 1");
        $stmt->bind_param("i", $id_miembro);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $row = $resultado->fetch_assoc();
        $id_galeria = $row['id_galeria'] ?? null;

        if (!$id_galeria) {
            throw new Exception("No se encontró una galería asociada a este miembro.");
        }

        // 🔹 Insertar nuevos logros
        foreach ($logros as $logro) {
            $stmt = $conn->prepare("INSERT INTO Logro (titulo, descripcion) VALUES (?, ?)");
            $stmt->bind_param("ss", $logro['titulo'], $logro['descripcion']);
            $stmt->execute();
            $id_logro = $stmt->insert_id;

            // Asociar logro con el miembro en InMemoriam
            $stmt = $conn->prepare("INSERT INTO Miembros_Logros (id_miembro, id_logro, id_galeria, fecha) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iii", $id_miembro, $id_logro, $id_galeria);
            $stmt->execute();
        }

        // 🔹 Verificar si hay nueva imagen
        if (!empty($_FILES['imagen']['tmp_name'])) {
            // Obtener la imagen anterior
            $stmt = $conn->prepare("SELECT ruta_archivo FROM Galeria WHERE id_miembro = ? LIMIT 1");
            $stmt->bind_param("i", $id_miembro);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $imagen_antigua = $resultado->fetch_assoc()['ruta_archivo'];

            // Eliminar la imagen anterior del servidor
            if ($imagen_antigua && file_exists(__DIR__ . "/../../../public_html/img/" . basename($imagen_antigua))) {
                unlink(__DIR__ . "/../../../public_html/img/" . basename($imagen_antigua));
            }

            // Subir la nueva imagen
            $ruta_carpeta = __DIR__ . "/../../../public_html/img";
            if (!is_dir($ruta_carpeta)) {
                mkdir($ruta_carpeta, 0777, true);
            }

            $imagen_nombre = uniqid() . "_" . basename($_FILES['imagen']['name']);
            $ruta_imagen = $ruta_carpeta . "/" . $imagen_nombre;

            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen)) {
                $url_imagen = "https://tudominio.com/img/" . $imagen_nombre;

                // Actualizar la imagen en la base de datos
                $stmt = $conn->prepare("UPDATE Galeria SET ruta_archivo = ? WHERE id_miembro = ?");
                $stmt->bind_param("si", $url_imagen, $id_miembro);
                $stmt->execute();
            } else {
                throw new Exception("Error al subir la nueva imagen.");
            }
        }

        $conn->commit();
        echo json_encode(["message" => "Registro actualizado correctamente"], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["error" => "Error al actualizar el registro: " . $e->getMessage()], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(["error" => "Datos incompletos"], JSON_PRETTY_PRINT);
}
