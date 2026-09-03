<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $id_puestos = $_POST['puesto'];
    $celular = $_POST['celular'];
    $mail = $_POST['mail'];
    $sueldoBruto = $_POST['sueldoBruto'];

    if (!empty($dni) && strlen($dni) == 8 && !empty($nombre) && !empty($apellido) && !empty($celular) && !empty($mail) && !empty($sueldoBruto)) {
        $verificar = "SELECT * FROM empleados WHERE DNI = '$dni'";
        $consulverificar = mysqli_query($mysqli, $verificar);

        if (mysqli_num_rows($consulverificar) > 0) {
            // Enviar respuesta de error por DNI duplicado
            echo "error_dni";
            exit;
        } else {
            $sqlInsertar = "INSERT INTO empleados(DNI, nombre, apellido, celular, mail, id_puestos, salarioBruto, id_usuario) VALUES ('$dni','$nombre','$apellido','$celular','$mail','$id_puestos','$sueldoBruto',NULL)";
            $respuestabd = mysqli_query($mysqli, $sqlInsertar);

            if ($respuestabd) {
                echo "success";
            } else {
                // Obtener el mensaje de error del trigger
                $error = mysqli_error($mysqli);
                if (strpos($error, 'El salario debe ser positivo y no superior a 1.000.000.') !== false) {
                    echo "El salario debe ser positivo y no superior a 1.000.000.";
                } else {
                    echo "error";
                }
            }
        }
    } else {
        // Enviar respuesta de error por campos incompletos
        echo "error_campos";
    }
}

mysqli_close($mysqli);
?>
