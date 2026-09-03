<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar empleados</title>
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
        fieldset {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        h3 {
            color: #333;
            margin-top: 0;
        }
        p {
            margin: 0;
            margin-bottom: 10px;
        }
        .result {
            margin-bottom: 20px;
        }
        .actions {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }
        .btn-calc {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-calc:hover {
            background-color: #0056b3;
        }
        .actions a {
            margin: 0 10px;
            text-decoration: none;
            color: #fff; /* Color de texto blanco */
            background-color: #1e90ff; /* Color de fondo azul */
            padding: 10px 20px; /* Aumento de relleno */
            border: none; /* Eliminación del borde */
            border-radius: 5px; /* Mayor radio de borde */
            cursor: pointer; /* Cambio de cursor al pasar sobre el botón */
            transition: background-color 0.3s ease; /* Efecto de clic */
        }
        .actions a:hover {
            background-color: #007bff; /* Cambio de color de fondo al hacer clic */
        }
        .info-btn {
            background-color: #f9f9f9; /* Color de fondo gris claro */
            color: #000; /* Color de texto negro */
            border: 1px solid #ddd; /* Color de borde gris claro */
            padding: 5px 10px; /* Ajuste de relleno */
            border-radius: 3px; /* Radio de borde */
            cursor: pointer; /* Cambio de cursor al pasar sobre el botón */
        }
        p {
            text-align: left;
        }
        .result {
            text-align: left;
        }
        .explanation {
           /* display: none;*/
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
            text-align: left;
        }
        .close-btn {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 5px;
        }
        .close-btn:hover {
            background-color: #0056b3;
        }
        .mostrar-listado {
            font-size: 15px;
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
        .mostrar-listado:hover {
            background: #0056b3;
            cursor: pointer;
      
        }
        h3 {
            text-align: left;
        }
        .resaltado {
            color: #8AB4E6;
            font-weight: bold;
            font-size: 19px;
        }
        .titulo{
            text-align: center;
        }
    </style>
</head>
<body>
    <fieldset>
        <?php
        include 'conexion.php';
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['DNI']) && !empty($_POST['DNI']) && isset($_POST['periodo']) && !empty($_POST['periodo'])) {
                $dni = $_POST['DNI'];
                $periodo = $_POST['periodo'];
                $sueldoBruto = $_POST['sueldoBruto'];

                $query2 = "SELECT nombre FROM empleados WHERE DNI = '$dni'";
                $result2 = mysqli_query($mysqli, $query2);
                $row2 = mysqli_fetch_assoc($result2);
                $nombre = $row2['nombre'];
                $query3= "SELECT apellido FROM empleados WHERE DNI = '$dni'";
                $result3 = mysqli_query($mysqli, $query3);
                $row3 = mysqli_fetch_assoc($result3);
                $apellido = $row3['apellido'];

                echo "<h3>Periodo: $periodo | DNI: $dni | Nombre: $nombre $apellido</h3>";
                ?>
                <p class="resaltado">Sueldo bruto del empleado: <?php echo $sueldoBruto; ?></p>
                <?php
             mysqli_begin_transaction($mysqli);
             try{
                $sqlTerminar = "SELECT concepto, cantidadEnValor FROM temporal WHERE DNI = '$dni' AND periodo = '$periodo'";
                $resuSqlTerminar = mysqli_query($mysqli, $sqlTerminar);
                echo "<h3>Conceptos:</h3>";
                if (mysqli_num_rows($resuSqlTerminar) > 0) {
                    while ($row = mysqli_fetch_assoc($resuSqlTerminar)) {
                        $concepto = $row['concepto'];
                        $cantidadEnValor = $row['cantidadEnValor'];
                        
                        switch ($concepto) {
                            case 'C1':
                                echo "<div class='result'>Ausencias remuneradas: $cantidadEnValor";
                                $infoText = "Ausencias remuneradas: No modifica el sueldo.";
                                break;
                            case 'C2':
                                echo "<div class='result'>Ausencias no remuneradas: $cantidadEnValor";
                                $infoText = "Ausencia no remunerada: sueldo bruto - [(sueldo bruto / días trabajados)] x ausencias.";
                                break;
                            case 'C3':
                                echo "<div class='result'>Horas extra feriado: $cantidadEnValor";
                                $infoText = "Horas extra feriado: sueldo bruto + [(sueldo bruto / 160) * 2] * horas extra.";
                                break;
                            case 'C4':
                                echo "<div class='result'>Horas extra: $cantidadEnValor";
                                $infoText = "Horas extra: sueldo bruto + [(sueldo bruto / 160) * 1.5] * horas extra.";
                                break;
                            default:
                                echo "<div class='result'>Concepto desconocido: $concepto";
                                $infoText = "Concepto desconocido: $concepto";
                                break;
                        }
                        ?>
                        <button class="info-btn" data-info="<?php echo $infoText; ?>">Info</button>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='result'>No se encontraron resultados.</div>";
                }

                $sqlTerminar = "SELECT cantidadEnValor FROM temporal WHERE DNI = '$dni' AND periodo = '$periodo'";
                $resuSqlTerminar = mysqli_query($mysqli, $sqlTerminar);

                $sumaConceptos = 0;

                while ($fila = mysqli_fetch_assoc($resuSqlTerminar)) {
                    $sumaConceptos += $fila['cantidadEnValor'];
                }

                // Cálculo de descuentos
                $aporteJubilatorio = $sueldoBruto * 0.11; // 11%
                $obraSocial = $sueldoBruto * 0.03; // 3%
                $inssjp = $sueldoBruto * 0.03; // 3%

                $totalDescuentos = $aporteJubilatorio + $obraSocial + $inssjp;
                $totalSueldo = $sueldoBruto + $sumaConceptos - $totalDescuentos;

                $consultaPeriodo = "SELECT periodo FROM registro WHERE DNI = '$dni'";
                $periodoTabla = mysqli_query($mysqli, $consultaPeriodo);

                $periodoRepetido = false;

                while ($fila = mysqli_fetch_assoc($periodoTabla)) {
                    if ($fila['periodo'] == $periodo) {
                        $periodoRepetido = true;
                        break;
                    }
                }

                if ($periodoRepetido) {
                    echo "<div class='result'>Error: El periodo ya existe para este DNI.</div>";
                } else {
                    $consulta = "INSERT INTO registro (DNI, periodo, salario) VALUES ('$dni', '$periodo', '$totalSueldo')";
                    $resuconsulta = mysqli_query($mysqli, $consulta);

                    if ($resuconsulta) {
                        ?>
                        <br>
                        <p>Valor de los conceptos: <?php echo $sumaConceptos; ?></p>
                        <?php
                        echo "<h3>Descuentos:</h3>";
                        echo "<p>Aportes Jubilatorios (11%): -$aporteJubilatorio</p>";
                        echo "<p>Obra Social (3%): -$obraSocial</p>";
                        echo "<p>INSSJP (3%): -$inssjp</p>";
                        echo "<p>Total Descuentos: -$totalDescuentos</p>";
                        ?>
                        <p class="resaltado">Sueldo neto del empleado: <?php echo $totalSueldo; ?></p>
                        <h3>El sueldo se registró correctamente</h3>
                        <form action="mostrarsueldoneto.php" method="POST">
                            <input type="hidden" name="DNI" value="<?php echo $dni; ?>">
                            <input type="hidden" name="periodo" value="<?php echo $periodo; ?>">
                            <input type="hidden" name="totalSueldo" value="<?php echo $totalSueldo; ?>">
                            <input class="mostrar-listado" type="submit" value="Mostrar listado">
                        </form>
                        <div class="actions">
                            <a href="menusesiones.php">Volver al menú</a>
                            <a href="altasueldoneto.php">Cargar otro empleado</a>
                        </div>
                        <?php
                        $borrarTemporal = "DELETE FROM temporal";
                        if (!mysqli_query($mysqli, $borrarTemporal)) {
                            throw new Exception("Error al borrar datos de la tabla temporal: " . mysqli_error($mysqli));
                        }
                    } else {
                        $error = mysqli_error($mysqli);
                        if (strpos($error, 'El salario debe ser positivo y no superior a 1.000.000.') !== false) {
                            echo 'Error: El salario debe ser positivo y no superior a 1.000.000.';
                        } else {
                            echo "Error al registrar el sueldo: " . $error;
                        }
                        ?>
                        <div class="actions">
                            <a href="menusesiones.php">Volver al menú</a>
                            <a href="altasueldoneto.php">Intentar de nuevo</a>
                        </div>
                        <?php
                    }
                }
    
                // Confirmar la transacción
                mysqli_commit($mysqli);
    
            } catch (Exception $e) {
                // Revertir la transacción
                mysqli_rollback($mysqli);
                echo "Se ha producido un error: " . $e->getMessage();
                ?>
                <div class="actions">
                    <a href="menusesiones.php">Volver al menú</a>
                    <a href="altasueldoneto.php">Intentar de nuevo</a>
                </div>
                <?php
            }
    
            mysqli_close($mysqli);
        } else {
            echo "No ingresaste un DNI o un periodo.";
            ?>
            <div class="actions">
                <a href="menusesiones.php">Volver al menú</a>
                <a href="altasueldoneto.php">Intentar de nuevo</a>
            </div>
            <?php
        }
    }
    ?>
    </fieldset>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.info-btn').forEach(button => {
            button.addEventListener('click', () => {
                const infoText = button.getAttribute('data-info');
                const explanation = document.createElement('div');
                explanation.className = 'explanation';
                explanation.innerHTML = `<p>${infoText}</p><button class='close-btn'>Cerrar</button>`;
                button.parentNode.insertBefore(explanation, button.nextSibling);
                button.style.display = 'none';

                explanation.querySelector('.close-btn').addEventListener('click', () => {
                    explanation.remove();
                    button.style.display = 'inline-block';
                });
            });
        });
    });
</script>
