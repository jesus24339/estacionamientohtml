<?php
require_once 'conexion.php';

// Obtener historial de usuarios y visitantes
$queryUsuarios = "SELECT * FROM ingreso_usuario ORDER BY hora_entrada DESC";
$queryVisitantes = "SELECT * FROM ingreso_visitante ORDER BY hora_entrada DESC";

$stmtUsuarios = $pdo->prepare($queryUsuarios);
$stmtUsuarios->execute();
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

$stmtVisitantes = $pdo->prepare($queryVisitantes);
$stmtVisitantes->execute();
$visitantes = $stmtVisitantes->fetchAll(PDO::FETCH_ASSOC);
?>

<h2 class="titulo text-center mb-4">Historial de Registros</h2>

<div class="seccion">
    <div class="row align-items-center">
        <div class="col-md-2">
            <label class="form-label">Filtrar por fecha:</label>
        </div>
        <div class="col-md-10">
            <div class="row g-2">
                <div class="col-md-1">
                    <label for="filtro-dia" class="form-label">Día:</label>
                </div>
                <div class="col-md-1">
                    <input type="text" class="form-control" id="filtro-dia" placeholder="DD">
                </div>
                <div class="col-md-1">
                    <label for="filtro-mes" class="form-label">Mes:</label>
                </div>
                <div class="col-md-1">
                    <input type="text" class="form-control" id="filtro-mes" placeholder="MM">
                </div>
                <div class="col-md-1">
                    <label for="filtro-anio" class="form-label">Año:</label>
                </div>
                <div class="col-md-1">
                    <input type="text" class="form-control" id="filtro-anio" placeholder="AAAA">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primario w-100" onclick="filtrarHistorial()">Buscar</button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-secundario w-100" onclick="$('#filtro-dia, #filtro-mes, #filtro-anio').val(''); filtrarHistorial();">Limpiar</button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger w-100" onclick="confirmarEliminarHistorial()">Eliminar Historial</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tabla-contenedor mb-4">
    <h3 class="subtitulo mb-3">Historial de Usuarios</h3>
    <table id="tabla-historial-usuarios" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Apellido Materno</th>
                <th>Matrícula</th>
                <th>Vehículo</th>
                <th>Placa</th>
                <th>Hora Entrada</th>
                <th>Hora Salida</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['id']) ?></td>
                <td><?= htmlspecialchars($usuario['tipo']) ?></td>
                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                <td><?= htmlspecialchars($usuario['apellido_paterno']) ?></td>
                <td><?= htmlspecialchars($usuario['apellido_materno']) ?></td>
                <td><?= htmlspecialchars($usuario['matricula']) ?></td>
                <td><?= htmlspecialchars($usuario['vehiculo']) ?></td>
                <td><?= htmlspecialchars($usuario['placa']) ?></td>
                <td><?= htmlspecialchars($usuario['hora_entrada']) ?></td>
                <td><?= htmlspecialchars($usuario['hora_salida'] ?? 'N/A') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="tabla-contenedor">
    <h3 class="subtitulo mb-3">Historial de Visitantes</h3>
    <table id="tabla-historial-visitantes" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Vehículo</th>
                <th>Placa</th>
                <th>Hora Entrada</th>
                <th>Hora Salida</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($visitantes as $visitante): ?>
            <tr>
                <td><?= htmlspecialchars($visitante['id']) ?></td>
                <td><?= htmlspecialchars($visitante['tipo']) ?></td>
                <td><?= htmlspecialchars($visitante['nombre']) ?></td>
                <td><?= htmlspecialchars($visitante['apellido_paterno']) ?></td>
                <td><?= htmlspecialchars($visitante['vehiculo']) ?></td>
                <td><?= htmlspecialchars($visitante['placa']) ?></td>
                <td><?= htmlspecialchars($visitante['hora_entrada']) ?></td>
                <td><?= htmlspecialchars($visitante['hora_salida'] ?? 'N/A') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>