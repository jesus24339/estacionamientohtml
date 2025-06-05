<?php
require_once 'conexion.php';

$id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("SELECT nombre_archivo, contenido FROM pdf_registros WHERE id = ?");
    $stmt->execute([$id]);
    $pdf = $stmt->fetch();
    
    if ($pdf) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $pdf['nombre_archivo'] . '"');
        echo $pdf['contenido'];
    } else {
        header('Content-Type: text/html');
        echo 'PDF no encontrado';
    }
    
} catch (PDOException $e) {
    header('Content-Type: text/html');
    echo 'Error al descargar el PDF: ' . htmlspecialchars($e->getMessage());
}
?>