<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "";
$database = "elsa";

try {
    $conexion = new mysqli($host, $user, $password, $database);
    
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }
    
    $query = "SELECT * FROM guardias";
    $resultado = $conexion->query($query);
    
    $guardias = [];
    while ($fila = $resultado->fetch_assoc()) {
        $guardias[] = $fila;
    }
    
    echo json_encode($guardias);
    
    $conexion->close();
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>