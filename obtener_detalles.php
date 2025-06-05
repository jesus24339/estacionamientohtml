<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$tipo = $_POST['tipo'] ?? '';
$id = $_POST['id'] ?? 0;

try {
    if ($tipo === 'usuario') {
        $stmt = $pdo->prepare("SELECT * FROM ingreso_usuario WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM ingreso_visitante WHERE id = ?");
    }
    
    $stmt->execute([$id]);
    $registro = $stmt->fetch();
    
    if ($registro) {
        // Formatear fechas
        if ($registro['hora_entrada']) {
            $registro['hora_entrada'] = (new DateTime($registro['hora_entrada']))->format('Y-m-d H:i:s');
        }
        
        if (isset($registro['hora_salida']) && $registro['hora_salida']) {
            $registro['hora_salida'] = (new DateTime($registro['hora_salida']))->format('Y-m-d H:i:s');
        }
        
        echo json_encode(['success' => true, 'datos' => $registro]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registro no encontrado']);
    }
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener detalles: ' . $e->getMessage()]);
}
?>