<?php

class Asociado
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConexion();
    }

    public function getAll()
    {
        $query = "SELECT * FROM Asociado";
        $result = $this->db->query($query);

        $asociados = [];
        while ($row = $result->fetch_assoc()) {
            $asociados[] = $row;
        }
        return $asociados;
    }

    public function getById(int $id)
    {
        $query = "SELECT * FROM Asociado WHERE id_asociado = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($nombre_completo, $lugar): bool|int|string {
        $query = "INSERT INTO Asociado (nombre_completo, lugar) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $nombre_completo, $lugar);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update($id, $nombre_completo, $lugar) {
        $query = "UPDATE Asociado SET nombre_completo = ?, lugar = ? WHERE id_asociado = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $nombre_completo, $lugar, $id);
        return $stmt->execute();
    }

    public function delete(int $id)
    {
        $query = "DELETE FROM Asociado WHERE id_asociado = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function associateAportacion($id_asociado, $id_aportacion)
    {
        $query = "INSERT INTO Aportaciones_Asociados (id_aportacion, id_asociado) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id_aportacion, $id_asociado);
        return $stmt->execute();
    }
}
