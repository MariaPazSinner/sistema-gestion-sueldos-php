<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            background-image: url("fondonaranja.jpeg");
            background-size: cover; /* Esto hace que la imagen cubra toda la pantalla */
            background-repeat: no-repeat; /* Esto evita que la imagen se repita */
            background-position: center center; /* Esto centra la imagen */
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 20px;
        }
        h1 {
            text-align: center;
        }
        .concept {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .concept label {
            margin-right: 10px;
        }
        .actions {
            text-align: center;
            margin-top: 20px;
        }
        
        /*.actions a {
            margin: 0 10px;
            text-decoration: none;
            color: #1e90ff;
        }*/
        .explanation {
            display: none;
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }
        .btn-terminar {
            
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
        .btn-terminar:hover {
            background: #0056b3;
        } 
        
        .back-link {
            font-family: arial;
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
    <div class="container">
        <h1>Cargar Datos de Empleado</h1>
        <fieldset>
            <div>
                <?php
                    include 'conexion.php';

                    function calcularSueldo($dni, $periodo, $ausenciaremu, $ausenciaNOremu, $horasextraFER, $horasextra, $sueldoBruto, $mysqli) {
                        $diaxcantidad = number_format(0, 2, '.', '');
                        $consulta = "INSERT INTO temporal(concepto, DNI, cantidadEnValor, periodo) VALUES ('C1', '$dni', '$diaxcantidad', '$periodo')";
                        mysqli_query($mysqli, $consulta);

                        //ausencia no remunerada
                        $sueldodia = $sueldoBruto / 21;
                        $diaxcantidad = number_format(($sueldodia * $ausenciaNOremu) * -1, 2, '.', '');
                        $consulta = "INSERT INTO temporal(concepto, DNI, cantidadEnValor, periodo) VALUES ('C2', '$dni', '$diaxcantidad', '$periodo')";
                        mysqli_query($mysqli, $consulta);

                        //horas extra feriado
                        $sueldodia = $sueldoBruto / 160; 
                        $diaxcantidad = number_format((($sueldodia * 2) * $horasextraFER), 2, '.', '');
                        $consulta = "INSERT INTO temporal(concepto, DNI, cantidadEnValor, periodo) VALUES ('C3', '$dni', '$diaxcantidad', '$periodo')";
                        mysqli_query($mysqli, $consulta);

                        //horas extra
                        $sueldodia = $sueldoBruto / 160;
                        $diaxcantidad = number_format((($sueldodia * 1.5) * $horasextra), 2, '.', '');
                        $consulta = "INSERT INTO temporal(concepto, DNI, cantidadEnValor, periodo) VALUES ('C4', '$dni', '$diaxcantidad', '$periodo')";
                        mysqli_query($mysqli, $consulta);
                    }

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        if (isset($_POST['DNI']) && !empty($_POST['DNI'])) {
                            $dni = $_POST['DNI'];
                            $periodo = $_POST['periodo'];
                            $ausenciaremu = $_POST['cantidadAR'];
                            $ausenciaNOremu = $_POST['cantidadANR'];
                            $horasextraFER = $_POST['cantidadHEFER'];
                            $horasextra = $_POST['cantidadHE'];
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
                                ?>
                                <div class="actions">
                                    <a class="back-link" href="menusesiones.php">Volver al menú</a>
                                    <a class="back-link" href="altasueldoneto.php">Intentar de nuevo</a>
                                </div>
                                <?php
                            } else {
                            $query2 = "SELECT nombre FROM empleados WHERE DNI = '$dni'";
                            $result2 = mysqli_query($mysqli, $query2);
                            $row2 = mysqli_fetch_assoc($result2);
                            $nombre = $row2['nombre'];
                            $query3= "SELECT apellido FROM empleados WHERE DNI = '$dni'";
                            $result3 = mysqli_query($mysqli, $query3);
                            $row3 = mysqli_fetch_assoc($result3);
                            $apellido = $row3['apellido'];

                            echo "<h3>Periodo: $periodo | DNI: $dni | Nombre: $nombre $apellido</h3>";

                            $consulta_empleado = "SELECT * FROM empleados WHERE DNI=$dni";
                            $resultado_empleado = mysqli_query($mysqli, $consulta_empleado);

                            if (mysqli_num_rows($resultado_empleado) > 0) {
                                $sueldoQuery = "SELECT salarioBruto FROM empleados WHERE DNI = $dni";
                                $sueldoResult = mysqli_query($mysqli, $sueldoQuery);
                                $sueldoRow = mysqli_fetch_assoc($sueldoResult);
                                $sueldoBruto = $sueldoRow['salarioBruto'];

                                echo "<div class='concept'><label>Cantidad de Ausencias remuneradas: $ausenciaremu</label> <button class='info-btn' data-info='Las ausencias remuneradas son días que el empleado no asistió al trabajo pero aún recibe su sueldo.'>Info</button></div>";
                                echo "<div class='concept'><label>Cantidad de Ausencias No remuneradas: $ausenciaNOremu</label> <button class='info-btn' data-info='Las ausencias no remuneradas son días que el empleado no asistió al trabajo y no recibe su sueldo.'>Info</button></div>";
                                echo "<div class='concept'><label>Cantidad de Horas extra (feriado): $horasextraFER</label> <button class='info-btn' data-info='Las horas extra en feriado son horas trabajadas durante días feriados, generalmente pagadas con una tarifa más alta.'>Info</button></div>";
                                echo "<div class='concept'><label>Cantidad de Horas extra: $horasextra</label> <button class='info-btn' data-info='Las horas extra son horas trabajadas más allá de la jornada laboral regular.'>Info</button></div>";

                                calcularSueldo($dni, $periodo, $ausenciaremu, $ausenciaNOremu, $horasextraFER, $horasextra, $sueldoBruto, $mysqli);
                                ?>

                                <form action="terminarsueldoneto.php" method="POST">
                                    <input type="hidden" name="DNI" value="<?php echo $dni; ?>">
                                    <input type="hidden" name="periodo" value="<?php echo $periodo; ?>">
                                    <input type="hidden" name="sueldoBruto" value="<?php echo $sueldoBruto; ?>">
                                    <br>
                                    <input class="btn-terminar" type="submit" value="Terminar">
                                </form>
                                <div class="actions">
                                    <a class="back-link" href="menusesiones.php">Volver al menú</a>
                                </div>
                                <?php
                            } else {
                                echo "El DNI no existe en la base de datos.";
                                ?>
                                <div class="actions">
                                    <a class="back-link" href="menusesiones.php">Volver al menú</a>
                                    <a class="back-link" href="altasueldoneto.php">Intentar de nuevo</a>
                                </div>
                                <?php
                            }
                        
                        } 
                    }else {
                            echo "Por favor, ingrese un DNI.";
                            ?>
                            <div class="actions">
                                    <a class="back-link" href="menusesiones.php">Volver al menú</a>
                                    <a class="back-link" href="altasueldoneto.php">Intentar de nuevo</a>
                            </div>
                            <?php
                        }
                    
                    }
                ?>
            </div>
        </fieldset>
    </div>
    <script>
        document.querySelectorAll('.info-btn').forEach(button => {
            button.addEventListener('click', () => {
                const infoText = button.getAttribute('data-info');
                const explanation = document.createElement('div');
                explanation.className = 'explanation';
                explanation.innerHTML = `<p>${infoText}</p><button class='close-btn'>Cerrar</button>`;
                button.parentElement.appendChild(explanation);
                explanation.style.display = 'block';
                button.style.display = 'none';

                explanation.querySelector('.close-btn').addEventListener('click', () => {
                    explanation.remove();
                    button.style.display = 'inline-block';
                });
            });
        });
    </script>
</body>
</html>
