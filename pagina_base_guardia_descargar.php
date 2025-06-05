<?php
require_once 'conexion.php';

// Obtener registros PDF
$query = "SELECT * FROM pdf_registros ORDER BY fecha DESC, hora DESC";
$stmt = $pdo->prepare($query);
$stmt->execute();
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="titulo text-center mb-4">Registros PDF Guardados</h2>

<?php if (empty($registros)): ?>
    <div class="alert alert-info text-center">
        No hay registros PDF guardados
    </div>
<?php else: ?>
    <div class="tabla-contenedor">
        <table id="tabla-pdf" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Archivo</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $registro): ?>
                <tr>
                    <td><?= htmlspecialchars($registro['id']) ?></td>
                    <td><?= htmlspecialchars($registro['nombre_archivo']) ?></td>
                    <td><?= htmlspecialchars($registro['fecha']) ?></td>
                    <td><?= htmlspecialchars($registro['hora']) ?></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primario me-2" onclick="visualizarPDF(<?= $registro['id'] ?>)">
                            <i class="bi bi-eye"></i> Visualizar
                        </button>
                        <button type="button" class="btn btn-sm btn-secundario" onclick="descargarPDF(<?= $registro['id'] ?>, '<?= htmlspecialchars($registro['nombre_archivo']) ?>')">
                            <i class="bi bi-download"></i> Descargar
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>