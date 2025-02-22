<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

// Importar la clase de conexión
require_once "../mysq/conector.php"; // Ajusta la ruta si es necesario

// Crear instancia de la base de datos
$db = new Database();
$conn = $db->getConexion();

// Verificar la conexión
if (!$conn) {
    die(json_encode(["error" => "Error en la conexión a la base de datos"]));
}

// Consulta SQL para obtener datos de InMemoriam y sus logros
$sql = "SELECT 
            m.id_miembro,
            m.nombres AS nombre,
            m.fecha_nac AS fecha_nacimiento,
            i.fecha_fallecimiento,
            i.descripcion AS descripcion_fallecimiento,
            l.titulo AS logro,
            ml.fecha AS fecha_logro
        FROM InMemoriam i
        JOIN Miembro m ON i.id_miembro = m.id_miembro
        LEFT JOIN Miembros_Logros ml ON m.id_miembro = ml.id_miembro
        LEFT JOIN Logro l ON ml.id_logro = l.id_logro
        ORDER BY m.id_miembro, ml.fecha";

$result = $conn->query($sql);

$data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $id_miembro = $row['id_miembro'];

        // Si el miembro no está en la lista, se agrega
        if (!isset($data[$id_miembro])) {
            $data[$id_miembro] = [
                "nombre" => $row['nombre'],
                "fecha_nacimiento" => $row['fecha_nacimiento'],
                "fecha_fallecimiento" => $row['fecha_fallecimiento'],
                "descripcion_fallecimiento" => $row['descripcion_fallecimiento'],
                "logros" => [] // Array para los logros
            ];
        }

        // Si hay logros, se agregan al array
        if ($row['logro']) {
            $data[$id_miembro]["logros"][] = [
                "titulo" => $row['logro'],
                "fecha" => $row['fecha_logro']
            ];
        }
    }
    
    // Convertir el array asociativo a un array indexado para JSON
    echo json_encode(array_values($data), JSON_PRETTY_PRINT);
} else {
    echo json_encode(["error" => "Error en la consulta: " . $conn->error]);
}

// Cerrar conexión
$conn->close();
?>
