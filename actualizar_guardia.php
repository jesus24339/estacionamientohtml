<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "";
$database = "elsa";

$id = $_POST['id'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$apellido_paterno = $_POST['apellido_paterno'] ?? '';
$apellido_materno = $_POST['apellido_materno'] ?? '';
$matricula = $_POST['matricula'] ?? '';
$turno = $_POST['turno'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

try {
    $conexion = new mysqli($host, $user, $password, $database);
    
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }
    
    $query = "UPDATE guardias SET 
              nombre = ?, apellido_paterno = ?, apellido_materno = ?,
              matricula = ?, turno = ?, contrasena = ?
              WHERE id = ?";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssssssi", 
        $nombre, $apellido_paterno, $apellido_materno,
        $matricula, $turno, $contrasena, $id
    );
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Guardia actualizado correctamente']);
    } else {
        throw new Exception("Error al actualizar guardia: " . $stmt->error);
    }
    
    $stmt->close();
    $conexion->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>