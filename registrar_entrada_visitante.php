<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$datos = [
    'tipo' => $_POST['tipo'] ?? '',
    'nombre' => $_POST['nombre'] ?? '',
    'apellido_p' => $_POST['apellido_p'] ?? '',
    'vehiculo' => $_POST['vehiculo'] ?? '',
    'modelo' => $_POST['modelo'] ?? '',
    'marca' => $_POST['marca'] ?? '',
    'color' => $_POST['color'] ?? '',
    'anio' => $_POST['anio'] ?? '',
    'placa' => $_POST['placa'] ?? ''
];

try {
    // Validar campos obligatorios
    if (empty($datos['tipo']) || empty($datos['nombre']) || empty($datos['apellido_p']) || empty($datos['placa'])) {
        echo json_encode(['success' => false, 'message' => 'Por favor complete todos los campos obligatorios']);
        exit;
    }
    
    // Registrar visitante
    $stmt = $pdo->prepare("
        INSERT INTO ingreso_visitante (
            tipo, nombre, apellido_paterno, vehiculo, modelo, 
            marca, color, anio, placa, hora_entrada
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $datos['tipo'],
        $datos['nombre'],
        $datos['apellido_p'],
        $datos['vehiculo'],
        $datos['modelo'],
        $datos['marca'],
        $datos['color'],
        $datos['anio'],
        $datos['placa']
    ]);
    
    echo json_encode(['success' => true]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al registrar el visitante: ' . $e->getMessage()]);
}
?>