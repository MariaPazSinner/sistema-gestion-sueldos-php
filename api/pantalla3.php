<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de la Modificación</title>
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
        .mensaje {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .back-link {
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
    </style>
</head>
<body>
    <div class="mensaje">
        <?php
        include 'conexion.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['dni']) && !empty($_POST['dni']) && isset($_POST['salariobrutonew']) && !empty($_POST['salariobrutonew'])) {
                $dni = $_POST['dni'];
                $nuevoSalarioBruto = $_POST['salariobrutonew'];

                // Obtener el nombre del empleado
                $query = "SELECT nombre, apellido FROM empleados WHERE DNI = ?";
                $stmt = $mysqli->prepare($query);
                $stmt->bind_param("i", $dni);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($row = $result->fetch_assoc()) {
                    $nombreCompleto = $row['nombre'] . ' ' . $row['apellido'];

                    // Actualizar el salario
                    $updateQuery = $mysqli->prepare("UPDATE empleados SET salarioBruto = ? WHERE DNI = ?");
                    $updateQuery->bind_param("di", $nuevoSalarioBruto, $dni);

                    if ($updateQuery->execute()) {
                        $nuevoSalarioBrutoFormateado = number_format($nuevoSalarioBruto, 2, ',', '.');
                        echo "<h2>El nuevo salario bruto de $nombreCompleto es $$nuevoSalarioBrutoFormateado.</h2>";
                    } else {
                        echo "<h2>Error al actualizar el salario bruto: " . $updateQuery->error . "</h2>";
                    }
                    $updateQuery->close();
                } else {
                    echo "<h2>Error: No se encontró el empleado con DNI $dni.</h2>";
                }
                $stmt->close();
            } else {
                echo "<h2>Por favor, complete todos los campos.</h2>";
            }
        }
        ?>
        <a class="back-link" href="menusesiones.php">Volver al menú</a>
    </div>
</body>
</html>
