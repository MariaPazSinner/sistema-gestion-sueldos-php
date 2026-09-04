<?php
// Manejar la lógica PHP antes de cualquier salida de HTML
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dni']) && !empty($_POST['dni'])) {
    include 'conexion.php';

    $dni = $_POST['dni'];

    // Escapar el DNI para evitar SQL injection
    $dni_escapado = mysqli_real_escape_string($mysqli, $dni);

    $consulta_empleado = "SELECT * FROM empleados WHERE DNI = '$dni_escapado'";
    $resultado_empleado = mysqli_query($mysqli, $consulta_empleado);

    if (mysqli_num_rows($resultado_empleado) == 0) {
        $error_message = 'El DNI ingresado no existe en la base de datos.';
    } else {
        // Redirigir a pantalla2.php con el DNI como parámetro
        header("Location: pantalla2.php?dni=$dni_escapado");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Salario Bruto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url("fondonaranja.jpeg");
            background-size: cover; /* Esto hace que la imagen cubra toda la pantalla */
            background-repeat: no-repeat; /* Esto evita que la imagen se repita */
            background-position: center center; /* Esto centra la imagen */
        }
        .back-link {
            font-family: arial;
            display: block;
            margin-top: 20px;
            padding: 10px;
            background: #007bff;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            width: 100%;
            max-width: 200px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
        }
        .back-link:hover {
            background: #0056b3;
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
    </style>
</head>

<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="screen-card compact-form">
            <div class="screen-heading">
                <span class="eyebrow">Equipo</span>
                <h1>Actualizar salario bruto</h1>
                <p>Elegí el empleado cuyo salario contractual necesitás modificar.</p>
            </div>
            <div>
                <label for="dni-salario">Empleado</label>
                <select id="dni-salario" name="dni" required>
                    <?php
                    include 'conexion.php';

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
            </div>
            <div>
                <input type="submit" value="Continuar">
            </div>
    </form>

    <?php
    // Mostrar mensaje de error si existe
    if (!empty($error_message)) {
        echo '<p style="font-family: Arial, sans-serif; color: red; text-align: center; margin-top: 10px;">' . $error_message . '</p>';
    }
    ?>
</body>
</html>
