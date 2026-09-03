<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dniEliminar']) && !empty($_POST['dniEliminar'])) {
    $dni = $_POST['dniEliminar'];

    // Escapar el DNI para evitar SQL injection
    $dni_escapado = mysqli_real_escape_string($mysqli, $dni);

    // Consulta para verificar si el empleado existe
    $consulta_empleado = "SELECT * FROM empleados WHERE DNI = '$dni_escapado'";
    $resultado_empleado = mysqli_query($mysqli, $consulta_empleado);

    if (mysqli_num_rows($resultado_empleado) == 0) {
        // Redirigir a consultardatos.php con mensaje de error
        header("Location: consultardatos.php?mensaje=El empleado con DNI $dni no existe en la base de datos.");
        exit();
    } else {
        // Borrar el empleado
        $borrar_empleado = "DELETE FROM empleados WHERE DNI = '$dni_escapado'";
        if (mysqli_query($mysqli, $borrar_empleado)) {
            // Redirigir a consultardatos.php con mensaje de éxito
            header("Location: consultardatos.php?mensaje=Empleado con DNI $dni ha sido eliminado exitosamente.");
            exit();
        } else {
            // Redirigir a consultardatos.php con mensaje de error
            $error = mysqli_error($mysqli);
            header("Location: consultardatos.php?mensaje=Error al eliminar el empleado: $error");
            exit();
        }
    }
} else {
    // Redirigir a consultardatos.php con mensaje de error si no se ha proporcionado un DNI
    header("Location: consultardatos.php?mensaje=Por favor, ingrese un DNI válido.");
    exit();
}
?>
