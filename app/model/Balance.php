<?php

class Balance
{
    private $db;

    public function __construct(Database $db)
    {

        $this->db = $db->getConexion();
    }

    public function getAll()
    {

        $query = "SELECT * FROM balances";
        $result = $this->db->query($query);

        $balances = [];

        while ($row = $result->fetch_assoc()) {
            $balances[] = $row;
        }

        return $balances;
    }

    public function getBalanceById(int $id)
    {
        $query = "SELECT * FROM balances WHERE id_balance = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $balance = $result->fetch_assoc();
        $stmt->close();

        return $balance ?: null;
    }

    public function create($descripcion, $debe, $haber, $fecha)
    {
        $query = "INSERT INTO balances (descripcion, debe, haber, fecha) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sdds", $descripcion, $debe, $haber, $fecha);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function update(int $id, $descripcion, $debe, $haber, $fecha)
    {
        $query = "UPDATE balances SET descripcion = ?, debe = ?, haber = ?, fecha = ? WHERE id_balance = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sddsi", $descripcion, $debe, $haber, $fecha, $id);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function delete(int $id)
    {
        $query = "DELETE FROM balances WHERE id_balance = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
