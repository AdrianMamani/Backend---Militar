<?php
class Contacto
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConexion();
    }

    public function getAll()
    {
        $query = "SELECT * FROM Contacto";
        $result = $this->db->query($query);

        $contactos = [];
        while ($row = $result->fetch_assoc()) {
            $contactos[] = $row;
        }
        return $contactos;
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM Contacto WHERE id_contacto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($num_contacto, $correo, $lugar)
    {
        $query = "INSERT INTO Contacto (num_contacto, correo, lugar) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $num_contacto, $correo, $lugar);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update(int $id, $num_contacto, $correo, $lugar)
    {
        $query = "UPDATE Contacto SET num_contacto = ?, correo = ?, lugar = ? WHERE id_contacto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssi", $num_contacto, $correo, $lugar, $id);

        if ($stmt->affected_rows === 0) {
            error_log("No Existe el ID $id");
            return null;
        }

        return $stmt->execute();
    }

    public function delete(int $id)
    {
        $query = "DELETE FROM Contacto WHERE id_contacto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
