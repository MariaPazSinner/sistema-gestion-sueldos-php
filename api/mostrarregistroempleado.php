<?php
include 'conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['DNI']) && !empty($_POST['DNI'])) {
        $dni = $_POST['DNI'];

        $consultaTabla = "SELECT r.id_registro, r.DNI, r.periodo, r.salario, e.nombre, e.apellido 
        FROM registro r 
        JOIN empleados e ON r.DNI = e.DNI 
        WHERE r.DNI = '$dni'";
        $resultado = mysqli_query($mysqli, $consultaTabla);

        // Verifica si la consulta se ejecutó correctamente
        if ($resultado) {
            $registros = [];
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $registros[] = $fila;
            }
        } else {
            // Si hay algún error en la consulta
            echo "Error en la consulta: " . mysqli_error($mysqli);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de liquidaciones</title>
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
            background-size: cover; /* Hace que la imagen cubra toda la pantalla */
            background-repeat: no-repeat; /* Evita que la imagen se repita */
            background-position: center center; /* Centra la imagen */
            min-height: 100vh; /* Asegura que el fondo cubra toda la altura de la ventana */
            background-attachment: fixed;
        }
        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
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
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
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
    
    <?php if (!empty($registros)) { ?>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Periodo</th>
                <th>Salario</th>
                
            </tr>
        </thead>
        <tbody>
            <?php
                // Si hay resultados en la consulta, mostrar los registros
                foreach ($registros as $fila) {
            ?>
            <tr>
                <td><?php echo $fila['id_registro']; ?></td>
                <td><?php echo $fila['DNI']; ?></td>
                <td><?php echo $fila['nombre']; ?></td>
                <td><?php echo $fila['apellido']; ?></td>
                <td><?php echo $fila['periodo']; ?></td>
                <td><?php echo '$' . number_format($fila['salario'],2,'.',','); ?></td>
                </tr>
            <?php
                }
            ?>
        </tbody>
    </table>
    <?php } else { ?>
        <p>No hay registros para el DNI especificado.</p>
        <?php } ?>
    <!-- Formulario para volver al menú -->
    <br>
    <a class="back-link" href="menusesiones.php">Ir al menú</a>
</body>
</html>
