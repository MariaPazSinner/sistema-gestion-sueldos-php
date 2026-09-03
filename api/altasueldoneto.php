<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de gestión de sueldos</title>
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
        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"], input[type="month"], input[type="number"], select {
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
            text-decoration: none;
        }
        a:last-child {
            background: #007bff;
        }
        a:last-child:hover {
            background: #5EA3F5;
        }
    </style>
</head>
<body>
    <h2>Alta de conceptos</h2>
    <form action="cargarsueldoneto.php" method="POST">
        <div>
            <label>Ingrese DNI</label>
            <select id="DNI" name="DNI" required>
                <?php
                include "conexion.php";

                $query = "SELECT DNI, nombre, apellido FROM empleados";
                $result = mysqli_query($mysqli, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $dni = $row['DNI'];
                        $nombre = $row['nombre'];
                        $apellido = $row['apellido'];
                        echo "<option value='$dni'>$dni - $nombre $apellido</option>";
                    }
                } else {
                    echo "<option value=''>No hay empleados disponibles</option>";
                }

                mysqli_close($mysqli);
                ?>
            </select>
        </div>
        <div>
            <label>Periodo:</label>
            <input type="month" name="periodo" id="periodo" maxlength="7" required>
        </div>
        <div>
            <h3>Conceptos:</h3>
            <label>Ausencia Remunerada</label>
            <input type="number" name="cantidadAR" id="cantidadAR" min="0" max="31" required>
            <label>Ausencia No Remunerada</label>
            <input type="number" name="cantidadANR" id="cantidadANR" min="0" max="31" required>
            <label>Horas extra Feriado</label>
            <input type="number" name="cantidadHEFER" id="cantidadHEFER" min="0" max="150" required>
            <label>Horas extra</label>
            <input type="number" name="cantidadHE" id="cantidadHE" min="0" max="360" required>
        </div>
        <div>
            <input type="submit" id="cargar" value="Cargar">
        </div>
    </form>
    <a href="menusesiones.php">Ir al menú</a>
</body>
</html>
