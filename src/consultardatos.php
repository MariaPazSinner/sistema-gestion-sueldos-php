<?php
include 'conexion.php';
$dni = $_POST['DNI'] ?? 'todos';
$departamento = $_POST['departamento'] ?? 'todos';
$condiciones = [];
if ($dni !== '' && $dni !== 'todos') {
    $dni_escapado = mysqli_real_escape_string($mysqli, $dni);
    $condiciones[] = "e.DNI = '$dni_escapado'";
}
if ($departamento !== '' && $departamento !== 'todos') {
    $departamento_escapado = mysqli_real_escape_string($mysqli, $departamento);
    $condiciones[] = "d.id_departamento = '$departamento_escapado'";
}
$consultaTabla = "SELECT e.DNI, e.nombre, e.apellido, e.celular, e.mail, p.nombre AS nombre_puesto, d.nombre AS nombre_departamento, e.salarioBruto FROM empleados e LEFT JOIN puestos p ON e.id_puestos = p.id_puestos LEFT JOIN departamento d ON p.id_departamento = d.id_departamento";
if ($condiciones) $consultaTabla .= ' WHERE ' . implode(' AND ', $condiciones);
$consultaTabla .= ' ORDER BY e.apellido, e.nombre';
$resultado = mysqli_query($mysqli, $consultaTabla);
$empleados = mysqli_query($mysqli, "SELECT DNI, nombre, apellido FROM empleados ORDER BY apellido, nombre");
$departamentos = mysqli_query($mysqli, "SELECT id_departamento, nombre FROM departamento ORDER BY nombre");
?>
<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Gestión de empleados</title></head>
<body><main class="screen-card wide-card">
<header class="screen-toolbar"><div class="title-block"><span class="eyebrow">Personas</span><h1>Empleados</h1><p>Consultá la información del equipo, filtrá por área y accedé a las acciones habituales.</p></div><a class="primary-action" href="altaempleados.php">+ Agregar empleado</a></header>
<?php if (!empty($_GET['mensaje'])): ?><p class="status-message"><?php echo htmlspecialchars($_GET['mensaje'], ENT_QUOTES, 'UTF-8'); ?></p><?php endif; ?>
<form method="POST" class="filter-panel"><div class="filter-grid">
<div><label for="DNI">Empleado</label><select id="DNI" name="DNI"><option value="todos">Todos los empleados</option><?php while ($empleado = mysqli_fetch_assoc($empleados)): ?><option value="<?php echo htmlspecialchars($empleado['DNI']); ?>" <?php echo $dni == $empleado['DNI'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($empleado['apellido'] . ', ' . $empleado['nombre'] . ' · ' . $empleado['DNI']); ?></option><?php endwhile; ?></select></div>
<div><label for="departamento">Área de trabajo</label><select id="departamento" name="departamento"><option value="todos">Todas las áreas</option><?php while ($area = mysqli_fetch_assoc($departamentos)): ?><option value="<?php echo htmlspecialchars($area['id_departamento']); ?>" <?php echo $departamento == $area['id_departamento'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($area['nombre']); ?></option><?php endwhile; ?></select></div>
</div><div class="filter-actions"><button type="submit">Aplicar filtros</button><a class="text-action" href="consultardatos.php">Limpiar</a></div></form>
<div class="table-shell"><table><thead><tr><th>DNI</th><th>Empleado</th><th>Contacto</th><th>Puesto</th><th>Área</th><th>Salario bruto</th><th>Acciones</th></tr></thead><tbody>
<?php if ($resultado && mysqli_num_rows($resultado) > 0): while ($fila = mysqli_fetch_assoc($resultado)): ?>
<tr><td><?php echo htmlspecialchars($fila['DNI']); ?></td><td><strong><?php echo htmlspecialchars($fila['apellido'] . ', ' . $fila['nombre']); ?></strong></td><td><?php echo htmlspecialchars($fila['mail']); ?><small><?php echo htmlspecialchars($fila['celular']); ?></small></td><td><?php echo htmlspecialchars($fila['nombre_puesto'] ?? 'Sin asignar'); ?></td><td><span class="area-pill"><?php echo htmlspecialchars($fila['nombre_departamento'] ?? 'Sin asignar'); ?></span></td><td>$<?php echo number_format((float)$fila['salarioBruto'], 2, ',', '.'); ?></td><td class="row-actions"><a class="table-action" href="modificarempleado.php?DNI=<?php echo urlencode($fila['DNI']); ?>">Editar</a><form action="borrarempleado.php" method="POST" data-confirm-delete="employee"><input type="hidden" name="dniEliminar" value="<?php echo htmlspecialchars($fila['DNI']); ?>"><input type="hidden" name="accion" value="eliminar"><button type="submit" class="danger-action">Eliminar</button></form></td></tr>
<?php endwhile; else: ?><tr><td colspan="7" class="empty-state">No encontramos empleados con esos filtros.</td></tr><?php endif; ?>
</tbody></table></div></main></body></html>
