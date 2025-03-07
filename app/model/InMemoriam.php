<?php
require_once __DIR__ . '/../../config/Database.php';

class InMemoriam
{
    private $conn;

    public function __construct(Database $db)
    {
        $this->conn = $db->getConexion();
    }

    public function getAll()
    {
        $sql = "SELECT im.*, m.nombres, m.fecha_nac, g.ruta_archivo AS imagen,
                    GROUP_CONCAT(l.titulo SEPARATOR ', ') AS logros
                FROM InMemoriam im
                JOIN Miembro m ON im.id_miembro = m.id_miembro
                LEFT JOIN Galeria g ON m.id_miembro = g.id_miembro
                LEFT JOIN Miembros_Logros ml ON m.id_miembro = ml.id_miembro
                LEFT JOIN Logro l ON ml.id_logro = l.id_logro
                GROUP BY im.id_inmemoriam";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT im.*, m.nombres, m.fecha_nac, g.ruta_archivo AS imagen,
                    GROUP_CONCAT(l.titulo SEPARATOR ', ') AS logros
                FROM InMemoriam im
                JOIN Miembro m ON im.id_miembro = m.id_miembro
                LEFT JOIN Galeria g ON m.id_miembro = g.id_miembro
                LEFT JOIN Miembros_Logros ml ON m.id_miembro = ml.id_miembro
                LEFT JOIN Logro l ON ml.id_logro = l.id_logro
                WHERE im.id_inmemoriam = ?
                GROUP BY im.id_inmemoriam";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function create($nombre_miembro, $fecha_fallecimiento, $descripcion, $imagen, $logros)
    {
        if (!is_array($logros)) {
            $logros = json_decode($logros, true) ?: [];
        }

        $this->conn->begin_transaction();

        try {
            // Insertar Miembro
            $sql_miembro = "INSERT INTO Miembro (nombres) VALUES (?)";
            $stmt = $this->conn->prepare($sql_miembro);
            $stmt->bind_param("s", $nombre_miembro);
            $stmt->execute();
            $id_miembro = $this->conn->insert_id;

            // Insertar en InMemoriam
            $sql = "INSERT INTO InMemoriam (id_miembro, fecha_fallecimiento, descripcion) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iss", $id_miembro, $fecha_fallecimiento, $descripcion);
            $stmt->execute();

            // Insertar Imagen
            if ($imagen) {
                $sql_imagen = "INSERT INTO Galeria (id_miembro, ruta_archivo) VALUES (?, ?)";
                $stmt = $this->conn->prepare($sql_imagen);
                $stmt->bind_param("is", $id_miembro, $imagen);
                $stmt->execute();
            }

            // Insertar Logros
            foreach ($logros as $logro) {
                if (!is_string($logro) && isset($logro['nombre'])) {
                    $logro = $logro['nombre'];
                }

                if ($logro) {
                    $sql_logro = "INSERT INTO Logro (titulo) VALUES (?)";
                    $stmt = $this->conn->prepare($sql_logro);
                    $stmt->bind_param("s", $logro);
                    $stmt->execute();
                    $id_logro = $this->conn->insert_id;

                    $sql_ml = "INSERT INTO Miembros_Logros (id_miembro, id_logro) VALUES (?, ?)";
                    $stmt = $this->conn->prepare($sql_ml);
                    $stmt->bind_param("ii", $id_miembro, $id_logro);
                    $stmt->execute();
                }
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function update($id, $nombre_miembro, $fecha_fallecimiento, $descripcion, $imagen)
    {
        $this->conn->begin_transaction();

        try {
            // Actualizar InMemoriam
            $sql = "UPDATE InMemoriam SET fecha_fallecimiento = ?, descripcion = ? WHERE id_inmemoriam = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssi", $fecha_fallecimiento, $descripcion, $id);
            $stmt->execute();

            // Actualizar Miembro
            $sql_miembro = "UPDATE Miembro SET nombres = ? WHERE id_miembro = (SELECT id_miembro FROM InMemoriam WHERE id_inmemoriam = ?)";
            $stmt = $this->conn->prepare($sql_miembro);
            $stmt->bind_param("si", $nombre_miembro, $id);
            $stmt->execute();

            // Actualizar Imagen solo si hay una nueva
            if ($imagen) {
                $sql_imagen = "UPDATE Galeria SET ruta_archivo = ? WHERE id_miembro = (SELECT id_miembro FROM InMemoriam WHERE id_inmemoriam = ?)";
                $stmt = $this->conn->prepare($sql_imagen);
                $stmt->bind_param("si", $imagen, $id);
                $stmt->execute();
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }

    public function delete($id)
    {
        $this->conn->begin_transaction();

        try {
            // Eliminar primero en InMemoriam
            $sql = "DELETE FROM InMemoriam WHERE id_inmemoriam = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // Eliminar Galeria
            $sql = "DELETE FROM Galeria WHERE id_miembro = (SELECT id_miembro FROM Miembro WHERE id_miembro = (SELECT id_miembro FROM InMemoriam WHERE id_inmemoriam = ?))";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            return false;
        }
    }
}
