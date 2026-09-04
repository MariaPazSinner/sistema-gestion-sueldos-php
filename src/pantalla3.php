<?php
include 'conexion.php';
$destino = 'modificarsueldobruto.php?salary_error=1';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['dni']) && !empty($_POST['salariobrutonew'])) {
    $dni = $_POST['dni'];
    $nuevoSalarioBruto = (float)$_POST['salariobrutonew'];
    $stmt = $mysqli->prepare('SELECT nombre, apellido FROM empleados WHERE DNI = ?');
    $stmt->bind_param('i', $dni);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $update = $mysqli->prepare('UPDATE empleados SET salarioBruto = ? WHERE DNI = ?');
        $update->bind_param('di', $nuevoSalarioBruto, $dni);
        if ($update->execute()) {
            $destino = 'modificarsueldobruto.php?salary_updated=1&employee=' . urlencode($row['nombre'] . ' ' . $row['apellido']) . '&amount=' . urlencode(number_format($nuevoSalarioBruto, 2, ',', '.'));
        }
        $update->close();
    }
    $stmt->close();
}
header('Location: ' . $destino);
exit();
