<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$filtros = [
    'dia' => $_POST['dia'] ?? '',
    'mes' => $_POST['mes'] ?? '',
    'anio' => $_POST['anio'] ?? ''
];

try {
    $queryUsuarios = "SELECT * FROM ingreso_usuario WHERE 1=1";
    $queryVisitantes = "SELECT * FROM ingreso_visitante WHERE 1=1";
    $params = [];
    
    if (!empty($filtros['dia'])) {
        $queryUsuarios .= " AND DAY(hora_entrada) = ?";
        $queryVisitantes .= " AND DAY(hora_entrada) = ?";
        $params[] = $filtros['dia'];
    }
    
    if (!empty($filtros['mes'])) {
        $queryUsuarios .= " AND MONTH(hora_entrada) = ?";
        $queryVisitantes .= " AND MONTH(hora_entrada) = ?";
        $params[] = $filtros['mes'];
    }
    
    if (!empty($filtros['anio'])) {
        $queryUsuarios .= " AND YEAR(hora_entrada) = ?";
        $queryVisitantes .= " AND YEAR(hora_entrada) = ?";
        $params[] = $filtros['anio'];
    }
    
    // Consultar usuarios
    $stmtUsuarios = $pdo->prepare($queryUsuarios);
    $stmtUsuarios->execute($params);
    $usuarios = $stmtUsuarios->fetchAll();
    
    // Consultar visitantes (necesitamos los mismos parámetros)
    $stmtVisitantes = $pdo->prepare($queryVisitantes);
    $stmtVisitantes->execute($params);
    $visitantes = $stmtVisitantes->fetchAll();
    
    echo json_encode([
        'usuarios' => $usuarios,
        'visitantes' => $visitantes
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>