<?php
class Contacto
{

    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConexion();
    }


    public function getDataById($id)
    {
        $query = "SELECT * FROM contacto WHERE id_contacto= ? ";
        $stm = $this->db->prepare($query);

        if (!$stm) {
            error_log("Ocurrio un problema al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param('i', $id);

        $stm->execute();
        $result = $stm->get_result();

        if (!$result || $result->num_rows === 0) {
            error_log("El Contacto con ID " . $id . " No Existe");
            return null;
        }
        $findById = $result->fetch_assoc();

        $result->free();
        $stm->close();
        return $findById;
    }

    public function postData($numContacto, $correo, $lugar)
    {

        $query = "INSERT INTO contacto (num_contacto,correo,lugar) VALUES (?,?,?)";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("hubo un error al preparar la consulta" . $this->db->error);
            return false;
        }

        $stm->bind_param("sss", $numContacto, $correo, $lugar);
        if (!$stm->execute()) {
            error_log("Hubo un error al inserta el Contacto del miembro" . $stm->error);
            return null;
        }

        $stm->close();
        return true;
    }

    public function putData($id_contacto, $num_contacto, $correo, $lugar)
    {

        $query = "UPDATE contacto SET  num_contacto=? , correo=? , lugar=? WHERE id_contacto = ?";
        $stm = $this->db->prepare($query);
        if (!$stm) {
            error_log("Ocurrio un problema al preparar la query" . $this->db->error);
            return false;
        }
        $stm->bind_param("sssi", $num_contacto, $correo, $lugar, $id_contacto);
        if (!$stm->execute()) {
            error_log("Ocurrio un error al actualizar el registro con ID " . $id_contacto . ": " . $stm->error);
            return null;
        }
        $stm->close();
        return true;
    }
}
