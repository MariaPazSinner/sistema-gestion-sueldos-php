<?php
include 'conexion.php';

if (isset($_GET['DNI']) && !empty($_GET['DNI'])) {
    $dni = $_GET['DNI'];

    // Escapar el DNI para evitar SQL injection
    $dni_escapado = mysqli_real_escape_string($mysqli, $dni);

    // Consulta para obtener los datos del empleado
    $consulta = "SELECT * FROM empleados WHERE DNI = '$dni_escapado'";
    $resultado = mysqli_query($mysqli, $consulta);

    if ($resultado) {
        $empleado = mysqli_fetch_assoc($resultado);
    } else {
        echo "Error al obtener los datos del empleado.";
        exit();
    }
} else {
    echo "Error: No se proporcionó un DNI.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Empleado</title>
    <style>
        /* Copia el mismo estilo CSS del archivo de alta aquí */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            background-image: url("fondonaranja.jpeg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }
        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        fieldset {
            width: 50%;
            margin: 50px auto;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
        }
        legend {
            font-size: 1.5em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            padding: 10px;
        }
        input[type="text"], input[type="number"], input[type="email"] {
            width: 100%;
            padding: 8px;
            font-size: 1em;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        select {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            text-align: center;
        }
        input[type="submit"] {
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
        .error {
            color: red;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        a.back-link {
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
        a.back-link:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <fieldset>
        <h1>Modificar Empleado</h1>
        <form id="empleado-form" action="actualizarempleado.php" method="POST" onsubmit="return validarFormulario()">
            <input type="hidden" name="dni" value="<?php echo htmlspecialchars($empleado['DNI']); ?>">

            <label>Ingrese nombre del empleado:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($empleado['nombre']); ?>">
            <div id="nombre-error" class="error"></div><br>

            <label>Ingrese apellido del empleado:</label><br>
            <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($empleado['apellido']); ?>">
            <div id="apellido-error" class="error"></div><br>

            <label>Seleccione puesto del empleado:</label><br>
            <?php
            $puestos = "SELECT * FROM puestos";
            $consultaPuestos = mysqli_query($mysqli, $puestos);
            if ($consultaPuestos) {
                echo '<select id="puesto" name="puesto">';
                while ($puestoRow = mysqli_fetch_assoc($consultaPuestos)) {
                    $puestoNombre = $puestoRow['nombre'];
                    $id_puestos = $puestoRow['id_puestos'];
                    $selected = ($id_puestos == $empleado['puesto_id']) ? 'selected' : '';
                    echo '<option value="' . $id_puestos . '" ' . $selected . '>' . $puestoNombre . '</option>';
                }
                echo '</select>';
            } else {
                echo '<p>Error al cargar los puestos.</p>';
            }
            ?>
            <div id="puesto-error" class="error"></div><br>

            <label>Ingrese celular del empleado:</label><br>
            <input type="number" id="celular" name="celular" value="<?php echo htmlspecialchars($empleado['celular']); ?>">
            <div id="celular-error" class="error"></div><br>

            <label>Ingrese mail del empleado:</label><br>
            <input type="email" id="mail" name="mail" value="<?php echo htmlspecialchars($empleado['mail']); ?>">
            <div id="mail-error" class="error"></div><br>

            <!-- Eliminamos el campo de Sueldo bruto -->
            <input type="submit" value="Actualizar">
            <div id="general-error" class="error"></div>
        </form>
    </fieldset>

    <a class="back-link" href="modificarsueldobruto.php?DNI=<?php echo htmlspecialchars($empleado['DNI']); ?>">Modificar Sueldo Bruto</a>
    <a class="back-link" href="consultardatos.php">Volver al listado</a>
    <a class="back-link" href="menusesiones.php">Volver al menú</a>

    <script>
        function validarFormulario() {
            var valido = true;

            // Limpiar mensajes de error anteriores
            document.querySelectorAll('.error').forEach(function(el) {
                el.textContent = '';
            });

            var dni = document.getElementById("dni").value;
            var mail = document.getElementById("mail").value;
            var regexMail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

            if (dni.length < 8) {
                document.getElementById("dni-error").textContent = "El DNI debe tener al menos 8 dígitos.";
                valido = false;
            }

            if (!regexMail.test(mail)) {
                document.getElementById("mail-error").textContent = "Por favor, ingrese un correo electrónico válido.";
                valido = false;
            }

            var inputs = document.querySelectorAll('input:not([type="submit"]), select');
            inputs.forEach(function(input) {
                if (input.value === "") {
                    document.getElementById(input.id + "-error").textContent = "Este campo es obligatorio.";
                    valido = false;
                }
            });

            // Mostrar mensaje de error general si hay campos vacíos
            if (!valido) {
                document.getElementById("general-error").textContent = "Por favor, complete todos los campos obligatorios.";
            }

            return valido;
        }
    </script>
</body>
</html>
