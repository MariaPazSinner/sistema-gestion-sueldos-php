<?php include 'conexion.php'; $empleados = mysqli_query($mysqli, "SELECT DNI, nombre, apellido FROM empleados ORDER BY apellido, nombre"); ?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Reporte de evolución salarial</title></head><body>
<main class="screen-card compact-form report-card">
<header class="screen-heading"><span class="eyebrow">Reportes</span><h1>Evolución salarial</h1><p>Compará las liquidaciones de una persona entre dos períodos y visualizá su variación.</p></header>
<div class="report-intro"><strong>Análisis histórico</strong><span>Elegí un empleado y un rango mensual. El informe resume la evolución con sus importes y porcentaje de cambio.</span></div>
<form action="reportevolucionsalario.php" method="POST">
<label for="dni">Empleado</label><select id="dni" name="dni" required><option value="">Seleccionar empleado</option><?php while ($row = mysqli_fetch_assoc($empleados)): ?><option value="<?php echo htmlspecialchars($row['DNI']); ?>"><?php echo htmlspecialchars($row['apellido'] . ', ' . $row['nombre'] . ' · ' . $row['DNI']); ?></option><?php endwhile; ?></select>
<div class="filter-grid"><div><label for="fecha_inicio">Desde</label><input type="month" id="fecha_inicio" name="fecha_inicio" required></div><div><label for="fecha_fin">Hasta</label><input type="month" id="fecha_fin" name="fecha_fin" required></div></div>
<div class="form-actions"><button type="submit">Generar análisis</button></div>
</form></main></body></html>
