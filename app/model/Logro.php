<?php

class Logro
{
    private $db;

    public function __construct(Database $con)
    {
        $this->db = $con->getConexion();
    }

    public function countLogro()
    {
        $query = "SELECT COUNT(id_logro) AS 'N° Total Logros' FROM logro;";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("Error en preparar la Consulta " . $this->db->error);
            return false;
        }
        $stm->execute();
        $result = $stm->get_result();
        if (!$result || $result->num_rows === 0) {
            error_log("Hubo un problema al generar el total de logros " . $stm->error);
            return null;
        }
        $countLogro = $result->fetch_assoc();
        $result->free();
        $stm->close();

        return $countLogro;
    }

    public function getData()
    {
        $query = "SELECT * FROM logro;";
        $stm = $this->db->query($query);

        if (!$stm) {
            error_log("Ocurrio un error al preparar la consulta " . $this->db->error);
            return false;
        }

        $listMiembro = $stm->fetch_all(MYSQLI_ASSOC);

        if (empty($listMiembro)) {
            error_log("No se encontraron registros en la tabla miembro " . $this->db->error);
            return null;
        }

        $stm->free();
        return $listMiembro;
    }

    public function postData($titulo, $descripcion)
    {

        $query = "INSERT INTO logro (titulo,descripcion) VALUES (?,?)";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("hubo un error al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param("ss", $titulo, $descripcion);
        if (!$stm->execute()) {
            error_log("Hubo un error al inserta el registro miembro" . $stm->error);
            return null;
        }

        $stm->close();
        return true;
    }



    public function putData(int $id, $titulo, $descripcion)
    {
        $query = "UPDATE logro SET  titulo=? , descripcion=? WHERE id_logro = ?";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un problema al preparar la query" . $this->db->error);
            return false;
        }
        $stm->bind_param("ssi",  $titulo, $descripcion, $id);
        if (!$stm->execute()) {
            error_log("Ocurrio un error al actualizar el registro con ID " . $id . ": " . $stm->error);
            return false;
        }
        $stm->close();
        return true;
    }

    public function deleteData(int $id)
    {
        $query = "DELETE FROM logro WHERE  id_logro = ?";
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
