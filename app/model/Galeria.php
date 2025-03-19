<?php
class ModeloGaleria {
    private $db;

    public function __construct(Database $conn) {
        $this->db = $conn->getConexion();
    }

    // 🔹 SUBIR IMAGEN AL SERVIDOR Y GUARDAR RUTA
    public function subirImagen($archivo) {
        if (!is_array($archivo) || !isset($archivo["name"]) || !isset($archivo["tmp_name"])) {
            return ["error" => "Archivo inválido. No se recibió correctamente."];
        }

        $directorio = __DIR__ . "/../img/";
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $nombreArchivo = uniqid() . "_" . basename($archivo["name"]);
        $rutaCompleta = $directorio . $nombreArchivo;
        $tipoArchivo = strtolower(pathinfo($rutaCompleta, PATHINFO_EXTENSION));

        $formatosPermitidos = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($tipoArchivo, $formatosPermitidos)) {
            return ["error" => "Formato de imagen no permitido."];
        }

        if (move_uploaded_file($archivo["tmp_name"], $rutaCompleta)) {
            return ["success" => true, "ruta" => "img/" . $nombreArchivo, "tipo" => $tipoArchivo];
        } else {
            return ["error" => "Error al mover el archivo."];
        }
    }

    // 🔹 OBTENER TODAS LAS IMÁGENES
    public function obtenerGaleria() {
        $query = "SELECT * FROM galeria";
        $stm = $this->db->query($query);
        return $stm ? $stm->fetch_all(MYSQLI_ASSOC) : false;
    }

    // 🔹 OBTENER IMAGEN POR ID
    public function obtenerPorId(int $id) {
        $query = "SELECT * FROM galeria WHERE id_galeria = ?";
        $stm = $this->db->prepare($query);
        $stm->bind_param("i", $id);
        $stm->execute();
        return $stm->get_result()->fetch_assoc();
    }

    // 🔹 AGREGAR UNA NUEVA IMAGEN
    public function agregarImagen($id_promocion, $id_miembro, $id_noticia, $id_evento, $imagen, $informacion)
{
    $nombreArchivo = basename($imagen['name']);
    $directorioDestino = __DIR__ . "/../img/";
    $rutaArchivo = $directorioDestino . $nombreArchivo;
    $rutaBD = "img/" . $nombreArchivo; // 📌 Ruta para la BD

    // Validar tipo de archivo
    $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

    if (!in_array($extension, $extensionesPermitidas)) {
        return ["error" => "Formato de imagen no permitido"];
    }

    // 📂 Mover la imagen a la carpeta
    if (!move_uploaded_file($imagen['tmp_name'], $rutaArchivo)) {
        return ["error" => "No se pudo guardar la imagen"];
    }

    // 📝 Insertar en la BD
    $query = "INSERT INTO galeria (id_promocion, id_miembro, id_noticia, id_evento, tipo_archivo, ruta_archivo, informacion) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->db->prepare($query);
    
    if (!$stmt) {
        return ["error" => "Error en la preparación: " . $this->db->error]; // ❌ Error en prepare()
    }

    $stmt->bind_param("iiiiiss", $id_promocion, $id_miembro, $id_noticia, $id_evento, $extension, $rutaBD, $informacion);

    if (!$stmt->execute()) {
        return ["error" => "Error en la ejecución: " . $stmt->error]; // ❌ Error en execute()
    }

    return ["ruta" => $rutaBD]; // ✅ Ruta correcta
}

    // 🔹 ACTUALIZAR INFORMACIÓN DE UNA IMAGEN
    public function actualizarImagen($id_galeria, $id_promocion, $id_miembro, $id_noticia, $id_evento, $archivo, $informacion) {
        $imagenActual = $this->obtenerPorId($id_galeria);
        if (!$imagenActual) return ["error" => "Imagen no encontrada."];

        if (!empty($archivo["name"])) {
            $subida = $this->subirImagen($archivo);
            if (isset($subida["error"])) return $subida;
            $ruta_archivo = $subida["ruta"];
            $tipo_archivo = $subida["tipo"];
        } else {
            $ruta_archivo = $imagenActual["ruta_archivo"];
            $tipo_archivo = $imagenActual["tipo_archivo"];
        }

        $query = "UPDATE galeria SET id_promocion = ?, id_miembro = ?, id_noticia = ?, id_evento = ?, tipo_archivo = ?, ruta_archivo = ?, informacion = ? 
                  WHERE id_galeria = ?";
        $stm = $this->db->prepare($query);
        $stm->bind_param("iiissssi", $id_promocion, $id_miembro, $id_noticia, $id_evento, $tipo_archivo, $ruta_archivo, $informacion, $id_galeria);
        return $stm->execute() ? ["success" => true, "message" => "Imagen actualizada."] : false;
    }

    // 🔹 ELIMINAR UNA IMAGEN
    public function eliminarImagen(int $id_galeria) {
        $imagen = $this->obtenerPorId($id_galeria);
        if (!$imagen) return ["error" => "No se encontró la imagen."];

        $query = "DELETE FROM galeria WHERE id_galeria = ?";
        $stm = $this->db->prepare($query);
        $stm->bind_param("i", $id_galeria);
        $stm->execute();

        if ($stm->affected_rows > 0) {
            $rutaArchivo = __DIR__ . "/../" . $imagen["ruta_archivo"];
            if (file_exists($rutaArchivo)) unlink($rutaArchivo);
            return ["success" => true, "message" => "Imagen eliminada."];
        }
        return ["error" => "No se pudo eliminar la imagen."];
    }
}
?>
