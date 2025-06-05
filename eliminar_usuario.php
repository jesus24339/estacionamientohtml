<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "";
$database = "elsa";

$id = $_POST['id'] ?? '';

try {
    $conexion = new mysqli($host, $user, $password, $database);
    
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }
    
    $query = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario eliminado correctamente']);
    } else {
        throw new Exception("Error al eliminar usuario: " . $stmt->error);
    }
    
    $stmt->close();
    $conexion->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>