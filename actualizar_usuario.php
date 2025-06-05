<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "";
$database = "elsa";

$id = $_POST['id'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$apellido_paterno = $_POST['apellido_paterno'] ?? '';
$apellido_materno = $_POST['apellido_materno'] ?? '';
$matricula = $_POST['matricula'] ?? '';
$vehiculo = $_POST['vehiculo'] ?? '';
$placa = $_POST['placa'] ?? '';
$marca = $_POST['marca'] ?? '';
$modelo = $_POST['modelo'] ?? '';
$color = $_POST['color'] ?? '';
$anio = $_POST['anio'] ?? '';

try {
    $conexion = new mysqli($host, $user, $password, $database);
    
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }
    
    $query = "UPDATE usuarios SET 
              tipo = ?, nombre = ?, apellido_paterno = ?, apellido_materno = ?,
              matricula = ?, vehiculo = ?, placa = ?, marca = ?,
              modelo = ?, color = ?, anio = ?
              WHERE id = ?";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sssssssssssi", 
        $tipo, $nombre, $apellido_paterno, $apellido_materno,
        $matricula, $vehiculo, $placa, $marca,
        $modelo, $color, $anio, $id
    );
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente']);
    } else {
        throw new Exception("Error al actualizar usuario: " . $stmt->error);
    }
    
    $stmt->close();
    $conexion->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>