<?php


class Aportacion
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConexion();
    }

    public function getAll(): array {
        $query = "SELECT * FROM vista_aportaciones";
        $result = $this->db->query($query);

        $aportaciones = [];
        while ($row = $result->fetch_assoc()) {
            $aportaciones[] = $row;
        }

        return $aportaciones;
    }

    public function getById(int $id): ?array
    {
        $query = "SELECT * FROM Aportacion WHERE id_aportacion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $aportacion = $result->fetch_assoc();
        $stmt->close();

        return $aportacion ?: null;
    }

    public function create($id_categoria, $id_tesorero, $montos, $lugar) {
        $query = "CALL insertarAportacion(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @id_aportacion)";
        $lugar = $lugar ?? '';
    
        // Preparar la consulta
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "ii" . str_repeat("d", 12) . "s",
            $id_categoria, $id_tesorero,
            $montos['ene'], $montos['feb'], $montos['mar'], $montos['abr'], 
            $montos['may'], $montos['jun'], $montos['jul'], $montos['ago'], 
            $montos['sep'], $montos['oct'], $montos['nov'], $montos['dic'],
            $lugar
        );
    
        // Ejecutar el procedimiento
        if ($stmt->execute()) {
            // Obtener el resultado del parámetro de salida
            $result = $this->db->query("SELECT @id_aportacion as id");
            $row = $result->fetch_assoc();
            return $row['id'] ?? false;
        }
    
        return false;
    }

    public function update(int $id, int $id_categoria, int $id_tesorero, array $montos, ?string $lugar): bool {
        $query = "CALL actualizarAportacion(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        $stmt = $this->db->prepare($query);
    
        // Si $lugar es null, lo convertimos a un string vacío
        $lugar = $lugar ?? '';
    
        $params = array_merge(
            [$id, $id_categoria, $id_tesorero],
            array_values($montos),
            [$lugar]
        );
    
        $stmt->bind_param("iiidddddddddddds", ...$params);
    
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function delete(int $id): bool
    {
        $query = "DELETE FROM Aportacion WHERE id_aportacion = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);

        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}
