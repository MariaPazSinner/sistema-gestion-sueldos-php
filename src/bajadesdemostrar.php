<?php
include "conexion.php";

$registro = isset($_GET['id_registro']) ? $_GET['id_registro'] : '';
$dniregistro = isset($_GET['DNI']) ? $_GET['DNI'] : '';
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$apellido = isset($_GET['apellido']) ? $_GET['apellido'] : '';

if (isset($_GET['id_registro'])) {
    // Ejecutar consulta para eliminar el registro con el id_registro especificado
    $consulta1 = "DELETE FROM registro WHERE id_registro = '$registro'";
    $resuconsulta = mysqli_query($mysqli, $consulta1);

    if ($resuconsulta) {
        $filasAfectadas = mysqli_affected_rows($mysqli);
        if ($filasAfectadas > 0) {
            // Consulta para verificar si aún hay registros con el mismo DNI
            $consulta2 = "SELECT * FROM registro WHERE DNI = '$dniregistro'";
            $resuconsulta2 = mysqli_query($mysqli, $consulta2);
            $registros = [];

            while ($fila = mysqli_fetch_assoc($resuconsulta2)) {
                $registros[] = $fila;
            }

            // Convertir el array de registros en una cadena JSON
            $registrosJson = json_encode($registros);
            
            // Redirigir al segundo script con el DNI, nombre, apellido y los resultados de la consulta
            header("Location: mostrarsueldoneto.php?DNI=$dniregistro&nombre=$nombre&apellido=$apellido&registros=" . urlencode($registrosJson));
            exit();
        } else {
            echo "El salario no se encontró";
        }
    } else {
        echo "Hubo un error al intentar eliminar el salario: " . mysqli_error($mysqli);
    }
} else {
    echo "Error: No se proporcionó el ID del registro";
}
?>
<br>
<form action="mostrarsueldoneto.php" method="POST">
    <input type="submit" value="Volver al listado">
</form>
<br>
<a href="menusesiones.php">Volver al menú</a>
