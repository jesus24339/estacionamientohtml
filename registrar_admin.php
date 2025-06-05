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
$contrasena = $_POST['contrasena'] ?? '';

// Validar datos recibidos
if (empty($nombre) || empty($apellido_paterno) || empty($apellido_materno) || empty($matricula) || empty($contrasena)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, complete todos los campos.']);
    exit;
}

// Validar formato de matrícula (9 dígitos)
if (!preg_match('/^\d{9}$/', $matricula)) {
    echo json_encode(['success' => false, 'message' => 'La matrícula debe tener exactamente 9 dígitos.']);
    exit;
}

// Validar formato de contraseña (4 dígitos)
if (!preg_match('/^\d{4}$/', $contrasena)) {
    echo json_encode(['success' => false, 'message' => 'La contraseña debe tener exactamente 4 dígitos.']);
    exit;
}

try {
    // Conexión a la base de datos
    $conexion = new mysqli($host, $user, $password, $database);
    
    // Verificar conexión
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }
    
    // Verificar si la matrícula ya existe
    $queryVerificar = "SELECT matricula FROM admin_personal WHERE matricula = ?";
    $stmtVerificar = $conexion->prepare($queryVerificar);
    $stmtVerificar->bind_param("s", $matricula);
    $stmtVerificar->execute();
    $resultado = $stmtVerificar->get_result();
    
    if ($resultado->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'La matrícula ya está registrada.']);
        $stmtVerificar->close();
        $conexion->close();
        exit;
    }
    $stmtVerificar->close();
    
    // Insertar los datos en la tabla admin_personal
    $queryInsertar = "INSERT INTO admin_personal (nombre, apellido_paterno, apellido_materno, matricula, contrasena) 
                     VALUES (?, ?, ?, ?, ?)";
    $stmtInsertar = $conexion->prepare($queryInsertar);
    $stmtInsertar->bind_param("sssss", $nombre, $apellido_paterno, $apellido_materno, $matricula, $contrasena);
    
    if ($stmtInsertar->execute()) {
        echo json_encode(['success' => true, 'message' => 'Administrador registrado con éxito.']);
    } else {
        throw new Exception("Error al registrar el administrador: " . $stmtInsertar->error);
    }
    
    // Cerrar conexiones
    $stmtInsertar->close();
    $conexion->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Hubo un error al intentar registrar el administrador: ' . $e->getMessage()]);
}
?>