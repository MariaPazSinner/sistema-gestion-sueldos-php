<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dni'])) {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $puesto = $_POST['puesto'];
    $celular = $_POST['celular'];
    $mail = $_POST['mail'];
    $sueldoBruto = $_POST['sueldoBruto'];

    // Escapar los datos para evitar SQL injection
    $dni_escapado = mysqli_real_escape_string($mysqli, $dni);
    $nombre_escapado = mysqli_real_escape_string($mysqli, $nombre);
    $apellido_escapado = mysqli_real_escape_string($mysqli, $apellido);
    $puesto_escapado = mysqli_real_escape_string($mysqli, $puesto);
    $celular_escapado = mysqli_real_escape_string($mysqli, $celular);
    $mail_escapado = mysqli_real_escape_string($mysqli, $mail);
    
    // Consulta para obtener el sueldo bruto actual
    $consulta_actual = "SELECT salarioBruto FROM empleados WHERE DNI = '$dni_escapado'";
    $resultado_actual = mysqli_query($mysqli, $consulta_actual);

    if ($resultado_actual) {
        $empleado_actual = mysqli_fetch_assoc($resultado_actual);
        $sueldoBruto_actual = $empleado_actual['salarioBruto'];
        
        // Verificar si se ha enviado un nuevo sueldo bruto
        if (!empty($sueldoBruto)) {
            $sueldoBruto_escapado = mysqli_real_escape_string($mysqli, $sueldoBruto);
        } else {
            $sueldoBruto_escapado = $sueldoBruto_actual;
        }

        // Consulta para actualizar los datos del empleado
        $consulta_update = "UPDATE empleados SET 
            nombre = '$nombre_escapado',
            apellido = '$apellido_escapado',
            id_puestos = '$puesto_escapado',
            celular = '$celular_escapado',
            mail = '$mail_escapado',
            salarioBruto = '$sueldoBruto_escapado'
            WHERE DNI = '$dni_escapado'";

        if (mysqli_query($mysqli, $consulta_update)) {
            // Redirigir con mensaje de éxito
            header("Location: consultardatos.php?mensaje=Empleado actualizado correctamente.");
        } else {
            // Redirigir con mensaje de error
            $error = mysqli_error($mysqli);
            header("Location: consultardatos.php?mensaje=Error al actualizar el empleado: $error");
        }
    } else {
        echo "Error al obtener el sueldo bruto actual.";
    }
} else {
    echo "Error: No se ha proporcionado un DNI.";
}
?>
