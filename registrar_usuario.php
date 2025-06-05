<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "elsa";

// Obtener datos del POST
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

// Validar que todos los campos estén presentes
if (empty($tipo) || empty($nombre) || empty($apellido_paterno) || empty($apellido_materno) || 
    empty($matricula) || empty($vehiculo) || empty($placa) || empty($marca) || 
    empty($modelo) || empty($color) || empty($anio)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

try {
    // Conexión a la base de datos
    $conexion = new mysqli($host, $user, $password, $database);
    
    // Verificar conexión
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }
    
    // Insertar datos en la tabla usuarios
    $query = "INSERT INTO usuarios 
              (tipo, nombre, apellido_paterno, apellido_materno, matricula, vehiculo, placa, marca, modelo, color, anio) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sssssssssss", 
        $tipo, $nombre, $apellido_paterno, $apellido_materno, 
        $matricula, $vehiculo, $placa, $marca, 
        $modelo, $color, $anio
    );
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registro guardado correctamente']);
    } else {
        throw new Exception("Error al guardar el registro: " . $stmt->error);
    }
    
    // Cerrar conexión
    $stmt->close();
    $conexion->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>