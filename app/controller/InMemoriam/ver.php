<?php
require_once __DIR__ . '/../../../config/Database.php';

$db = new Database();
$conn = $db->getConexion();

header("Content-Type: application/json");

$query = "SELECT im.id_inmemoriam, m.nombres, m.fecha_nac, im.fecha_fallecimiento, im.descripcion FROM InMemoriam im INNER JOIN Miembro m ON im.id_miembro = m.id_miembro";
$result = $conn->query($query);

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode(!empty($data) ? $data : ["error" => "No hay registros"], JSON_PRETTY_PRINT);
} else {
    echo json_encode(["error" => "Error en la consulta"], JSON_PRETTY_PRINT);
}
