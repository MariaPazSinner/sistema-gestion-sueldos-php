<?php
// Iniciar la sesión
session_start();
include "conexion.php";

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $contrasenia = md5($_POST['contrasenia']); // Encriptar contraseña con MD5
    $dni = $_POST['dni']; // Obtener el DNI

    // Consulta a la base de datos
    $sql = "SELECT * FROM usuario WHERE id_usuario = '$usuario' AND claveIngreso = '$contrasenia' AND DNI = '$dni'";
    $result = mysqli_query($mysqli, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Usuario encontrado, iniciar sesión
        $_SESSION['usuario'] = $usuario; // Guardar el usuario en la sesión
        $_SESSION['dni'] = $dni; // Guardar el DNI en la sesión

        // Redirigir a la página del menú
        header("Location: menusesiones.php");
        exit();
    } else {
        // Usuario no encontrado
        $error = "Usuario, contraseña o DNI incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        input[type="text"], input[type="password"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
        a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-align: center;
        }
        a:hover {
            text-decoration: underline;
        }
        p {
            color: red;
            text-align: center;
        }

    </style>
</head>
<body>
    <form action="logininiciosesiones.php" method="POST">
        <h2>Login</h2>
        <fieldset>
            <div>
                <label for="usuario">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required>
            </div>
            <div>
                <label for="contrasenia">Contraseña:</label>
                <input type="password" id="contrasenia" name="contrasenia" required>
            </div>
            <div>
                <label for="dni">DNI:</label>
                <input type="number" id="dni" name="dni" required>
            </div>
            <div>
                <input type="submit" value="Enviar">
            </div>
            <div><a href="altausuarios.php">Crear usuario</a></div>
            <div><a href="recuperaContrasenia.php">Olvidé mi contraseña</a></div> 
        </fieldset>
        <?php
        if (isset($error)) {
            echo "<p>$error</p>";
        }
        ?>
    </form>  
</body>
</html>
