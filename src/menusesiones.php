<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: logininiciosesiones.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Consultar el rol del usuario
$query = "SELECT codigo_rol FROM usuario WHERE id_usuario = '$usuario'";
$result = mysqli_query($mysqli, $query);
$row = mysqli_fetch_assoc($result);

$codigo_rol = $row['codigo_rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>
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
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        fieldset {
            border: none;
            padding: 0;
        }
        a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 16px;
        }
        a:hover {
            background: #007cff;
        }
        a:last-child {
            background: #007bff;
        }
        a:last-child:hover {
            background: #5EA3F5;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            padding: 10px;
            background: #007bff;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            width: 150px;
            max-width: 200px;
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            border: none;
        }
        .back-link:hover {
            background: #0056b3;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo htmlspecialchars($usuario); ?></h1>

        <?php if ($codigo_rol == 0): ?>
        <form>
            <fieldset>
                <div>
                    <a href="mostrarsueldoneto.php">Mostrar listado</a>
                </div>
                <div>
                    <a href="altasueldoneto.php">Cargar Sueldo Neto</a>
                </div>
                <div>
                    <a href="altaempleados.php">Agregar Empleado</a>
                </div>
                <div>
                    <a href="modificarsueldobruto.php">Modificar Sueldo Bruto</a>
                </div>
                <div>
                    <a href="consultardatos.php">Consultar datos de empleados</a>
                </div>
                <div>
                    <a href="generarReporte.php">Generar reporte</a>
                </div>
                <div>
                    <a href="cerrarsesion.php">Cerrar sesión</a>
                </div>
            </fieldset>
        </form>
        <?php else: // Consultar el rol del usuario
        $query2 = "SELECT DNI FROM empleados WHERE id_usuario = '$usuario'";
        $result2 = mysqli_query($mysqli, $query2);
        $row2 = mysqli_fetch_assoc($result2);
        $dni = $row2['DNI'];
        ?>
        <form action="mostrarregistroempleado.php" method="POST" id="form-mostrar-registro">
            <input type="hidden" name="DNI" value="<?php echo $dni; ?>">
            <fieldset>
                <div>
                    <button class="back-link" type="submit">Mostrar listado</button>
                </div>
                <div>
                    <a href="cerrarsesion.php" class="back-link">Cerrar sesión</a>
                </div>
            </fieldset>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>