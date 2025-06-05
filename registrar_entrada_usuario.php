<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$matricula = $_POST['matricula'] ?? '';

try {
    // Verificar si la matrícula existe
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE matricula = ?");
    $stmt->execute([$matricula]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        echo json_encode(['success' => false, 'message' => 'La matrícula no se encuentra registrada']);
        exit;
    }
    
    // Verificar si ya tiene un registro de entrada sin salida
    $stmt = $pdo->prepare("SELECT * FROM ingreso_usuario WHERE matricula = ? AND hora_salida IS NULL");
    $stmt->execute([$matricula]);
    
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Este usuario ya tiene un registro de entrada sin salida']);
        exit;
    }
    
    // Registrar entrada
    $stmt = $pdo->prepare("
        INSERT INTO ingreso_usuario (
            tipo, nombre, apellido_paterno, apellido_materno, 
            matricula, vehiculo, modelo, marca, color, anio, placa, hora_entrada
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    
    $stmt->execute([
        $usuario['tipo'],
        $usuario['nombre'],
        $usuario['apellido_paterno'],
        $usuario['apellido_materno'],
        $usuario['matricula'],
        $usuario['vehiculo'],
        $usuario['modelo'],
        $usuario['marca'],
        $usuario['color'],
        $usuario['anio'],
        $usuario['placa']
    ]);
    
    echo json_encode(['success' => true]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al registrar la entrada: ' . $e->getMessage()]);
}
?>