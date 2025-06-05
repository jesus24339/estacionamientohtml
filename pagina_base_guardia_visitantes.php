<?php
require_once 'conexion.php';

// Obtener visitantes registrados sin salida
$query = "SELECT * FROM ingreso_visitante WHERE hora_salida IS NULL";
$stmt = $pdo->prepare($query);
$stmt->execute();
$visitantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="seccion">
    <div class="row align-items-center">
        <div class="col-md-2">
            <h5 class="mb-0">Registrar Visitante:</h5>
        </div>
        <div class="col-md-10">
            <div class="row g-2">
                <div class="col-md-1">
                    <label for="tipo-visitante" class="form-label">Tipo:</label>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="tipo-visitante">
                        <option value="">Seleccionar</option>
                        <option value="Visitante">Visitante</option>
                        <option value="Proveedor">Proveedor</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label for="nombre-visitante" class="form-label">Nombre:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="nombre-visitante">
                </div>
                <div class="col-md-2">
                    <label for="apellido-p-visitante" class="form-label">Apellido Paterno:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="apellido-p-visitante">
                </div>
            </div>
            <div class="row g-2 mt-2">
                <div class="col-md-1">
                    <label for="vehiculo-visitante" class="form-label">Vehículo:</label>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="vehiculo-visitante">
                        <option value="">Seleccionar</option>
                        <option value="Automóvil">Automóvil</option>
                        <option value="Motocicleta">Motocicleta</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label for="modelo-visitante" class="form-label">Modelo:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="modelo-visitante">
                </div>
                <div class="col-md-1">
                    <label for="marca-visitante" class="form-label">Marca:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="marca-visitante">
                </div>
            </div>
            <div class="row g-2 mt-2">
                <div class="col-md-1">
                    <label for="color-visitante" class="form-label">Color:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="color-visitante">
                </div>
                <div class="col-md-1">
                    <label for="anio-visitante" class="form-label">Año:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="anio-visitante">
                </div>
                <div class="col-md-1">
                    <label for="placa-visitante" class="form-label">Placa:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="placa-visitante">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primario w-100" onclick="registrarEntradaVisitante()">Registrar Entrada</button>
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
                <div class="col-md-1">
                    <label for="filtro-placa" class="form-label">Placa:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="filtro-placa">
                </div>
                <div class="col-md-2">
                    <label for="filtro-apellido-p" class="form-label">Apellido Paterno:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="filtro-apellido-p">
                </div>
                <div class="col-md-1">
                    <label for="filtro-nombre" class="form-label">Nombre:</label>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" id="filtro-nombre">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-primario w-100" onclick="filtrarVisitantes()">Filtrar</button>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-secundario w-100" onclick="$('#filtro-placa, #filtro-apellido-p, #filtro-nombre').val(''); filtrarVisitantes();">Limpiar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tabla-contenedor">
    <table id="tabla-visitantes" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Apellido Paterno</th>
                <th>Vehículo</th>
                <th>Modelo</th>
                <th>Marca</th>
                <th>Color</th>
                <th>Año</th>
                <th>Placa</th>
                <th>Acciones</th>
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
                <td><?= htmlspecialchars($visitante['modelo']) ?></td>
                <td><?= htmlspecialchars($visitante['marca']) ?></td>
                <td><?= htmlspecialchars($visitante['color']) ?></td>
                <td><?= htmlspecialchars($visitante['anio']) ?></td>
                <td><?= htmlspecialchars($visitante['placa']) ?></td>
                <td>
                    <button type="button" class="btn btn-sm btn-primario" onclick="mostrarDetalles('visitante', <?= $visitante['id'] ?>)">
                        <i class="bi bi-eye"></i> Ver
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>