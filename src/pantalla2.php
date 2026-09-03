<!DOCTYPE html>
<html lang="en">
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
    <form action="pantalla3.php" method="POST">
        <fieldset style="font-family: Arial, sans-serif; width: 100%; margin: 50px auto; background-color: #fff; border: 1px solid #ccc; padding: 20px; border-radius: 10px;">
            <div style="font-family: Arial, sans-serif; text-align: center;">
                <h2>Modificar Salario Bruto</h2>
            </div>
            <div>
                <?php
                include 'conexion.php';

                // Mostrar el DNI recibido desde modificarsueldobruto.php
                if (isset($_GET['dni']) && !empty($_GET['dni'])) {
                    $dni = $_GET['dni'];
                    $query = "SELECT nombre, apellido, salarioBruto FROM empleados WHERE DNI = '$dni'";
                    $querynombre = mysqli_query($mysqli, $query);
                    if ($row = mysqli_fetch_assoc($querynombre)) {
                        $nombre = $row['nombre'];
                        $apellido = $row['apellido'];
                        $salarioactual = $row['salarioBruto'];
                    
                        echo "<h4>DNI ingresado: $dni</h4>";
                        echo "<h4>Empleado: $nombre $apellido </h4>";
                        echo "<h4>Salario bruto actual: $salarioactual </h4>";
                    } else {
                        echo "<h4>Error: No se encontró el empleado con DNI $dni.</h4>";
                    }
                }
                ?>
                <div style="margin-bottom: 10px;">
                    <h2>Ingrese el nuevo salario bruto:</h2>
                    <input type="text" name="salariobrutonew" placeholder="Ingrese salario" required style="font-family: Arial, sans-serif; width: 100%; padding: 8px; font-size: 1em; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box;">
                </div>
                <div>
                    <input type="hidden" name="dni" value="<?php echo htmlspecialchars($dni); ?>">
                    <input type="submit" value="Enviar">
                </div>
            </div>
            <br>
            <a class="back-link" href="menusesiones.php">Ir al menú</a>
        </fieldset>
    </form>
</body>
</html>
