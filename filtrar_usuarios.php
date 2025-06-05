<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$filtros = [
    'matricula' => $_POST['matricula'] ?? '',
    'apellido_p' => $_POST['apellido_p'] ?? '',
    'apellido_m' => $_POST['apellido_m'] ?? '',
    'nombre' => $_POST['nombre'] ?? ''
];

try {
    $query = "SELECT * FROM ingreso_usuario WHERE hora_salida IS NULL";
    $params = [];
    
    if (!empty($filtros['matricula'])) {
        $query .= " AND matricula LIKE ?";
        $params[] = "%{$filtros['matricula']}%";
    }
    
    if (!empty($filtros['apellido_p'])) {
        $query .= " AND apellido_paterno LIKE ?";
        $params[] = "%{$filtros['apellido_p']}%";
    }
    
    if (!empty($filtros['apellido_m'])) {
        $query .= " AND apellido_materno LIKE ?";
        $params[] = "%{$filtros['apellido_m']}%";
    }
    
    if (!empty($filtros['nombre'])) {
        $query .= " AND nombre LIKE ?";
        $params[] = "%{$filtros['nombre']}%";
    }
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    
    $usuarios = $stmt->fetchAll();
    
    echo json_encode($usuarios);
    
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>