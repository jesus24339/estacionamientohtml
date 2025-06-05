<?php
require_once 'conexion.php';

// Obtener usuarios registrados sin salida
$query = "SELECT * FROM ingreso_usuario WHERE hora_salida IS NULL";
$stmt = $pdo->prepare($query);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="seccion">
    <div class="row align-items-center">
        <div class="col-md-3">
            <h5 class="mb-0">Registrar Entrada de Usuario:</h5>
        </div>
        <div class="col-md-9">
            <div class="row g-2">
                <div class="col-md-3">
                    <label for="matricula" class="form-label">Matrícula (9 dígitos):</label>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" id="matricula" maxlength="9">
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-primario w-100" onclick="registrarEntradaUsuario()">Registrar Entrada</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="seccion">
    <div class="row align-items-center">
        <div class="col-md-1">
            <label class="form-label">Filtrar por:</label>
        </div>
        <div class="col-md-11">
            <div class="row g-2">
                <div class="col-md-2">
                    <label for="filtro-matricula" class="form-label">Matrícula:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="filtro-matricula">
                </div>
                <div class="col-md-2">
                    <label for="filtro-apellido-p" class="form-label">Apellido Paterno:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="filtro-apellido-p">
                </div>
                <div class="col-md-2">
                    <label for="filtro-apellido-m" class="form-label">Apellido Materno:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="filtro-apellido-m">
                </div>
                <div class="col-md-2">
                    <label for="filtro-nombre" class="form-label">Nombre:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="filtro-nombre">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primario w-100" onclick="filtrarUsuarios()">Filtrar</button>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-secundario w-100" onclick="$('#filtro-matricula, #filtro-apellido-p, #filtro-apellido-m, #filtro-nombre').val(''); filtrarUsuarios();">Limpiar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tabla-contenedor">
    <table id="tabla-usuarios" class="table table-striped table-bordered" style="width:100%">
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
                <th>Marca</th>
                <th>Modelo</th>
                <th>Color</th>
                <th>Año</th>
                <th>Acciones</th>
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
                <td><?= htmlspecialchars($usuario['marca']) ?></td>
                <td><?= htmlspecialchars($usuario['modelo']) ?></td>
                <td><?= htmlspecialchars($usuario['color']) ?></td>
                <td><?= htmlspecialchars($usuario['anio']) ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-primario" onclick="mostrarDetalles('usuario', <?= $usuario['id'] ?>)">
                        <i class="bi bi-eye"></i> Ver
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>