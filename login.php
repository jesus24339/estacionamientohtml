<?php
header('Content-Type: application/json');

// Configuración de la base de datos
$host = "localhost";
$user = "root";
$password = "";
$database = "elsa";

// Obtener datos del POST
$matricula = $_POST['matricula'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';
$tipo_usuario = $_POST['tipo_usuario'] ?? '';

// Validar datos recibidos
if (empty($matricula) || empty($contrasena) || empty($tipo_usuario)) {
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
    
    // Consulta según el tipo de usuario
    if ($tipo_usuario == "Administrador") {
        $query = "SELECT nombre FROM admin_personal WHERE matricula = ? AND contrasena = ?";
    } elseif ($tipo_usuario == "Guardia") {
        $query = "SELECT nombre FROM guardias WHERE matricula = ? AND contrasena = ?";
    } else {
        echo json_encode(['success' => false, 'message' => 'Tipo de usuario no válido']);
        exit;
    }
    
    // Preparar y ejecutar la consulta
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ss", $matricula, $contrasena);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        echo json_encode([
            'success' => true,
            'nombre_usuario' => $fila['nombre']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Usuario o contraseña incorrectos']);
    }
    
    // Cerrar conexiones
    $stmt->close();
    $conexion->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
}
?>