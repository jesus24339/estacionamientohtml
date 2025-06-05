<?php
require_once 'conexion.php';

$id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("SELECT contenido FROM pdf_registros WHERE id = ?");
    $stmt->execute([$id]);
    $pdf = $stmt->fetch();
    
    if ($pdf) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="visualizacion.pdf"');
        echo $pdf['contenido'];
    } else {
        header('Content-Type: text/html');
        echo 'PDF no encontrado';
    }
    
} catch (PDOException $e) {
    header('Content-Type: text/html');
    echo 'Error al visualizar el PDF: ' . htmlspecialchars($e->getMessage());
}
?>