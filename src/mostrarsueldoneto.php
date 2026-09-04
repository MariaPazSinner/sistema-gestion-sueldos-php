<?php
include 'conexion.php';
$dni = $_POST['DNI'] ?? 'todos';
$periodo_filtro = $_POST['periodo_filtro'] ?? '';
$condiciones = [];
if ($dni !== '' && $dni !== 'todos') { $dni_sql = mysqli_real_escape_string($mysqli, $dni); $condiciones[] = "r.DNI = '$dni_sql'"; }
if ($periodo_filtro !== '') { $periodo_sql = mysqli_real_escape_string($mysqli, $periodo_filtro); $condiciones[] = "r.periodo = '$periodo_sql'"; }
$consultaTabla = "SELECT r.id_registro, r.DNI, r.periodo, r.salario, e.nombre, e.apellido FROM registro r JOIN empleados e ON r.DNI = e.DNI";
if ($condiciones) $consultaTabla .= ' WHERE ' . implode(' AND ', $condiciones);
$consultaTabla .= ' ORDER BY r.periodo DESC, e.apellido, e.nombre';
$resultado = mysqli_query($mysqli, $consultaTabla);
$empleados = mysqli_query($mysqli, "SELECT DNI, nombre, apellido FROM empleados ORDER BY apellido, nombre");
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Liquidaciones</title></head><body>
<main class="screen-card wide-card"><header class="screen-toolbar"><div class="title-block"><span class="eyebrow">Liquidaciones</span><h1>Historial de liquidaciones</h1><p>Revisá los importes liquidados por persona y período.</p></div><a class="primary-action" href="altasueldoneto.php">+ Nueva liquidación</a></header>
<form method="POST" class="filter-panel"><div class="filter-grid"><div><label for="DNI">Empleado</label><select id="DNI" name="DNI"><option value="todos">Todos los empleados</option><?php while ($empleado = mysqli_fetch_assoc($empleados)): ?><option value="<?php echo htmlspecialchars($empleado['DNI']); ?>" <?php echo $dni == $empleado['DNI'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($empleado['apellido'] . ', ' . $empleado['nombre'] . ' · ' . $empleado['DNI']); ?></option><?php endwhile; ?></select></div><div><label for="periodo_filtro">Mes de liquidación</label><input type="month" id="periodo_filtro" name="periodo_filtro" value="<?php echo htmlspecialchars($periodo_filtro); ?>"></div></div><div class="filter-actions"><button type="submit">Aplicar filtros</button><a class="text-action" href="mostrarsueldoneto.php">Limpiar</a></div></form>
<div class="table-shell"><table><thead><tr><th>Período</th><th>Empleado</th><th>DNI</th><th>Importe neto</th><th>Acciones</th></tr></thead><tbody>
<?php if ($resultado && mysqli_num_rows($resultado) > 0): while ($fila = mysqli_fetch_assoc($resultado)): ?><tr><td><strong><?php echo htmlspecialchars($fila['periodo']); ?></strong></td><td><?php echo htmlspecialchars($fila['apellido'] . ', ' . $fila['nombre']); ?></td><td><?php echo htmlspecialchars($fila['DNI']); ?></td><td>$<?php echo number_format((float)$fila['salario'], 2, ',', '.'); ?></td><td class="row-actions"><a class="table-action" href="detalleliquidacion.php?id=<?php echo urlencode($fila['id_registro']); ?>">Descargar detalle</a><a class="danger-action" data-confirm-delete="liquidation" href="bajadesdemostrar.php?id_registro=<?php echo urlencode($fila['id_registro']); ?>&amp;DNI=<?php echo urlencode($fila['DNI']); ?>">Eliminar</a></td></tr><?php endwhile; else: ?><tr><td colspan="5" class="empty-state">No encontramos liquidaciones con esos filtros.</td></tr><?php endif; ?>
</tbody></table></div></main></body></html>
