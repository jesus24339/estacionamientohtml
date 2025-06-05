<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$tipo = $_POST['tipo'] ?? '';
$id = $_POST['id'] ?? 0;

try {
    if ($tipo === 'usuario') {
        $stmt = $pdo->prepare("UPDATE ingreso_usuario SET hora_salida = NOW() WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("UPDATE ingreso_visitante SET hora_salida = NOW() WHERE id = ?");
    }
    
    $stmt->execute([$id]);
    
    echo json_encode(['success' => true]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al registrar la salida: ' . $e->getMessage()]);
}
?>