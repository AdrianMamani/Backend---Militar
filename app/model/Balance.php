<?php

class Balance{
    private $db;

    public function __construct(Database $db){

        $this -> db = $db ->getConexion();

    }

    public function getAll(){

        $query = "SELECT * FROM Balance";
        $result = $this -> db ->query($query);

        $balances = [];

        while ($row = $result-> fetch_assoc()) {
                $balances[] = $row;
        }

        return $balances;
    }

    public function getBalanceById($id){
        $query = "SELECT * FROM Balance WHERE id_balance = ?";
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
        $query = "INSERT INTO Balance (descripcion, debe, haber, fecha) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sdds", $descripcion, $debe, $haber, $fecha);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function update($id, $descripcion, $debe, $haber, $fecha)
    {
        $query = "UPDATE Balance SET descripcion = ?, debe = ?, haber = ?, fecha = ? WHERE id_balance = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sddsi", $descripcion, $debe, $haber, $fecha, $id);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function delete($id)
    {
        $query = "DELETE FROM Balance WHERE id_balance = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
    
}

?>