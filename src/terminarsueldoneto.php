<?php
include 'conexion.php';
$error = '';
$guardado = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['DNI'] ?? '';
    $periodo = $_POST['periodo'] ?? '';
    $ausenciaRemunerada = (float)($_POST['cantidadAR'] ?? 0);
    $ausenciaNoRemunerada = (float)($_POST['cantidadANR'] ?? 0);
    $horasFeriado = (float)($_POST['cantidadHEFER'] ?? 0);
    $horasExtra = (float)($_POST['cantidadHE'] ?? 0);
    $dniSql = mysqli_real_escape_string($mysqli, $dni);
    $periodoSql = mysqli_real_escape_string($mysqli, $periodo);
    $empleadoResult = mysqli_query($mysqli, "SELECT nombre, apellido, salarioBruto FROM empleados WHERE DNI = '$dniSql'");
    $empleado = $empleadoResult ? mysqli_fetch_assoc($empleadoResult) : null;
    if (!$empleado || !$periodo) {
        $error = 'No pudimos validar el empleado o el período seleccionado.';
    } else {
        $nombreCompleto = $empleado['nombre'] . ' ' . $empleado['apellido'];
        $sueldoBruto = (float)$empleado['salarioBruto'];
        $conceptos = [
            'Ausencia remunerada' => 0,
            'Ausencia no remunerada' => -(($sueldoBruto / 21) * $ausenciaNoRemunerada),
            'Horas extra en feriado' => (($sueldoBruto / 160) * 2) * $horasFeriado,
            'Horas extra regulares' => (($sueldoBruto / 160) * 1.5) * $horasExtra,
        ];
        $aporteJubilatorio = $sueldoBruto * .11;
        $obraSocial = $sueldoBruto * .03;
        $inssjp = $sueldoBruto * .03;
        $totalDescuentos = $aporteJubilatorio + $obraSocial + $inssjp;
        $sumaConceptos = array_sum($conceptos);
        $totalSueldo = $sueldoBruto + $sumaConceptos - $totalDescuentos;
        $duplicado = mysqli_query($mysqli, "SELECT id_registro FROM registro WHERE DNI = '$dniSql' AND periodo = '$periodoSql' LIMIT 1");
        if ($duplicado && mysqli_num_rows($duplicado) > 0) {
            $error = 'Ya existe una liquidación para este empleado en el período seleccionado.';
        } else {
            $totalSql = (float)$totalSueldo;
            if (mysqli_query($mysqli, "INSERT INTO registro (DNI, periodo, salario) VALUES ('$dniSql', '$periodoSql', '$totalSql')")) {
                $registroId = mysqli_insert_id($mysqli);
                $guardado = true;
            } else $error = 'No pudimos registrar la liquidación. Revisá los importes e intentá nuevamente.';
        }
    }
} else $error = 'No se recibieron datos para generar la liquidación.';
function moneda($valor) { return '$' . number_format((float)$valor, 2, ',', '.'); }
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Resultado de la liquidación</title></head><body>
<main class="screen-card payroll-result">
<?php if ($guardado): ?>
<header class="screen-toolbar"><div class="title-block"><span class="eyebrow">Liquidación registrada</span><h1><?php echo htmlspecialchars($nombreCompleto); ?></h1><p>Período <?php echo htmlspecialchars($periodo); ?> · DNI <?php echo htmlspecialchars($dni); ?></p></div><span class="success-badge">Guardada correctamente</span></header>
<section class="payroll-summary"><div><span>Sueldo bruto</span><strong><?php echo moneda($sueldoBruto); ?></strong></div><div><span>Total descuentos</span><strong>−<?php echo moneda($totalDescuentos); ?></strong></div><div class="net-total"><span>Total a liquidar</span><strong><?php echo moneda($totalSueldo); ?></strong></div></section>
<div class="payroll-columns"><section><h2>Novedades y conceptos</h2><?php foreach ($conceptos as $nombreConcepto => $importe): ?><div class="detail-row"><span><?php echo htmlspecialchars($nombreConcepto); ?></span><strong><?php echo moneda($importe); ?></strong></div><?php endforeach; ?></section><section><h2>Descuentos</h2><div class="detail-row"><span>Aporte jubilatorio (11%)</span><strong>−<?php echo moneda($aporteJubilatorio); ?></strong></div><div class="detail-row"><span>Obra social (3%)</span><strong>−<?php echo moneda($obraSocial); ?></strong></div><div class="detail-row"><span>INSSJP (3%)</span><strong>−<?php echo moneda($inssjp); ?></strong></div></section></div>
<div class="form-actions"><a class="primary-action" href="detalleliquidacion.php?id=<?php echo urlencode($registroId); ?>">Descargar detalle</a><a class="secondary-action" href="mostrarsueldoneto.php">Ver liquidaciones</a><a class="text-action" href="altasueldoneto.php">Crear otra</a></div>
<?php else: ?><div class="empty-panel"><span class="eyebrow">Liquidaciones</span><h1>No pudimos completar la operación</h1><p><?php echo htmlspecialchars($error); ?></p><div class="form-actions"><a class="primary-action" href="altasueldoneto.php">Volver a intentar</a><a class="secondary-action" href="menusesiones.php">Ir al menú</a></div></div><?php endif; ?>
</main></body></html>
