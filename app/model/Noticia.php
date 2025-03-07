<?php
class Noticia
{
    private $db;


    public function __construct(Database $conn)
    {
        $this->db = $conn->getConexion();
    }


    public function countNoticia()
    {
        $query = "SELECT COUNT(id_noticia) AS 'N° Total Noticia' FROM noticia;";
        $stm = $this->db->prepare($query);

        if (!$stm) {
            error_log("Hubo un Error en preparar la consulta " . $this->db->error);
            return false;
        }

        $stm->execute();
        $result = $stm->get_result();
        if (!$result || $result->num_rows === 0) {
            error_log("Hubo un error en consultar el N° Total de logros " . $stm->error);
            return null;
        }
        $countNoticia = $result->fetch_assoc();
        $result->free();
        $stm->close();
        return $countNoticia;
    }

    public function getData()
    {
        $query = "CALL listNoticiasAndPersonas();";
        $stm = $this->db->query($query);

        if (!$stm) {
            error_log("Ocurrio un error al preparar la consulta " . $this->db->error);
            return false;
        }

        $listNoticia = $stm->fetch_all(MYSQLI_ASSOC);

        if (empty($listNoticia)) {
            error_log("No se encontraron registros en la tabla Noticia " . $this->db->error);
            return null;
        }

        $stm->free();
        return $listNoticia;
    }


    public function putData(int $id, $titulo, $descripcion, $fechaPublicacion)
    {
        $query = "UPDATE noticia SET titulo=? , descripcion= ? , fecha_publicacion=?  WHERE id_noticia = ?";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un problema al preparar la query" . $this->db->error);
            return false;
        }
        $stm->bind_param("sssi",  $titulo, $descripcion, $fechaPublicacion, $id);
        if (!$stm->execute()) {
            error_log("Ocurrio un error al actualizar el registro con ID " . $id . ": " . $stm->error);
            return false;
        }
        if ($stm->affected_rows === 0) {
            error_log("No se encontró el registro con ID " . $id . " para actualizar");
            return null;
        }

        $stm->close();
        return true;
    }


    public function deleteData(int $id)
    {
        $query = "DELETE FROM noticia WHERE  id_noticia = ?";
        $stm =  $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un error al prepara la consulta" . $this->db->error);
            return false;
        }
        $stm->bind_param('i', $id);
        if (!$stm->execute()) {
            error_log("No se pudo eliminar el registro con ID " . $id . ": " . $stm->error);
            return false;
        }
        if ($stm->affected_rows ===  0) {
            error_log("No se encontro el registro con ID $id");
            return null;
        }

        $stm->close();
        return true;
    }
}
