<?php

class EventoMiembro
{

    private $db;

    public function __construct(Database $conn)
    {
        $this->db = $conn->getConexion();
    }
    // LISTAR O ORGANIZADORES POR EL ID DEL EVENTO ---> OK

    public function getById($id)
    {
        $query = "CALL listEventoOrganizador(?);";
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

    // AGREGAR ORGANIZADOR AL EVENTO ASIGNADO  ---> OK

    public function postData($idEvento, $idMiembro)
    {

        $query = "INSERT INTO evento_miembro (id_evento, id_miembro) VALUES (?,?);";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("hubo un error al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param("ii", $idEvento, $idMiembro);
        if (!$stm->execute()) {
            error_log("Hubo un error al insertar el registro Evento Persona" . $stm->error);
            return null;
        }

        $stm->close();
        return true;
    }

    // ACTUALIZAR ORGANIZADOR DEL EVENTO ASIGNADO  ---> OK


    public function putData($idMiembro, $idEvento, $idNuevoMiembro)
    {
        $query = "UPDATE evento_miembro SET id_miembro =? WHERE id_evento= ? AND id_miembro =?;";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un problema al preparar la query" . $this->db->error);
            return false;
        }
        $stm->bind_param("iii",  $idNuevoMiembro, $idEvento, $idMiembro);
        if (!$stm->execute()) {
            error_log("Ocurrio un error al actualizar el registro con ID " . $idNuevoMiembro . ": " . $stm->error);
            return false;
        }
        $stm->close();
        return true;
    }

    // ELIMINAR ORGANIZADOR DEL EVENTO POR ID_ORGANIZADOR Y ID_EVENTO -- OK
    public function deleteData($idMiembro, $idEvento)
    {
        $query = "DELETE FROM evento_miembro where id_miembro = ? and id_evento = ?;";
        $stm =  $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un error al prepara la consulta" . $this->db->error);
            return false;
        }
        $stm->bind_param('ii', $idMiembro, $idEvento);
        if (!$stm->execute()) {
            error_log("No se pudo eliminar el registro con ID " . $idMiembro . ": " . $stm->error);
            return false;
        }
        if ($stm->affected_rows ===  0) {
            error_log("No se encontro el registro con ID $idMiembro");
            return null;
        }

        $stm->close();
        return true;
    }
}
