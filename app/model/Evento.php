<?php
class Evento
{
    private $db;

    public function __construct(Database $conn)
    {
        $this->db = $conn->getConexion();
    }



    public function getData()
    {
        $query = "call listEvento();";
        $stm = $this->db->query($query);

        if (!$stm) {
            error_log("Ocurrio un error al preparar la consulta " . $this->db->error);
            return false;
        }

        $listEvento = $stm->fetch_all(MYSQLI_ASSOC);

        if (empty($listEvento)) {
            error_log("No se encontraron registros en la tabla Evento Persona " . $this->db->error);
            return null;
        }

        $stm->free();
        return $listEvento;
    }


    public function getDataById(int $id)
    {
        $query = "CALL EventoById(?); ";
        $stm = $this->db->prepare($query);

        if (!$stm) {
            error_log("Ocurrio un problema al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param('i', $id);

        $stm->execute();
        $result = $stm->get_result();

        if (!$result || $result->num_rows === 0) {
            error_log("Persona con ID " . $id . " No Existe");
            return null;
        }
        $findById = $result->fetch_assoc();

        $result->free();
        $stm->close();
        return $findById;
    }

    public function putData(int $id, $nombreEvento, $lugar, $fecha, $descripcion)
    {
        $query = "UPDATE evento SET  nombre_evento=?, lugar_evento=? , fecha_evento=?, descripcion=? WHERE id_evento = ?";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un problema al preparar la query" . $this->db->error);
            return false;
        }
        $stm->bind_param("ssssi",  $nombreEvento, $lugar, $fecha, $descripcion, $id);
        if (!$stm->execute()) {
            error_log("Ocurrio un error al actualizar el registro con ID " . $id . ": " . $stm->error);
            return false;
        }

        if ($stm->affected_rows === 0) {
            error_log("No Existe el ID $id");
            return null;
        }

        $stm->close();
        return true;
    }

    public function deleteData(int $id)
    {
        $query = "DELETE FROM evento WHERE id_evento=?;";
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
