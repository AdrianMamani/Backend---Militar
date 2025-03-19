<?php

class InMemoriam
{
    private $db;

    public function __construct(Database $conn)
    {
        $this->db = $conn->getConexion();
    }

    // 🔹 Obtener todos los registros de InMemoriam con imágenes y logros
    public function getData()
    {
        $query = "
            SELECT im.id_inmemoriam, im.id_miembro, im.fecha_fallecimiento, im.descripcion,
       g.id_galeria, g.ruta_archivo AS imagen, -- Corrección aquí
       l.id_logro, l.titulo AS titulo_logro, l.descripcion AS descripcion_logro
FROM InMemoriam im
LEFT JOIN Galeria g ON im.id_miembro = g.id_miembro
LEFT JOIN Miembros_Logros ml ON im.id_miembro = ml.id_miembro
LEFT JOIN Logro l ON ml.id_logro = l.id_logro;
        ";

        $stm = $this->db->query($query);

        if (!$stm) {
            error_log("Error al obtener registros de InMemoriam: " . $this->db->error);
            return false;
        }

        $listInMemoriam = [];
        while ($row = $stm->fetch_assoc()) {
            $idMemoriam = $row['id_inmemoriam'];
            if (!isset($listInMemoriam[$idMemoriam])) {
                $listInMemoriam[$idMemoriam] = [
                    "id_memoriam" => $row["id_inmemoriam"],
                    "id_miembro" => $row["id_miembro"],
                    "fecha_fallecimiento" => $row["fecha_fallecimiento"],
                    "descripcion" => $row["descripcion"],
                    "imagenes" => [],
                    "logros" => []
                ];
            }

            if (!empty($row["imagen"])) {
                $listInMemoriam[$idMemoriam]["imagenes"][] = [
                    "id_galeria" => $row["id_galeria"],
                    "imagen" => $row["imagen"]
                ];
            }

            if (!empty($row["id_logro"])) {
                $listInMemoriam[$idMemoriam]["logros"][] = [
                    "id_logro" => $row["id_logro"],
                    "titulo" => $row["titulo_logro"],
                    "descripcion" => $row["descripcion_logro"]
                ];
            }
        }

        return array_values($listInMemoriam);
    }

    // 🔹 Obtener un registro por ID con logros e imágenes
    public function getById(int $id)
{
    $query = "
        SELECT im.id_inmemoriam, im.fecha_fallecimiento, im.descripcion, 
               m.nombres, m.fecha_nac,
               l.id_logro, l.titulo, l.descripcion AS descripcion_logro,
               g.id_galeria, g.ruta_archivo AS imagen
        FROM InMemoriam im
        LEFT JOIN Miembro m ON im.id_miembro = m.id_miembro
        LEFT JOIN Miembros_Logros ml ON m.id_miembro = ml.id_miembro
        LEFT JOIN Logro l ON ml.id_logro = l.id_logro
        LEFT JOIN Galeria g ON m.id_miembro = g.id_miembro
        WHERE im.id_inmemoriam = ?;
    ";

    $stmt = $this->db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = null;
    while ($row = $result->fetch_assoc()) {
        if (!$data) {
            $data = [
                "id_memoriam" => $row["id_inmemoriam"],
                "fecha_fallecimiento" => $row["fecha_fallecimiento"],
                "descripcion" => $row["descripcion"],
                "nombres" => $row["nombres"],
                "fecha_nac" => $row["fecha_nac"],
                "logros" => [],
                "imagenes" => []
            ];
        }
        if (!empty($row["id_logro"])) {
            $data["logros"][] = [
                "id_logro" => $row["id_logro"],
                "titulo" => $row["titulo"],
                "descripcion" => $row["descripcion_logro"]
            ];
        }
        if (!empty($row["imagen"])) {
            $data["imagenes"][] = [
                "id_galeria" => $row["id_galeria"],
                "imagen" => $row["imagen"]
            ];
        }
    }

    return $data ?: ["message" => "No se encontró el registro con ID $id"];
}

    // 🔹 Insertar nuevo registro en InMemoriam
    public function postData($idMiembro, $fechaFallecimiento, $descripcion)
    {
        $query = "INSERT INTO InMemoriam (id_miembro, fecha_fallecimiento, descripcion) VALUES (?, ?, ?)";
        $stm = $this->db->prepare($query);

        if (!$stm) {
            error_log("Error al preparar la consulta: " . $this->db->error);
            return false;
        }

        $stm->bind_param("iss", $idMiembro, $fechaFallecimiento, $descripcion);

        if (!$stm->execute()) {
            error_log("Error al insertar el registro en InMemoriam: " . $stm->error);
            return null;
        }

        $stm->close();
        return true;
    }

    // 🔹 Actualizar un registro en InMemoriam
    public function updateData($idMemoriam, $fechaFallecimiento, $descripcion)
    {
        $query = "UPDATE InMemoriam SET fecha_fallecimiento = ?, descripcion = ? WHERE id_inmemoriam = ?";
        $stm = $this->db->prepare($query);

        if (!$stm) {
            error_log("Error al preparar la consulta: " . $this->db->error);
            return false;
        }

        $stm->bind_param("ssi", $fechaFallecimiento, $descripcion, $idMemoriam);

        if (!$stm->execute()) {
            error_log("Error al actualizar el registro en InMemoriam: " . $stm->error);
            return null;
        }

        if ($stm->affected_rows === 0) {
            error_log("No se encontró el registro con ID $idMemoriam");
            return null;
        }

        $stm->close();
        return true;
    }

    // 🔹 Eliminar un registro en InMemoriam
    public function deleteData(int $idMemoriam)
    {
        $query = "DELETE FROM InMemoriam WHERE id_inmemoriam = ?";
        $stm = $this->db->prepare($query);

        if (!$stm) {
            error_log("Error al preparar la consulta: " . $this->db->error);
            return false;
        }

        $stm->bind_param("i", $idMemoriam);

        if (!$stm->execute()) {
            error_log("Error al eliminar el registro en InMemoriam: " . $stm->error);
            return false;
        }

        if ($stm->affected_rows === 0) {
            error_log("No se encontró el registro con ID $idMemoriam");
            return null;
        }

        $stm->close();
        return true;
    }
}

?>
