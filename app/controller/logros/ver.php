<?php
require_once __DIR__ . '/../../../config/Database.php';

header("Content-Type: application/json");

$db = new Database();
$conn = $db->getConexion();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT 
                l.id_logro, 
                l.titulo, 
                l.descripcion, 
                m.nombres AS nombre_miembro, 
                ml.fecha, 
                g.ruta_archivo AS imagen 
            FROM Logro l
            INNER JOIN Miembros_Logros ml ON l.id_logro = ml.id_logro
            INNER JOIN Miembro m ON ml.id_miembro = m.id_miembro
            LEFT JOIN Galeria g ON ml.id_galeria = g.id_galeria";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $logros = [];
        while ($row = $result->fetch_assoc()) {
            $logros[] = $row;
        }
        echo json_encode($logros, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(["message" => "No se encontraron logros"], JSON_PRETTY_PRINT);
    }
} else {
    echo json_encode(["error" => "Método no permitido"], JSON_PRETTY_PRINT);
}
