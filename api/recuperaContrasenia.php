<?php
include "conexion.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url("imagenfondologin.png");
            background-size: cover; /* Esto hace que la imagen cubra toda la pantalla */
            background-repeat: no-repeat; /* Esto evita que la imagen se repita */
            background-position: center center; /* Esto centra la imagen */
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        fieldset {
            border: none;
            padding: 0;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-align: center;
        }
        a:hover {
            text-decoration: none;
        }
        p {
            color: red;
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
        .mensaje {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <form action="recuperaContrasenia.php" method="POST">
        <h2>Restablecer Contraseña</h2>
        <fieldset>
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required><br><br>

            <label for="contrasenia">Nueva Contraseña:</label>
            <input type="password" id="contrasenia" name="contrasenia" required><br><br>

            <button type="submit">Restablecer</button>
            <br>
            <a class="back-link" href="logininiciosesiones.php">Ir al login</a><?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $usuario = $_POST['usuario'];
             $nueva_contrasenia = $_POST['contrasenia'];
// Validar que los campos no estén vacíos
if (empty($usuario) || empty($nueva_contrasenia)) {
    echo "Error: Todos los campos son obligatorios.";
    ?>
    <a href="recuperaContrasenia.php">Reintentar</a>
    <?php
}else {
     $query= "SELECT * FROM usuario WHERE id_usuario = '$usuario'";
     $result = mysqli_query($mysqli, $query);
    if (mysqli_num_rows($result) > 0) {
        // Usuario encontrado
         // Encriptar la nueva contraseña
        $nueva_contrasenia = md5($nueva_contrasenia);
        $sql = "UPDATE usuario SET claveIngreso = '$nueva_contrasenia' WHERE id_usuario = '$usuario'";

        if (mysqli_query($mysqli, $sql)) {
            ?>
            <br><?php
            echo "Contraseña actualizada exitosamente.";
        } else {
            echo "Error al actualizar la contraseña: " . mysqli_error($mysqli);
            ?>
            <a href="recuperaContrasenia.php">Reintentar</a>
            <?php
        }
    } else {
        // Usuario no encontrado
        echo "El usuario no existe";
    }
   
}
}
mysqli_close($mysqli);
?>
            </fieldset>
        </form>
</body>
</html>