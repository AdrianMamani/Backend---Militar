<?php
class Persona
{
    private $db;

    public function __construct(Database $conn)
    {
        $this->db = $conn->getConexion();
    }

    public function getData()
    {
        $query = "SELECT * FROM persona;";
        $stm = $this->db->query($query);

        if (!$stm) {
            error_log("Ocurrio un error al preparar la consulta " . $this->db->error);
            return false;
        }

        $listPersona = $stm->fetch_all(MYSQLI_ASSOC);

        if (empty($listPersona)) {
            error_log("No se encontraron registros en la tabla Persona " . $this->db->error);
            return null;
        }

        $stm->free();
        return $listPersona;
    }


    public function getDataById(int $id)
    {
        $query = "SELECT * FROM persona WHERE id_personas= ? ";
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

    public function postData($contacto, $nombres)
    {

        $query = "INSERT INTO persona (contacto,nombres) VALUES (?,?)";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("hubo un error al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param("ss", $contacto, $nombres);
        if (!$stm->execute()) {
            error_log("Hubo un error al insertar el registro Persona" . $stm->error);
            return null;
        }

        $stm->close();
        return true;
    }

    public function putData(int $id, $contacto, $nombres)
    {
        $query = "UPDATE persona SET  contacto=? , nombres=? WHERE id_personas = ?";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un problema al preparar la query" . $this->db->error);
            return false;
        }
        $stm->bind_param("ssi",  $contacto, $nombres, $id);
        if (!$stm->execute()) {
            error_log("Ocurrio un error al actualizar el registro con ID " . $id . ": " . $stm->error);
            return false;
        }
        $stm->close();
        return true;
    }

    public function deleteData(int $id)
    {
        $query = "DELETE FROM persona WHERE  id_personas = ?";
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
