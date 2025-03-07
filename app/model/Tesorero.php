<?php

class Tesorero
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConexion();
    }

    public function getAll()
    {
        $query = "SELECT Tesorero.*, Contacto.num_contacto, Contacto.correo, Contacto.lugar 
                  FROM Tesorero 
                  JOIN Contacto ON Tesorero.id_contacto = Contacto.id_contacto";
        $result = $this->db->query($query);

        $tesoreros = [];
        while ($row = $result->fetch_assoc()) {
            $tesoreros[] = $row;
        }
        return $tesoreros;
    }

    public function getById(int $id)
    {
        $query = "SELECT Tesorero.*, Contacto.num_contacto, Contacto.correo, Contacto.lugar 
                  FROM Tesorero 
                  JOIN Contacto ON Tesorero.id_contacto = Contacto.id_contacto
                  WHERE Tesorero.id_tesorero = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($id_contacto, $nombre_completo)
    {
        $query = "INSERT INTO Tesorero (id_contacto, nombre_completo) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("is", $id_contacto, $nombre_completo);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update(int $id, $id_contacto, $nombre_completo)
    {
        $query = "UPDATE Tesorero SET id_contacto = ?, nombre_completo = ? WHERE id_tesorero = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isi", $id_contacto, $nombre_completo, $id);
        return $stmt->execute();
    }

    public function delete(int $id)
    {
        $query = "DELETE FROM Tesorero WHERE id_tesorero = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
