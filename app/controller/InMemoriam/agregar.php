<?php
require_once __DIR__ . '/../../../config/Database.php';

$db = new Database();
$conn = $db->getConexion();
header("Content-Type: application/json");

if (!empty($_POST['nombres']) && !empty($_POST['fecha_nac']) && !empty($_POST['fecha_fallecimiento']) && !empty($_POST['descripcion']) && !empty($_POST['logros']) && !empty($_FILES['imagen'])) {
    
    $logros = json_decode($_POST['logros'], true);

    if (!is_array($logros)) {
        // Intenta decodificar nuevamente eliminando caracteres ocultos
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
        $stmt = $conn->prepare("INSERT INTO Miembro (nombres, fecha_nac) VALUES (?, ?)");
        $stmt->bind_param("ss", $_POST['nombres'], $_POST['fecha_nac']);
        $stmt->execute();
        $id_miembro = $stmt->insert_id;

        $stmt = $conn->prepare("INSERT INTO InMemoriam (id_miembro, fecha_fallecimiento, descripcion) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id_miembro, $_POST['fecha_fallecimiento'], $_POST['descripcion']);
        $stmt->execute();

        // Definir la carpeta de imágenes en el hosting
        $ruta_carpeta = __DIR__ . "/../../../public_html/img"; // Ajusta la ruta según el hosting

        // Verificar si la carpeta existe, si no, crearla
        if (!is_dir($ruta_carpeta)) {
            mkdir($ruta_carpeta, 0777, true);
        }

        // Nombre único para la imagen
        $imagen_nombre = uniqid() . "_" . basename($_FILES['imagen']['name']);
        $ruta_imagen = $ruta_carpeta . "/" . $imagen_nombre;

        // Mover el archivo subido a la carpeta en el servidor
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen)) {
            // Guardar la URL de acceso público en la base de datos
            $url_imagen = "https://tudominio.com/img/" . $imagen_nombre;
        } else {
            throw new Exception("Error al subir la imagen.");
        }

        // Guardar en la base de datos la URL en lugar de la ruta local
        $stmt = $conn->prepare("INSERT INTO Galeria (id_miembro, tipo_archivo, ruta_archivo, informacion) VALUES (?, 'imagen', ?, 'Foto del miembro en InMemoriam')");
        $stmt->bind_param("is", $id_miembro, $url_imagen);
        $stmt->execute();
        $id_galeria = $stmt->insert_id;

        foreach ($logros as $logro) {
            $stmt = $conn->prepare("INSERT INTO Logro (titulo, descripcion) VALUES (?, ?)");
            $stmt->bind_param("ss", $logro['titulo'], $logro['descripcion']);
            $stmt->execute();
            $id_logro = $stmt->insert_id;

            $stmt = $conn->prepare("INSERT INTO Miembros_Logros (id_miembro, id_logro, id_galeria, fecha) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("iii", $id_miembro, $id_logro, $id_galeria);
            $stmt->execute();
        }

        $conn->commit();
        echo json_encode(["message" => "Registro agregado correctamente"], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["error" => "Error al agregar el registro: " . $e->getMessage()], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(["error" => "Datos incompletos"], JSON_PRETTY_PRINT);
}

