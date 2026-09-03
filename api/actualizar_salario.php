<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['dni']) && !empty($_POST['dni']) && isset($_POST['salariobrutonew']) && !empty($_POST['salariobrutonew'])) {
        $dni = $_POST['dni'];
        $nuevoSalario = $_POST['salariobrutonew'];

        // Actualizar salario bruto en la base de datos
        $updateQuery = "UPDATE empleados SET salarioBruto = $nuevoSalario WHERE DNI = $dni";
        $resultado = mysqli_query($mysqli, $updateQuery);

        if ($resultado) {
            echo 'success'; // Envía 'success' si la actualización fue exitosa
        } else {
            echo 'error'; // Envía 'error' si hubo un error al actualizar
        }
    }
}
?>
