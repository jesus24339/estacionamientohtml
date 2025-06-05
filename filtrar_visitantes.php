<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$filtros = [
    'placa' => $_POST['placa'] ?? '',
    'apellido_p' => $_POST['apellido_p'] ?? '',
    'nombre' => $_POST['nombre'] ?? ''
];

try {
    $query = "SELECT * FROM ingreso_visitante WHERE hora_salida IS NULL";
    $params = [];
    
    if (!empty($filtros['placa'])) {
        $query .= " AND placa LIKE ?";
        $params[] = "%{$filtros['placa']}%";
    }
    
    if (!empty($filtros['apellido_p'])) {
        $query .= " AND apellido_paterno LIKE ?";
        $params[] = "%{$filtros['apellido_p']}%";
    }
    
    if (!empty($filtros['nombre'])) {
        $query .= " AND nombre LIKE ?";
        $params[] = "%{$filtros['nombre']}%";
    }
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    
    $visitantes = $stmt->fetchAll();
    
    echo json_encode($visitantes);
    
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>