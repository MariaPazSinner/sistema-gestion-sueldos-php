<?php include 'conexion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Empleado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            background-image: url("fondonaranja.jpeg");
            background-size: cover; /* Esto hace que la imagen cubra toda la pantalla */
            background-repeat: no-repeat; /* Esto evita que la imagen se repita */
            background-position: center center; /* Esto centra la imagen */
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
            padding:10px;

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
        #empleados-table {
            width: 50%;
            margin: 50px auto; /* Centra horizontalmente */
            margin-left: 25%; /* Mueve la tabla hacia la derecha */
            background-color: #fff;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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

        a {
            text-decoration: none;
            color: #1e90ff;
            margin-top: 10px;
            display: inline-block;
            padding: 10px;
            text-align: center;
        }
        a:hover {
            text-decoration: none;
        }
        #filtrar-lb {
            text-align: center;
            
        }
        .link {
        text-decoration: none;
        color: #1e90ff;
        padding: 10px;
        display: inline-block; /* Para que respete el text-align */
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

    <fieldset>
    <h1>Alta de empleados:</h1>
        <form id="empleado-form" action="empleadocargado.php" method="POST" onsubmit="return validarFormulario()">
            <label>Ingrese DNI:</label><br>
            <input type="number" id="dni" name="dni">
            <div id="dni-error" class="error"></div><br>
            <label>Ingrese nombre del empleado:</label><br>
            <input type="text" id="nombre" name="nombre">
            <div id="nombre-error" class="error"></div><br>
            <label>Ingrese apellido del empleado:</label><br>
            <input type="text" id="apellido" name="apellido">
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
                    echo '<option value="' . $id_puestos . '">' . $puestoNombre . '</option>';
                }
                echo '</select>';
            } else {
                echo '<p>Error al cargar los puestos.</p>';
            }
            ?>
            <div id="puesto-error" class="error"></div><br>
            <label>Ingrese celular del empleado:</label><br>
            <input type="number" id="celular" name="celular">
            <div id="celular-error" class="error"></div><br>
            <label>Ingrese mail del empleado:</label><br>
            <input type="email" id="mail" name="mail">
            <div id="mail-error" class="error"></div><br>
            <label>Ingrese Sueldo bruto del empleado:</label><br>
            <input type="number" id="sueldoBruto" name="sueldoBruto"><br>
            <input type="submit" value="Guardar">
            <div id="general-error" class="error"></div>
        </form>
    </fieldset>

    <label id="filtrar-lb">Filtrar por puesto:</label>
    <select id="puesto-filter">
        <option value="">Todos los puestos</option>
        <?php
        $consultaPuestos = mysqli_query($mysqli, "SELECT * FROM puestos");
        if ($consultaPuestos) {
            while ($puestoRow = mysqli_fetch_assoc($consultaPuestos)) {
                $puestoNombre = $puestoRow['nombre'];
                $id_puestos = $puestoRow['id_puestos'];
                echo '<option value="' . $id_puestos . '">' . $puestoNombre . '</option>';
            }
        } else {
            echo '<option>Error al cargar los puestos</option>';
        }
        ?>
    </select>

    <div id="empleados-table"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            loadEmpleadosTable();

            $('#puesto-filter').change(function() {
                loadEmpleadosTable();
            });

            $('#empleado-form').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: 'empleadocargado.php',
                    data: formData,
                    success: function(response) {
                        if (response === "success") {
                            alert('Empleado cargado correctamente');
                            loadEmpleadosTable();
                        } else if (response === "error_dni") {
                            alert('El DNI ingresado ya existe en la base de datos');
                        } else if (response === "error_campos") {
                            alert('Por favor, complete todos los campos obligatorios');
                        } else if (response.includes('El salario debe ser positivo y no superior a 1.000.000.')) {
                            alert(response);
                        } else {
                            alert('Ocurrió un error al cargar el empleado 1');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Ocurrió un error al cargar el empleado 2');
                    }
                });
            });
        });

        function loadEmpleadosTable() {
            var puestoFilter = $('#puesto-filter').val();
            $.ajax({
                type: 'POST',
                url: 'get_empleados.php',
                data: { puesto: puestoFilter },
                success: function(response) {
                    $('#empleados-table').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert('Ocurrió un error al cargar la tabla de empleados');
                }
            });
        }

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

    <br>
    <a class="back-link" href="menusesiones.php">Ir al menú</a>
</body>
</html>
