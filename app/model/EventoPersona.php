<?php

class EventoPersona
{
    private $db;

    public function __construct(Database $conn)
    {
        $this->db = $conn->getConexion();
    }



    public function getData()
    {
        $query = "CALL listEventoPersona();";
        $stm = $this->db->query($query);

        if (!$stm) {
            error_log("Ocurrio un error al preparar la consulta " . $this->db->error);
            return false;
        }

        $listMiembro = $stm->fetch_all(MYSQLI_ASSOC);

        if (empty($listMiembro)) {
            error_log("No se encontraron registros en la tabla Evento Persona " . $this->db->error);
            return null;
        }

        $stm->free();
        return $listMiembro;
    }


    public function getById($id)
    {
        $query = "call listEventoPersonaById(?);";
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


    public function postData($idEvento, $idPersona)
    {

        $query = "INSERT INTO evento_persona (id_evento,id_persona) VALUES (?,?)";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("hubo un error al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param("ii", $idEvento, $idPersona);
        if (!$stm->execute()) {
            error_log("Hubo un error al insertar el registro Evento Persona" . $stm->error);
            return null;
        }

        $stm->close();
        return true;
    }



    public function deleteData($idPersona, $idEvento)
    {
        $query = "DELETE FROM evento_persona WHERE  id_persona = ? AND id_evento = ?";
        $stm =  $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un error al prepara la consulta" . $this->db->error);
            return false;
        }
        $stm->bind_param('ii', $idPersona, $idEvento);
        if (!$stm->execute()) {
            error_log("No se pudo eliminar el registro con ID " . $idPersona . ": " . $stm->error);
            return false;
        }
        if ($stm->affected_rows ===  0) {
            error_log("No se encontro el registro con ID $idPersona");
            return null;
        }

        $stm->close();
        return true;
    }
}
