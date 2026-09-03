<?php
include 'conexion.php';

$dni = isset($_POST['DNI']) ? $_POST['DNI'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] === 'eliminar') {
    $dni = isset($_POST['dniEliminar']) ? $_POST['dniEliminar'] : '';

    if (!empty($dni)) {
        $dni_escapado = mysqli_real_escape_string($mysqli, $dni);

        // Consulta para verificar si el empleado existe
        $consulta_empleado = "SELECT * FROM empleados WHERE DNI = '$dni_escapado'";
        $resultado_empleado = mysqli_query($mysqli, $consulta_empleado);

        if (mysqli_num_rows($resultado_empleado) == 0) {
            // Redirigir a la misma página con mensaje de error
            header("Location: consultardatos.php?mensaje=El empleado con DNI $dni no existe en la base de datos.");
            exit();
        } else {
            // Borrar el empleado
            $borrar_empleado = "DELETE FROM empleados WHERE DNI = '$dni_escapado'";
            if (mysqli_query($mysqli, $borrar_empleado)) {
                // Redirigir a la misma página con mensaje de éxito
                header("Location: consultardatos.php?mensaje=Empleado con DNI $dni ha sido eliminado exitosamente.");
                exit();
            } else {
                // Redirigir a la misma página con mensaje de error
                $error = mysqli_error($mysqli);
                header("Location: consultardatos.php?mensaje=Error al eliminar el empleado: $error");
                exit();
            }
        }
    }
}

// Consulta para obtener los datos de los empleados
if (empty($dni) || $dni === "todos") {
    $consultaTabla = "SELECT e.DNI, e.nombre, e.apellido, e.celular, e.mail, p.nombre AS nombre_puesto, d.nombre AS nombre_departamento, e.salarioBruto, e.id_usuario
                      FROM empleados e
                      LEFT JOIN puestos p ON e.id_puestos = p.id_puestos
                      LEFT JOIN departamento d ON p.id_departamento = d.id_departamento";
} else {
    $consultaTabla = "SELECT e.DNI, e.nombre, e.apellido, e.celular, e.mail, p.nombre AS nombre_puesto, d.nombre AS nombre_departamento, e.salarioBruto, e.id_usuario
                      FROM empleados e
                      LEFT JOIN puestos p ON e.id_puestos = p.id_puestos
                      LEFT JOIN departamento d ON p.id_departamento = d.id_departamento
                      WHERE e.DNI = '$dni'";
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
            background-color: #f3f4f6;
            margin: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            background-image: url("fondonaranja.jpeg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 900px;
            box-sizing: border-box;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
        }
        select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            display: block;
        }
        input[type="submit"] {
            background: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            display: inline-block;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        table {
            width: 100%;
            background-color: #fff;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
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
        .custom-button {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.custom-button:hover {
    background-color: #0056b3;
}

.custom-button:active {
    background-color: #004080;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Empleados</h1>

        <!-- Mostrar el mensaje si existe -->
        <?php
        if (isset($_GET['mensaje']) && !empty($_GET['mensaje'])) {
            $mensaje = htmlspecialchars($_GET['mensaje']);
            echo '<p style="font-family: Arial, sans-serif; color: green; text-align: center; margin-top: 10px;">' . $mensaje . '</p>';
        }
        ?>

        <!-- Formulario para filtrar por DNI -->
        <form method="POST">
            <label for="DNI">Filtrar por DNI:</label>
            <select id="DNI" name="DNI">
                <option value="todos">Todos</option>
                <?php
                // Obtener DNIs disponibles desde la base de datos
                $query_dnies = "SELECT DISTINCT DNI FROM empleados";
                $result_dnies = mysqli_query($mysqli, $query_dnies);
                
                while ($row = mysqli_fetch_assoc($result_dnies)) {
                    $selected = ($dni == $row['DNI']) ? 'selected' : '';
                    echo "<option value='" . $row['DNI'] . "' $selected>" . $row['DNI'] . "</option>";
                }
                ?>
            </select>
            <input type="submit" value="Filtrar">
        </form>

        <table>
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Celular</th>
                    <th>Mail</th>
                    <th>Puesto</th>
                    <th>Departamento</th>
                    <th>Salario Bruto</th>
                    <th>Borrar</th>
                    <th>Modificar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Iterar sobre los resultados y mostrarlos en la tabla
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    ?>
                    <tr>
                        <td><?php echo $fila['DNI']; ?></td>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['apellido']; ?></td>
                        <td><?php echo $fila['celular']; ?></td>
                        <td><?php echo $fila['mail']; ?></td>
                        <td><?php echo $fila['nombre_puesto']; ?></td>
                        <td><?php echo $fila['nombre_departamento']; ?></td>
                        <td><?php echo '$' . number_format($fila['salarioBruto'], 2, '.', ','); ?></td>
                        <!-- Botón en consultardatos.php -->
                         <td><form action="borrarempleado.php" method="POST" style="display:inline;">
                            <input type="hidden" name="dniEliminar" value="<?php echo $fila['DNI']; ?>">
                            <input type="hidden" name="accion" value="eliminar">
                            <button type="submit" class="custom-button" onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">Borrar</button>
                        </form></td>
                        <td><a class="custom-button" href="modificarempleado.php?DNI=<?php echo $fila['DNI']; ?>" >Modificar</a></td>


                     </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <a class="back-link" href="menusesiones.php">Ir al menú</a>
    </div>
</body>
</html>
