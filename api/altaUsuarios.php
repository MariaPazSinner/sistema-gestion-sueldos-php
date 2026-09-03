<!DOCTYPE html>
<html>
<head>
    <title>Alta de Usuarios</title>
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
        legend {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <form action="confirmacionUsuario.php" method="POST">
        <fieldset>
            <legend>Alta de Usuarios</legend>
            </br>
            <label>Ingrese Nombre de usuario</label>
            <input type="text" name="nombre" required>
            </br>
            <label>Ingrese contraseña</label>
            <div class="password-container">
                <input type="password" name="contrasenia" id="password" required>
            </div>
            </br>
            <label>Ingrese DNI</label>
            <input type="text" name="dni" required>
            </br>
            <input type="submit" value="Crear">
            <br>
            <a class="back-link" href="logininiciosesiones.php">Ir al login</a>
        </fieldset>
    </form>
    
</body>
</html>
