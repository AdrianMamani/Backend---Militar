<?php 

class Categoria {
    private $db;

    private $id;
    private $descripcion;
    private $nombre;

    public function __construct(Database $db) {
        $this->db = $db->getConexion();
    }

    public function getAll(): array {
        $query = "SELECT * FROM Categoria";
        $result = $this->db->query($query);

        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }

        return $categorias;
    }

    public function getById(int $id): ?array {
        $query = "SELECT * FROM Categoria WHERE id_categoria = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $categoria = $result->fetch_assoc();
        $stmt->close();

        return $categoria ?: null;
    }

    public function create(string $nombre, string $descripcion): bool {
        $query = "INSERT INTO Categoria (nombre, descripcion) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $nombre, $descripcion);
        
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function update(int $id, string $nombre, string $descripcion): bool {
        $query = "UPDATE Categoria SET nombre = ?, descripcion = ? WHERE id_categoria = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);
        
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function delete(int $id): bool {
        $query = "DELETE FROM Categoria WHERE id_categoria = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}

?>