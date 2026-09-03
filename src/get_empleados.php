<!--este codigo maneja el filtrado de empleados segun el puesto-->
<?php
include 'conexion.php';

$puesto = isset($_POST['puesto']) ? $_POST['puesto'] : '';

$sql = "SELECT e.DNI, e.nombre, e.apellido, p.nombre AS puesto, e.celular, e.mail, e.salarioBruto 
       FROM empleados e
       INNER JOIN puestos p ON e.id_puestos = p.id_puestos";
if ($puesto) {
    $sql .= " WHERE e.id_puestos = '$puesto'"; //Si $puesto tiene un valor, se añade una cláusula WHERE a la consulta para filtrar los resultados por el puesto seleccionado.
}

$result = mysqli_query($mysqli, $sql);

if (!$result) {
    echo "Error al ejecutar la consulta: " . mysqli_error($mysqli);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    // Se imprimen los encabezados de la tabla
    echo "<table>";
    echo "<tr><th>DNI</th><th>Nombre</th><th>Apellido</th><th>Puesto</th><th>Celular</th><th>Mail</th><th>Salario Bruto</th></tr>";
    // Se recorren los resultados y se imprimen en filas de la tabla
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["DNI"] . "</td>";
        echo "<td>" . $row["nombre"] . "</td>";
        echo "<td>" . $row["apellido"] . "</td>";
        echo "<td>" . $row["puesto"] . "</td>";
        echo "<td>" . $row["celular"] . "</td>";
        echo "<td>" . $row["mail"] . "</td>";
        echo "<td>" . $row["salarioBruto"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron empleados.";
}

/*Si la consulta devuelve resultados (mysqli_num_rows($result) es mayor que 0), 
se construye una tabla HTML que muestra los empleados y sus detalles. Si no hay resultados,
se muestra un mensaje indicando que no se encontraron empleados.*/

mysqli_close($mysqli);
?>