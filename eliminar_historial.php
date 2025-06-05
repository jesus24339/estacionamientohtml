<?php
require_once 'conexion.php';

header('Content-Type: application/json');

$dia = $_POST['dia'] ?? '';
$mes = $_POST['mes'] ?? '';
$anio = $_POST['anio'] ?? '';

try {
    // Validar que todos los campos estén presentes
    if (empty($dia) || empty($mes) || empty($anio)) {
        echo json_encode(['success' => false, 'message' => 'Día, mes y año son requeridos']);
        exit;
    }

    // Validar que la fecha sea válida
    if (!checkdate($mes, $dia, $anio)) {
        echo json_encode(['success' => false, 'message' => 'La fecha proporcionada no es válida']);
        exit;
    }

    // Formatear la fecha para las consultas
    $fecha = sprintf('%04d-%02d-%02d', $anio, $mes, $dia);

    // Iniciar transacción
    $pdo->beginTransaction();

    // Eliminar registros de usuarios
    $stmtUsuarios = $pdo->prepare("
        DELETE FROM ingreso_usuario 
        WHERE DATE(hora_entrada) = :fecha
    ");
    $stmtUsuarios->execute([':fecha' => $fecha]);

    // Eliminar registros de visitantes
    $stmtVisitantes = $pdo->prepare("
        DELETE FROM ingreso_visitante 
        WHERE DATE(hora_entrada) = :fecha
    ");
    $stmtVisitantes->execute([':fecha' => $fecha]);

    // Confirmar transacción
    $pdo->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Historial eliminado correctamente',
        'fecha' => $fecha
    ]);

} catch (PDOException $e) {
    // Revertir transacción en caso de error
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Error al eliminar el historial: ' . $e->getMessage()]);
}
?>