<?php
include 'conexion.php';
$id = (int)($_GET['id'] ?? 0);
$resultado = mysqli_query($mysqli, "SELECT r.id_registro, r.periodo, r.salario, e.DNI, e.nombre, e.apellido, e.salarioBruto FROM registro r JOIN empleados e ON r.DNI=e.DNI WHERE r.id_registro=$id LIMIT 1");
$dato = $resultado ? mysqli_fetch_assoc($resultado) : null;
if ($dato) {
    $bruto = (float)$dato['salarioBruto']; $neto = (float)$dato['salario'];
    $jubilacion = $bruto * .11; $obraSocial = $bruto * .03; $inssjp = $bruto * .03;
    $descuentos = $jubilacion + $obraSocial + $inssjp;
    $ajustes = $neto - $bruto + $descuentos;
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
