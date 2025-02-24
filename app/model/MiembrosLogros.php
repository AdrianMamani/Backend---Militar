<?php
class MiembrosLogros
{
    private $db;

    public function __construct(Database $conn)
    {
        $this->db = $conn->getConexion();
    }


    public function getById($id)
    {
        $query = "CALL listMiembroLogros(?);";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function postData($idMiembro, $idLogro, $idGaleria, $fecha)
    {

        $query = "INSERT INTO miembros_logros (id_miembro,id_logro,id_galeria, fecha) VALUES (?,?,?,?);";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("hubo un error al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param("iiis", $idMiembro, $idLogro, $idGaleria, $fecha);
        if (!$stm->execute()) {
            error_log("Hubo un error al insertar el Logro del Miembro" . $stm->error);
            return null;
        }

        $stm->close();
        return true;
    }


    public function deleteData($idLogro, $idMiembro)
    {
        $query = "DELETE FROM miembros_logros WHERE id_logro = ? AND id_miembro = ?;";
        $stm =  $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un error al prepara la consulta" . $this->db->error);
            return false;
        }
        $stm->bind_param('ii', $idLogro, $idMiembro);
        if (!$stm->execute()) {
            error_log("No se pudo eliminar el registro con ID " . $idLogro . ": " . $stm->error);
            return false;
        }
        if ($stm->affected_rows ===  0) {
            error_log("No se encontro el registro con ID $idLogro");
            return null;
        }

        $stm->close();
        return true;
    }
}
