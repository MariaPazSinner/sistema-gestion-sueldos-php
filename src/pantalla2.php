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
    <form action="pantalla3.php" method="POST" class="screen-card compact-form">
            <div class="screen-heading">
                <span class="eyebrow">Equipo</span>
                <h1>Confirmar nuevo salario</h1>
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
                    
                        echo "<div class='employee-summary'><span><small>Empleado</small><strong>$nombre $apellido</strong></span><span><small>DNI</small><strong>$dni</strong></span><span><small>Salario actual</small><strong>$" . number_format($salarioactual, 2, ',', '.') . "</strong></span></div>";
                    } else {
                        echo "<h4>Error: No se encontró el empleado con DNI $dni.</h4>";
                    }
                }
                ?>
                <div style="margin-bottom: 10px;">
                    <label for="salariobrutonew">Nuevo salario bruto</label>
                    <input id="salariobrutonew" type="number" min="1" step="0.01" name="salariobrutonew" placeholder="Ingresá el nuevo importe" required>
                </div>
                <div>
                    <input type="hidden" name="dni" value="<?php echo htmlspecialchars($dni); ?>">
                    <div class="form-actions"><a class="secondary-action" href="modificarsueldobruto.php">Cancelar</a><input type="submit" value="Actualizar salario"></div>
                </div>
            </div>
    </form>
</body>
</html>
