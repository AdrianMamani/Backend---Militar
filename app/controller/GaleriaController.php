<?php
require_once __DIR__ . '/../model/Galeria.php';
require_once __DIR__ . '/../utils/Response.php';

class ControladorGaleria
{
    private $galeriaModel;

    public function __construct(Database $db)
    {
        $this->galeriaModel = new ModeloGaleria($db);
    }

    // 🔹 Obtener todas las imágenes
    public function listarGaleria()
    {
        $resultGaleria = $this->galeriaModel->obtenerGaleria();
        if ($resultGaleria === null) {
            Response::json(["message" => "No se encontraron registros en la galería"], 404);
        } else {
            Response::json($resultGaleria);
        }
    }

    // 🔹 Obtener imagen por ID
    public function obtenerPorId($id)
    {
        $id = intval($id);
        $result = $this->galeriaModel->obtenerPorId($id);

        if ($result === null) {
            Response::json(["message" => "La imagen con ID $id no existe"], 404);
        } else {
            Response::json($result);
        }
    }

    // 🔹 Agregar una nueva imagen
    public function agregarImagen()
{
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        Response::json(["error" => "Error al subir el archivo"], 400);
        return;
    }

    $imagen = $_FILES['imagen'];
    $id_promocion = !empty($_POST["id_promocion"]) ? intval($_POST["id_promocion"]) : null;
    $id_miembro = !empty($_POST["id_miembro"]) ? intval($_POST["id_miembro"]) : null;
    $id_noticia = !empty($_POST["id_noticia"]) ? intval($_POST["id_noticia"]) : null;
    $id_evento = !empty($_POST["id_evento"]) ? intval($_POST["id_evento"]) : null;
    $informacion = $_POST["informacion"] ?? "";

    // 📂 Guardar en la BD
    $resultado = $this->galeriaModel->agregarImagen(
        $id_promocion, $id_miembro, $id_noticia, $id_evento, $imagen, $informacion
    );

    // 📌 Validar si hay error
    if (isset($resultado["error"])) {
        Response::json(["error" => $resultado["error"]], 500);
        return;
    }

    // ✅ Respuesta correcta
    Response::json(["message" => "Imagen subida correctamente", "ruta" => $resultado["ruta"]]);
}

    // 🔹 Actualizar información de una imagen
    public function actualizarImagen()
    {
        if ($_SERVER["CONTENT_TYPE"] === "application/json") {
            $data = json_decode(file_get_contents("php://input"), true);
        } else {
            Response::json(["message" => "Formato no permitido"], 400);
            return;
        }

        if (!isset($data["id_galeria"])) {
            Response::json(["message" => "ID de la imagen es obligatorio"], 400);
            return;
        }

        // 📄 Obtener datos actualizados
        $id_galeria = intval($data["id_galeria"]);
        $id_promocion = isset($data["id_promocion"]) ? intval($data["id_promocion"]) : null;
        $id_miembro = isset($data["id_miembro"]) ? intval($data["id_miembro"]) : null;
        $id_noticia = isset($data["id_noticia"]) ? intval($data["id_noticia"]) : null;
        $id_evento = isset($data["id_evento"]) ? intval($data["id_evento"]) : null;
        $informacion = $data["informacion"] ?? "";

        // 📂 ¿Se subió una nueva imagen?
        $imagen = $_FILES["imagen"] ?? null;

        // 📝 Actualizar en la BD
        $resultado = $this->galeriaModel->actualizarImagen(
            $id_galeria, $id_promocion, $id_miembro, $id_noticia, $id_evento, $imagen, $informacion
        );

        if (isset($resultado["error"])) {
            Response::json(["error" => $resultado["error"]], 500);
        } else {
            Response::json(["message" => "Imagen actualizada correctamente", "ruta" => $resultado["ruta"]]);
        }
    }

    // 🔹 Eliminar una imagen
    public function eliminarImagen($id)
    {
        $id = intval($id);
        $imagen = $this->galeriaModel->obtenerPorId($id);

        if (!$imagen) {
            Response::json(["message" => "No se encontró la imagen con ID $id"], 404);
            return;
        }

        // Eliminar la imagen del servidor
        $rutaArchivo = __DIR__ . "/../" . $imagen["ruta_archivo"];
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo);
        }

        // 🗑 Eliminar el registro de la BD
        $result = $this->galeriaModel->eliminarImagen($id);

        if ($result === false) {
            Response::json(["message" => "Error al eliminar la imagen con ID $id"], 500);
        } else {
            Response::json(["message" => "Imagen eliminada exitosamente"]);
        }
    }
}
?>
