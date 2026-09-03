<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Reporte de Evolución de Salarios</title>
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
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        label {
            font-weight: bold;
            color: #333;
        }
        select, input[type="month"], input[type="submit"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }
        input[type="submit"] {
            font-size: 15px;
            display: block;
            margin-top: 20px;
            padding: 10px;
            background: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            width: 100%;
            max-width: 200px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        .back-button {
            display: block;
            margin-top: 20px;
            padding: 10px;
            background: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            width: 100%;
            max-width: 200px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            font-size: 15px;
            cursor: pointer;
        }
        .back-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Generar Reporte de Evolución de Salarios</h1>
        <form action="reportevolucionsalario.php" method="POST">
            <label for="dni">DNI del Empleado:</label>
            <select id="dni" name="dni" required>
                <?php
                include 'conexion.php';

                // Obtener los datos de los empleados
                $query = "SELECT DNI, nombre, apellido FROM empleados";
                $result = mysqli_query($mysqli, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $dni = $row['DNI'];
                        $nombre = $row['nombre'];
                        $apellido = $row['apellido'];
                        echo "<option value='$dni'>$dni - $nombre $apellido</option>";
                    }
                } else {
                    echo "<option value=''>No hay empleados disponibles</option>";
                }

                mysqli_close($mysqli);
                ?>
            </select>
            <br>
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="month" id="fecha_inicio" name="fecha_inicio" required>
            <br>
            <label for="fecha_fin">Fecha de Fin:</label>
            <input type="month" id="fecha_fin" name="fecha_fin" required>
            <br>
            <input type="submit" value="Generar Reporte">
        </form>
        <a class="back-button" href="menusesiones.php">Ir al menú</a>
    </div>
</body>
</html>
