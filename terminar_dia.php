<?php
require_once 'conexion.php';
require_once 'fpdf/fpdf.php';

header('Content-Type: application/json');

try {
    // Obtener registros (código previo se mantiene igual)
    $stmtUsuarios = $pdo->prepare("SELECT * FROM ingreso_usuario WHERE hora_salida IS NOT NULL");
    $stmtUsuarios->execute();
    $usuarios = $stmtUsuarios->fetchAll();
    
    $stmtVisitantes = $pdo->prepare("SELECT * FROM ingreso_visitante WHERE hora_salida IS NOT NULL");
    $stmtVisitantes->execute();
    $visitantes = $stmtVisitantes->fetchAll();
    
    if (empty($usuarios) && empty($visitantes)) {
        echo json_encode(['success' => false, 'message' => 'No hay registros para generar PDF']);
        exit;
    }
    
    // Crear PDF con diseño mejorado
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(true, 25);

    // --- PALETA DE COLORES MEJORADA ---
    $verdeOscuro = [0, 82, 43];    // Verde institucional más oscuro
    $verdeClaro = [198, 228, 214];  // Verde claro para fondos
    $dorado = [212, 175, 55];       // Acento dorado
    $grisOscuro = [64, 64, 64];     // Texto principal
    $grisClaro = [245, 245, 245];   // Fondos alternos

    // --- ENCABEZADO CON LOGO Y DEGRADADO ---
    $pdf->SetFillColor($verdeOscuro[0], $verdeOscuro[1], $verdeOscuro[2]);
    $pdf->Rect(0, 0, 210, 30, 'F');
    
    // Logo (asegúrate de tener el archivo logo_tecnologico.png en tu servidor)
    if(file_exists('logo_tecnologico.png')) {
        $pdf->Image('logo_tecnologico.png', 10, 8, 20);
    }
    
    // Título principal con sombra
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetY(12);
    $pdf->Cell(0, 10, 'TECNOLOGICO DE ESTUDIOS SUPERIORES DE IXTAPALUCA', 0, 1, 'C');
    
    // Subtítulo con acento dorado
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor($dorado[0], $dorado[1], $dorado[2]);
    $pdf->Cell(0, 8, 'REPORTE DE ACCESOS - ' . strtoupper(utf8_decode(date('d F Y'))), 0, 1, 'C');
    
    // Línea decorativa
    $pdf->SetDrawColor($dorado[0], $dorado[1], $dorado[2]);
    $pdf->SetLineWidth(0.8);
    $pdf->Line(15, 32, 195, 32);
    $pdf->SetLineWidth(0.2);
    
    // --- SECCIÓN DE USUARIOS ---
    if (!empty($usuarios)) {
        $pdf->Ln(12);
        
        // Título de sección con fondo
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetFillColor($verdeClaro[0], $verdeClaro[1], $verdeClaro[2]);
        $pdf->SetTextColor($verdeOscuro[0], $verdeOscuro[1], $verdeOscuro[2]);
        $pdf->Cell(0, 10, utf8_decode('  PERSONAL REGISTRADO  '), 0, 1, 'L', true);
        $pdf->Ln(3);
        
        // Encabezado de tabla con efecto 3D
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetFillColor($verdeOscuro[0], $verdeOscuro[1], $verdeOscuro[2]);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(30, 8, 'MATRICULA', 1, 0, 'C', true);
        $pdf->Cell(65, 8, 'NOMBRE COMPLETO', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'ENTRADA', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'SALIDA', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'DURACION', 1, 1, 'C', true);
        
        // Filas con efecto zebra
        $pdf->SetFont('Helvetica', '', 9);
        $fill = false;
        foreach ($usuarios as $usuario) {
            $pdf->SetFillColor($fill ? $grisClaro[0] : 255, $fill ? $grisClaro[1] : 255, $fill ? $grisClaro[2] : 255);
            $pdf->SetTextColor($grisOscuro[0], $grisOscuro[1], $grisOscuro[2]);
            
            // Calcular duración
            $entrada = new DateTime($usuario['hora_entrada']);
            $salida = new DateTime($usuario['hora_salida']);
            $duracion = $entrada->diff($salida);
            $duracionStr = $duracion->format('%H:%I');
            
            $pdf->Cell(30, 8, $usuario['matricula'], 'LR', 0, 'C', $fill);
            $nombre = utf8_decode($usuario['nombre'] . ' ' . $usuario['apellido_paterno'] . ' ' . $usuario['apellido_materno']);
            $pdf->Cell(65, 8, $nombre, 'LR', 0, 'L', $fill);
            $pdf->Cell(30, 8, date('H:i', strtotime($usuario['hora_entrada'])), 'LR', 0, 'C', $fill);
            $pdf->Cell(30, 8, date('H:i', strtotime($usuario['hora_salida'])), 'LR', 0, 'C', $fill);
            $pdf->Cell(25, 8, $duracionStr, 'LR', 1, 'C', $fill);
            $fill = !$fill;
        }
        
        // Cierre de tabla
        $pdf->SetFillColor($verdeOscuro[0], $verdeOscuro[1], $verdeOscuro[2]);
        $pdf->Cell(180, 0, '', 'T', 1);
        $pdf->Ln(8);
    }
    
    // --- SECCIÓN DE VISITANTES ---
    if (!empty($visitantes)) {
        // Título de sección con fondo
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetFillColor($verdeClaro[0], $verdeClaro[1], $verdeClaro[2]);
        $pdf->SetTextColor($verdeOscuro[0], $verdeOscuro[1], $verdeOscuro[2]);
        $pdf->Cell(0, 10, utf8_decode('  VISITANTES REGISTRADOS  '), 0, 1, 'L', true);
        $pdf->Ln(3);
        
        // Encabezado de tabla
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetFillColor($verdeOscuro[0], $verdeOscuro[1], $verdeOscuro[2]);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(30, 8, 'PLACA', 1, 0, 'C', true);
        $pdf->Cell(65, 8, 'NOMBRE', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'ENTRADA', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'SALIDA', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'DURACION', 1, 1, 'C', true);
        
        // Filas
        $pdf->SetFont('Helvetica', '', 9);
        $fill = false;
        foreach ($visitantes as $visitante) {
            $pdf->SetFillColor($fill ? $grisClaro[0] : 255, $fill ? $grisClaro[1] : 255, $fill ? $grisClaro[2] : 255);
            $pdf->SetTextColor($grisOscuro[0], $grisOscuro[1], $grisOscuro[2]);
            
            // Calcular duración
            $entrada = new DateTime($visitante['hora_entrada']);
            $salida = new DateTime($visitante['hora_salida']);
            $duracion = $entrada->diff($salida);
            $duracionStr = $duracion->format('%H:%I');
            
            $pdf->Cell(30, 8, $visitante['placa'], 'LR', 0, 'C', $fill);
            $nombre = utf8_decode($visitante['nombre'] . ' ' . $visitante['apellido_paterno']);
            $pdf->Cell(65, 8, $nombre, 'LR', 0, 'L', $fill);
            $pdf->Cell(30, 8, date('H:i', strtotime($visitante['hora_entrada'])), 'LR', 0, 'C', $fill);
            $pdf->Cell(30, 8, date('H:i', strtotime($visitante['hora_salida'])), 'LR', 0, 'C', $fill);
            $pdf->Cell(25, 8, $duracionStr, 'LR', 1, 'C', $fill);
            $fill = !$fill;
        }
        
        // Cierre de tabla
        $pdf->SetFillColor($verdeOscuro[0], $verdeOscuro[1], $verdeOscuro[2]);
        $pdf->Cell(180, 0, '', 'T', 1);
    }
    
    // --- PIE DE PÁGINA MEJORADO ---
    $pdf->SetY(-20);
    $pdf->SetFont('Helvetica', 'I', 8);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 6, utf8_decode('Reporte generado el ') . date('d/m/Y H:i:s'), 0, 1, 'C');
    
    // Línea decorativa
    $pdf->SetDrawColor($dorado[0], $dorado[1], $dorado[2]);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
    
    // Imagen de Rocko con efecto de sombra (opcional)
    if(file_exists('rocko.png')) {
        $pdf->Image('rocko.png', 150, $pdf->GetY() + 2, 40);
    }
    
    // --- GUARDAR PDF (código previo se mantiene igual) ---
    $nombreArchivo = 'registros_' . date('Ymd_His') . '.pdf';
    $rutaTemp = sys_get_temp_dir() . '/' . $nombreArchivo;
    $pdf->Output('F', $rutaTemp);
    
    $contenidoPDF = file_get_contents($rutaTemp);
    
    $stmt = $pdo->prepare("
        INSERT INTO pdf_registros (nombre_archivo, fecha, hora, contenido)
        VALUES (?, CURDATE(), CURTIME(), ?)
    ");
    
    $stmt->execute([$nombreArchivo, $contenidoPDF]);
    unlink($rutaTemp);
    
    $urlDescarga = 'descargar_pdf.php?id=' . $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true,
        'nombre_archivo' => $nombreArchivo,
        'pdf_url' => $urlDescarga
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error al terminar el dia: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al generar PDF: ' . $e->getMessage()]);
}
?>