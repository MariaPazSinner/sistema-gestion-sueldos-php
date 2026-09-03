<?php
include 'conexion.php';

$dni = isset($_POST['DNI']) ? $_POST['DNI'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $periodo = isset($_POST['periodo']) ? $_POST['periodo'] : '';
    $totalSueldo = isset($_POST['totalSueldo']) ? $_POST['totalSueldo'] : '';
}

// Si no se proporciona un DNI, realizar una consulta para obtener todos los registros
if (empty($dni) || $dni === "todos") {
    $consultaTabla = "SELECT r.id_registro, r.DNI, r.periodo, r.salario, e.nombre, e.apellido 
                     FROM registro r 
                     JOIN empleados e ON r.DNI = e.DNI";
} else {
    // Si se proporciona un DNI, filtrar por ese DNI
    $consultaTabla = "SELECT r.id_registro, r.DNI, r.periodo, r.salario, e.nombre, e.apellido 
                      FROM registro r 
                      JOIN empleados e ON r.DNI = e.DNI 
                      WHERE r.DNI = '$dni'";
}

$resultado = mysqli_query($mysqli, $consultaTabla);
if (!$resultado) {
    echo "Error: " . mysqli_error($mysqli);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Empleados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin: 0;
            background-image: url("fondonaranja.jpeg");
            background-size: cover; /* Esto hace que la imagen cubra toda la pantalla */
            background-repeat: no-repeat; /* Esto evita que la imagen se repita */
            background-position: center center; /* Esto centra la imagen */
        }
        h1 {
            font-size: 24px;
            color: #333;
            padding: 20px;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background: #007bff;
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            max-width: 800px;
            background: #fff;
            border-collapse: collapse;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: #007bff;
            color: #fff;
        }
        td {
            background: #f9f9f9;
        }
        a, button {
            color: #007bff;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
        }
        a:hover, button:hover {
            text-decoration: underline;
        }
        .container {
            width: 100%;
            max-width: 800px;
            text-align: center;
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
    <div class="container">
        <h1>Empleados</h1>

        <!-- Formulario para filtrar por DNI -->
        <form method="POST">
            <label for="DNI">Filtrar por DNI:</label>
            <select id="DNI" name="DNI">
                <option value="todos">Todos</option>
            </select>
            <input type="submit" value="Filtrar">
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Periodo</th>
                    <th>Salario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Iterar sobre los resultados y mostrarlos en la tabla
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    ?>
                    <tr>
                        <td><?php echo $fila['id_registro']; ?></td>
                        <td><?php echo $fila['DNI']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['apellido']; ?></td>
                        <td><?php echo $fila['periodo']; ?></td>
                        <td><?php echo '$' . number_format($fila['salario'], 2, '.', ','); ?></td>
                        <td>
                            <a href="bajadesdemostrar.php?id_registro=<?php echo $fila['id_registro']; ?>&DNI=<?php echo $fila['DNI']; ?>">BORRAR</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <a class="back-link" href="menusesiones.php">Ir al menú</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'get_dnis.php',
                type: 'GET',
                success: function(response) {
                    var dnis = JSON.parse(response);
                    var dniSelect = $('#DNI');
                    dnis.forEach(function(dni) {
                        dniSelect.append(new Option(dni, dni));
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar los DNIs:', error);
                }
            });
        });
    </script>
</body>
</html>
