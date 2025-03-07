<?php
class NoticiaPersona
{

    private $db;

    public function __construct(Database $conn)
    {
        $this->db = $conn->getConexion();
    }


    public function getById(int $id)
    {
        $query = "CALL ListNoticiaPersonaByIdNoticia(?);";
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


    public function postData($id_noticia, $id_persona)
    {

        $query = "INSERT INTO noticia_persona (id_noticia, id_persona) VALUES (?,?);";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("hubo un error al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param("ii", $id_noticia, $id_persona);
        if (!$stm->execute()) {
            error_log("Hubo un error al inserta la Persona a la Noticia" . $stm->error);
            return null;
        }

        $stm->close();
        return true;
    }



    public function deleteData(int $idPersona, int $idNoticia)
    {
        $query = "DELETE FROM noticia_persona WHERE id_persona = ? and id_noticia= ?;";
        $stm =  $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un error al prepara la consulta" . $this->db->error);
            return false;
        }
        $stm->bind_param('ii', $idPersona, $idNoticia);
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
