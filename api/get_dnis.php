<?php
include 'conexion.php';

$query = "SELECT DISTINCT DNI FROM registro";
$result = mysqli_query($mysqli, $query);

$dnis = [];
while ($row = mysqli_fetch_assoc($result)) {
    $dnis[] = $row['DNI'];
}

echo json_encode($dnis);
?>
