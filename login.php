<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../mysq/conector.php';  
session_start(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_usuario = $_POST['nombre_usuario']; 
    $contrasena = $_POST['password']; 
    
    $sql = "SELECT * FROM Usuario WHERE nombre_usuario = ? AND contrasena = PASSWORD(?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ss", $nombre_usuario, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['usuario'] = $user['nombre_usuario'];
        header("Location: /api/index.php");
        exit();
    } else {
        $error = "¡Usuario o contraseña incorrectos!";
    }
    
    $stmt->close();
}
?>

