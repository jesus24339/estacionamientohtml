<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "elsa";

// Obtener datos del POST
$nombre = $_POST['nombre'] ?? '';
$apellido_paterno = $_POST['apellido_paterno'] ?? '';
$apellido_materno = $_POST['apellido_materno'] ?? '';
$matricula = $_POST['matricula'] ?? '';
$turno = $_POST['turno'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

// Validar que todos los campos estén presentes
if (empty($nombre) || empty($apellido_paterno) || empty($apellido_materno) || 
    empty($matricula) || empty($turno) || empty($contrasena)) {
    echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
    exit;
}

// Validar contraseña (4 dígitos)
if (strlen($contrasena) !== 4 || !ctype_digit($contrasena)) {
    echo json_encode(['success' => false, 'message' => 'La contraseña debe ser de exactamente 4 dígitos numéricos']);
    exit;
}

try {
    // Conexión a la base de datos
    $conexion = new mysqli($host, $user, $password, $database);
    
    // Verificar conexión
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }
    
    // Insertar datos en la tabla guardias
    $query = "INSERT INTO guardias 
              (nombre, apellido_paterno, apellido_materno, matricula, turno, contrasena) 
              VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssssss", 
        $nombre, $apellido_paterno, $apellido_materno, 
        $matricula, $turno, $contrasena
    );
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Guardia registrado correctamente']);
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