<?php


class Aportacion
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConexion();
    }

    public function getAll(): array
    {
        $query = "SELECT * FROM Aportacion";
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

    public function create($id_categoria, $id_tesorero, $montos, $total, $fecha_creacion, $fecha_modificacion)
    {
        $query = "INSERT INTO Aportacion (id_categoria, id_tesorero, monto_ene, monto_feb, monto_mar, monto_abr, monto_may, 
                monto_jun, monto_jul, monto_ago, monto_sep, monto_oct, monto_nov, monto_dic, total, fecha_creacion, fecha_modificacion) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "ii" . str_repeat("d", 12) . "dss",
            $id_categoria,
            $id_tesorero,
            $montos['ene'],
            $montos['feb'],
            $montos['mar'],
            $montos['abr'],
            $montos['may'],
            $montos['jun'],
            $montos['jul'],
            $montos['ago'],
            $montos['sep'],
            $montos['oct'],
            $montos['nov'],
            $montos['dic'],
            $total,
            $fecha_creacion,
            $fecha_modificacion
        );


        if ($stmt->execute()) {
            return $this->db->insert_id;
        }
        return false;
    }

    public function update(int $id, int $id_categoria, int $id_tesorero, array $montos, string $lugar, float $total): bool
    {
        $query = "UPDATE Aportacion SET id_categoria = ?, id_tesorero = ?, monto_ene = ?, monto_feb = ?, monto_mar = ?, monto_abr = ?, monto_may = ?, monto_jun = ?, monto_jul = ?, monto_ago = ?, monto_sep = ?, monto_oct = ?, monto_nov = ?, monto_dic = ?, lugar = ?, total = ?, fecha_modificacion = NOW() WHERE id_aportacion = ?";
        $stmt = $this->db->prepare($query);

        // Combina todos los parámetros en un solo array
        $params = array_merge(
            [$id_categoria, $id_tesorero],
            array_values($montos), // Asegúrate de que $montos tenga exactamente 12 elementos
            [$lugar, $total, $id]
        );

        // Usa el desempaquetado en una única llamada
        $stmt->bind_param("iidddddddddddsdi", ...$params);

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
