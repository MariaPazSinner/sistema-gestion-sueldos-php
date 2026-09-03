<?php
include "conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $contrasenia = md5($_POST['contrasenia']); // Encriptar contraseña con MD5
    $dni = $_POST['dni'];

    // Validar que el DNI exista en la tabla empleado o supervisor
    $empleado_check = mysqli_query($mysqli, "SELECT * FROM empleados WHERE DNI = '$dni'");
   // $supervisor_check = mysqli_query($mysqli, "SELECT * FROM supervisores WHERE id_supervisor = '$dni'");

    // Validar que el usuario no exista con el mismo DNI
    $usuario_check = mysqli_query($mysqli, "SELECT * FROM usuario WHERE DNI = '$dni'");

    if (mysqli_num_rows($empleado_check) == 0 ) {
        echo "<div class='message'>Error: El DNI no existe en la base de datos de empleados o supervisores.<a href='altausuarios.php'>Reintentar</a></div>";
    } elseif (mysqli_num_rows($usuario_check) > 0) {
        echo "<div class='message'>Error: Ya existe un usuario con ese DNI.<a href='altausuarios.php'>Reintentar</a></div>";
    } else {
        // Determinar el tipo de usuario y el codigo_rol
        if (mysqli_num_rows($empleado_check) > 0) {
            $tipo = 1; // Empleado
            $codigo_rol = 1;
        } else {
            $tipo = 0; // Supervisor
            $codigo_rol = 0;
        }

        // Insertar el nuevo usuario en la tabla usuario
        $sql = "INSERT INTO usuario (id_usuario, DNI, claveIngreso, tipo, codigo_rol) VALUES ('$nombre', '$dni', '$contrasenia', 'MD5', '$codigo_rol')";
        
        if (mysqli_query($mysqli, $sql)) {
            // Actualizar la tabla correspondiente con el id_usuario
            if ($tipo == 1) {
                mysqli_query($mysqli, "UPDATE empleados SET id_usuario = '$nombre' WHERE DNI = '$dni'");
            } 
            echo "<div class='message success'>Usuario creado exitosamente.<br><a href='logininiciosesiones.php'>Iniciar sesión</a></div>";
        } else {
            echo "<div class='message'>Error al crear el usuario: " . mysqli_error($mysqli) . "<a href='altausuarios.php'>Reintentar</a></div>";
        }
    }
}

mysqli_close($mysqli);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Usuario</title>
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
        .message {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .message.success {
            border: 2px solid green;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        a:hover {
            background: #0056b3;
        }

    </style>
</head>
<body>

</body>
</html>
