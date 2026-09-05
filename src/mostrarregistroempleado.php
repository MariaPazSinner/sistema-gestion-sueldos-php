<?php
session_start();
include 'conexion.php';
if (!isset($_SESSION['usuario'])) { header('Location: logininiciosesiones.php'); exit(); }
$dni = $_SESSION['dni'] ?? ($_POST['DNI'] ?? '');
$periodo = $_POST['periodo_filtro'] ?? '';
$dniSql = mysqli_real_escape_string($mysqli, $dni);
$condicionPeriodo = '';
if ($periodo !== '') { $periodoSql = mysqli_real_escape_string($mysqli, $periodo); $condicionPeriodo = " AND r.periodo = '$periodoSql'"; }
$empleadoResult = mysqli_query($mysqli, "SELECT nombre, apellido FROM empleados WHERE DNI='$dniSql'");
$empleado = $empleadoResult ? mysqli_fetch_assoc($empleadoResult) : null;
$resultado = mysqli_query($mysqli, "SELECT r.id_registro, r.periodo, r.salario FROM registro r WHERE r.DNI='$dniSql'$condicionPeriodo ORDER BY r.periodo DESC");
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Mis liquidaciones</title></head><body>
<main class="screen-card wide-card"><header class="screen-toolbar"><div class="title-block"><span class="eyebrow">Mi perfil</span><h1>Mis liquidaciones</h1><p>Consultá tus importes por período y descargá cada comprobante.</p></div><a class="secondary-action" href="misdatos.php">Mis datos personales</a></header>
<section class="employee-context"><span>Empleado</span><strong><?php echo htmlspecialchars(($empleado['nombre'] ?? '') . ' ' . ($empleado['apellido'] ?? '')); ?></strong><small>DNI <?php echo htmlspecialchars($dni); ?></small></section>
<form method="POST" class="filter-panel employee-filter"><div><label for="periodo_filtro">Mes de liquidación</label><input type="month" id="periodo_filtro" name="periodo_filtro" value="<?php echo htmlspecialchars($periodo); ?>"></div><div class="filter-actions"><button type="submit">Aplicar filtro</button><a class="text-action" href="mostrarregistroempleado.php">Limpiar</a></div></form>
<div class="table-shell"><table><thead><tr><th>Período</th><th>Importe neto</th><th>Comprobante</th></tr></thead><tbody>
<?php if ($resultado && mysqli_num_rows($resultado) > 0): while ($fila = mysqli_fetch_assoc($resultado)): ?><tr><td><strong><?php echo htmlspecialchars($fila['periodo']); ?></strong></td><td>$<?php echo number_format((float)$fila['salario'], 2, ',', '.'); ?></td><td><a class="table-action" href="detalleliquidacion.php?id=<?php echo urlencode($fila['id_registro']); ?>">Descargar liquidación</a></td></tr><?php endwhile; else: ?><tr><td colspan="3" class="empty-state">No hay liquidaciones para el período seleccionado.</td></tr><?php endif; ?>
</tbody></table></div></main></body></html>
