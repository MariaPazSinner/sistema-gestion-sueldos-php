<?php
include 'conexion.php';
$id = (int)($_GET['id'] ?? 0);
mysqli_query($mysqli, "CREATE TABLE IF NOT EXISTS liquidacion_detalle (id_registro INT PRIMARY KEY, sueldo_bruto DECIMAL(14,2) NOT NULL, ajustes DECIMAL(14,2) NOT NULL, aporte_jubilatorio DECIMAL(14,2) NOT NULL, obra_social DECIMAL(14,2) NOT NULL, inssjp DECIMAL(14,2) NOT NULL, total_descuentos DECIMAL(14,2) NOT NULL)");
$resultado = mysqli_query($mysqli, "SELECT r.id_registro, r.periodo, r.salario, e.DNI, e.nombre, e.apellido, e.salarioBruto, ld.sueldo_bruto, ld.ajustes, ld.aporte_jubilatorio, ld.obra_social, ld.inssjp, ld.total_descuentos FROM registro r JOIN empleados e ON r.DNI=e.DNI LEFT JOIN liquidacion_detalle ld ON r.id_registro=ld.id_registro WHERE r.id_registro=$id LIMIT 1");
$dato = $resultado ? mysqli_fetch_assoc($resultado) : null;
if ($dato) {
    $bruto = $dato['sueldo_bruto'] !== null ? (float)$dato['sueldo_bruto'] : (float)$dato['salarioBruto'];
    $neto = (float)$dato['salario'];
    $jubilacion = $dato['aporte_jubilatorio'] !== null ? (float)$dato['aporte_jubilatorio'] : $bruto * .11;
    $obraSocial = $dato['obra_social'] !== null ? (float)$dato['obra_social'] : $bruto * .03;
    $inssjp = $dato['inssjp'] !== null ? (float)$dato['inssjp'] : $bruto * .03;
    $descuentos = $dato['total_descuentos'] !== null ? (float)$dato['total_descuentos'] : $jubilacion + $obraSocial + $inssjp;
    $ajustes = $dato['ajustes'] !== null ? (float)$dato['ajustes'] : $neto - $bruto + $descuentos;
}
function importe($valor) { return '$' . number_format((float)$valor, 2, ',', '.'); }
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Detalle de liquidación</title></head><body>
<main class="screen-card receipt-card">
<?php if ($dato): ?><header class="receipt-header"><div><span class="eyebrow">Comprobante de liquidación</span><h1>Gestión de Sueldos</h1><p>Empresa Demo S.A.</p></div><div class="receipt-period"><span>Período</span><strong><?php echo htmlspecialchars($dato['periodo']); ?></strong></div></header>
<section class="employee-detail"><div><span>Empleado</span><strong><?php echo htmlspecialchars($dato['nombre'] . ' ' . $dato['apellido']); ?></strong></div><div><span>DNI</span><strong><?php echo htmlspecialchars($dato['DNI']); ?></strong></div></section>
<section class="receipt-lines"><div class="detail-row"><span>Sueldo bruto</span><strong><?php echo importe($bruto); ?></strong></div><div class="detail-row"><span>Ajustes y novedades</span><strong><?php echo importe($ajustes); ?></strong></div><div class="detail-row"><span>Aporte jubilatorio (11%)</span><strong>−<?php echo importe($jubilacion); ?></strong></div><div class="detail-row"><span>Obra social (3%)</span><strong>−<?php echo importe($obraSocial); ?></strong></div><div class="detail-row"><span>INSSJP (3%)</span><strong>−<?php echo importe($inssjp); ?></strong></div><div class="detail-row receipt-total"><span>Total descuentos</span><strong>−<?php echo importe($descuentos); ?></strong></div><div class="detail-row receipt-net"><span>Total a liquidar</span><strong><?php echo importe($neto); ?></strong></div></section>
<p class="receipt-note">Comprobante generado por el sistema de Gestión de Sueldos.</p><div class="form-actions no-print"><button class="primary-action" type="button" onclick="window.print()">Descargar / guardar PDF</button><a class="secondary-action" href="mostrarsueldoneto.php">Volver a liquidaciones</a></div>
<?php else: ?><div class="empty-panel"><h1>Liquidación no encontrada</h1><p>El comprobante solicitado no está disponible.</p><a class="secondary-action" href="mostrarsueldoneto.php">Volver a liquidaciones</a></div><?php endif; ?>
</main></body></html>
