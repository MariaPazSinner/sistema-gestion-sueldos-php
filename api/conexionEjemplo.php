<?php
// Ejemplo local. La aplicación real usa api/conexion.php y variables de entorno.
$host = getenv('DB_HOST') ?: 'localhost';
$port = (int) (getenv('DB_PORT') ?: 3306);
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$bd = getenv('DB_NAME') ?: 'basededatosproyecto';
?>
