<?php 
class Deuda{

    private $db;

    public function __construct(Database $db){
        $this->db = $db ->getConexion();
    }



    public function getAll() {
        $query = "SELECT * FROM listarDeudasAsociados";
        $result = $this->db->query($query);
        
        $deudas = [];
        while ($row = $result->fetch_assoc()) {
            $deudas[] = $row;
        }
        return $deudas;
    }

    public function getById($id) {
        $query = "CALL verDeudaAsociado(?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        $result = $stmt->get_result();
        
        $deudas = [];
        while ($row = $result->fetch_assoc()) {
            $deudas[] = $row;
        }
    
        $stmt->close();
    
        return $deudas; 
    }

    public function create($fecha, $monto, $id_asociado) {
        $query = "INSERT INTO asociado_deuda (fecha, monto, id_asociado) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sdi", $fecha, $monto, $id_asociado);
        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update($id_deuda, $fecha, $monto, $id_asociado) {
        $query = "UPDATE asociado_deuda SET fecha = ?, monto = ?, id_asociado = ? WHERE id_deuda = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sdii", $fecha, $monto, $id_asociado, $id_deuda);
        return $stmt->execute();
    }

    public function delete(int $id_deuda) {
        $query = "DELETE FROM asociado_deuda WHERE id_deuda = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_deuda);
        return $stmt->execute();
    }
}
?>