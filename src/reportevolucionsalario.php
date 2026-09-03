<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = $_POST['dni'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    $query2 = "SELECT nombre FROM empleados WHERE DNI = '$dni'";
    $result2 = mysqli_query($mysqli, $query2);
    $row2 = mysqli_fetch_assoc($result2);
    $nombre = $row2['nombre'];

    // Convertir las fechas a formato compatible con SQL (YYYY-MM)
    $fecha_inicio_sql = date('Y-m-d', strtotime($fecha_inicio . "-01 -1 month")) . "-01";
    $fecha_fin_sql = $fecha_fin . "-31";

    // Consultar los salarios del empleado en el período seleccionado
    $sql = "SELECT periodo, salario FROM registro WHERE DNI = '$dni' AND periodo BETWEEN '$fecha_inicio_sql' AND '$fecha_fin_sql' ORDER BY periodo";
    $result = mysqli_query($mysqli, $sql);

    if (mysqli_num_rows($result) > 0) {
        $salarios = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $salarios[] = $row;
        }

        // Calcular el porcentaje de aumento o disminución de salarios
        $inicio_salario = $salarios[0]['salario'];
        $fin_salario = end($salarios)['salario'];
        $cambio_porcentaje = (($fin_salario - $inicio_salario) / $inicio_salario) * 100;
    } else {
        $error = "No se encontraron registros de salarios para el período seleccionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Evolución de Salarios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            background-image: url("fondonaranja.jpeg");
            background-size: cover; /* Esto hace que la imagen cubra toda la pantalla */
            background-repeat: no-repeat; /* Esto evita que la imagen se repita */
            background-position: center center; /* Esto centra la imagen */
        
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        .report-container {
            margin-top: 20px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .report-table th, .report-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .report-table th {
            background-color: #f2f2f2;
        }
        .report-table td {
            background-color: #fff;
        }
        .report-container p {
            font-size: 16px;
            color: #333;
            text-align: center;
        }
        .back-button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reporte de Evolución de Salarios</h1>
        <?php
        if (isset($error)) {
            echo "<p>" . $error . "</p>";
        } else {
            echo "<div class='report-container'>";
            echo "<h3 style='text-align: center;'> DNI: $dni | Nombre: $nombre</h3>";
            echo "<p>Periodo: " . $fecha_inicio . " a " . $fecha_fin . "</p>";
            echo "<table class='report-table'>";
            echo "<tr><th>Periodo</th><th>Salario</th></tr>";
            foreach ($salarios as $salario) {
                echo "<tr><td>" . $salario['periodo'] . "</td><td>" . $salario['salario'] . "</td></tr>";
            }
            echo "</table>";
            echo "<p>El salario ha ";
            if ($cambio_porcentaje >= 0) {
                echo "aumentado";
            } else {
                echo "disminuido";
            }
            echo " en un " . abs($cambio_porcentaje) . "% en el período seleccionado.</p>";
            echo "</div>";
        }
        ?>
        <a href="menusesiones.php" class="back-button">Volver al Menú</a>
    </div>
</body>
</html>
