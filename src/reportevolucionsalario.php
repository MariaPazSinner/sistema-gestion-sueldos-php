<?php
include 'conexion.php';
$error = '';
$salarios = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'] ?? '';
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? '';
    if (!$dni || !$fecha_inicio || !$fecha_fin || $fecha_inicio > $fecha_fin) {
        $error = 'Revisá el empleado y el rango de períodos seleccionado.';
    } else {
        $dni_sql = mysqli_real_escape_string($mysqli, $dni);
        $inicio_sql = mysqli_real_escape_string($mysqli, $fecha_inicio);
        $fin_sql = mysqli_real_escape_string($mysqli, $fecha_fin);
        $result2 = mysqli_query($mysqli, "SELECT nombre, apellido FROM empleados WHERE DNI = '$dni_sql'");
        $empleado = $result2 ? mysqli_fetch_assoc($result2) : null;
        $nombre = $empleado ? $empleado['apellido'] . ', ' . $empleado['nombre'] : 'Empleado';
        $result = mysqli_query($mysqli, "SELECT periodo, salario FROM registro WHERE DNI = '$dni_sql' AND periodo BETWEEN '$inicio_sql' AND '$fin_sql' ORDER BY periodo");
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) $salarios[] = $row;
            $inicio_salario = (float)$salarios[0]['salario'];
            $fin_salario = (float)$salarios[count($salarios) - 1]['salario'];
            $cambio_porcentaje = $inicio_salario != 0 ? (($fin_salario - $inicio_salario) / $inicio_salario) * 100 : 0;
        } else $error = 'No encontramos liquidaciones para ese empleado en el período seleccionado.';
    }
} else $error = 'Seleccioná los datos del reporte para comenzar.';
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Evolución salarial</title></head><body>
<main class="screen-card report-card"><header class="screen-toolbar"><div class="title-block"><span class="eyebrow">Reportes</span><h1>Evolución salarial</h1><p>Detalle mensual del rango seleccionado.</p></div><div class="header-actions"><button class="secondary-action" type="button" onclick="window.print()">Descargar reporte</button><a class="secondary-action" href="generarReporte.php">Nuevo reporte</a></div></header>
<?php if ($error): ?><div class="empty-panel"><strong>No pudimos generar el reporte</strong><p><?php echo htmlspecialchars($error); ?></p></div>
<?php else: ?><section class="report-summary"><div><span>Empleado</span><strong><?php echo htmlspecialchars($nombre); ?></strong><small>DNI <?php echo htmlspecialchars($dni); ?></small></div><div><span>Período analizado</span><strong><?php echo htmlspecialchars($fecha_inicio); ?> — <?php echo htmlspecialchars($fecha_fin); ?></strong><small><?php echo count($salarios); ?> liquidaciones</small></div><div class="metric <?php echo $cambio_porcentaje >= 0 ? 'positive' : 'negative'; ?>"><span>Variación</span><strong><?php echo ($cambio_porcentaje >= 0 ? '+' : '−') . number_format(abs($cambio_porcentaje), 2, ',', '.'); ?>%</strong><small>del primer al último período</small></div></section>
<div class="table-shell"><table><thead><tr><th>Período</th><th>Importe liquidado</th></tr></thead><tbody><?php foreach ($salarios as $salario): ?><tr><td><strong><?php echo htmlspecialchars($salario['periodo']); ?></strong></td><td>$<?php echo number_format((float)$salario['salario'], 2, ',', '.'); ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
</main></body></html>
