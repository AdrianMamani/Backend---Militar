<?php

class Miembro
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConexion();
    }

    public function countMiembro($estado)
    {
        $query = "SELECT COUNT(id_miembro)AS 'N° Total Miembros'  FROM miembro WHERE estado = ?;";
        $stm = $this->db->prepare($query);

        if (!$stm) {
            error_log("Hubo un Error al Preparar la Consulta " . $this->db->error);
            return false;
        }

        $stm->bind_param('s', $estado);
        $stm->execute();
        $result = $stm->get_result();
        if (!$result || $result->num_rows === 0) {
            error_log("No se Encontraron registros con el estado $estado " . $stm->error);
            return null;
        }
        $count =  $result->fetch_assoc();
        $result->free();
        $stm->close();

        return $count;
    }

    public function getData()
    {
        $query = "CALL listMiembrosActivos();";
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

    public function getDataById(int $id)
    {
        error_log("ID recibido en el Model: $id");
        //$query = "SELECT * FROM miembro WHERE id_miembro= ? ";
        $query = "call miembrosById(?); ";
        $stm = $this->db->prepare($query);

        if (!$stm) {
            error_log("Ocurrio un problema al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param('i', $id);

        $stm->execute();
        $result = $stm->get_result();

        if (!$result || $result->num_rows === 0) {
            error_log("El Miembro con ID " . $id . " No Existe");
            return null;
        }
        $findById = $result->fetch_assoc();

        $result->free();
        $stm->close();
        return $findById;
    }

    public function getDataMiembroById($id)
    {
        $query = "SELECT * FROM miembro WHERE id_miembro= ? ";
        //$query = "call miembrosById(?); ";
        $stm = $this->db->prepare($query);

        if (!$stm) {
            error_log("Ocurrio un problema al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param('i', $id);

        $stm->execute();
        $result = $stm->get_result();

        if (!$result || $result->num_rows === 0) {
            error_log("El Miembro con ID " . $id . " No Existe");
            return null;
        }
        $findById = $result->fetch_assoc();

        $result->free();
        $stm->close();
        return $findById;
    }


    public function postData($id_promocion, $id_especialidad, $id_contacto, $id_usuario, $nombres, $fecha_nac, $cargo, $descripcion, $estado)
    {

        $query = "INSERT INTO miembro (id_promocion,id_especialidad,id_contacto,id_usuario,nombres,fecha_nac,cargo,descripcion,estado)
        VALUES (?,?,?,?,?,?,?,?,?)";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("hubo un error al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param("iiiisssss", $id_promocion, $id_especialidad, $id_contacto, $id_usuario, $nombres, $fecha_nac, $cargo, $descripcion, $estado);
        if (!$stm->execute()) {
            error_log("Hubo un error al inserta el registro miembro" . $stm->error);
            return false;
        }

        $stm->close();
        return true;
    }

    public function putData(int $id, $nombres, $cargo, $descripcion)
    {
        // $query = "UPDATE miembro SET id_promocion=? , id_especialidad=? , id_contacto=? , id_usuario=? , nombres=? , fecha_nac=? , cargo=? , descripcion= ? , estado=? WHERE id_miembro = ?";

        $query = "UPDATE miembro SET  nombres=? , cargo=? , descripcion= ?  WHERE id_miembro = ?";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un problema al preparar la query" . $this->db->error);
            return false;
        }
        $stm->bind_param("sssi", $nombres, $cargo, $descripcion, $id);
        if (!$stm->execute()) {
            error_log("Ocurrio un error al actualizar el registro con ID " . $id . ": " . $stm->error);
            return false;
        }

        if ($stm->affected_rows === 0) {
            error_log("El Id $id No Existe");
            return null;
        }
        $stm->close();
        return true;
    }

    public function deleteData(int $id)
    {
        $query = "DELETE FROM miembro WHERE  id_miembro = ?";
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
