<?php
// Las credenciales se configuran en variables de entorno. Nunca se publican.
$host = getenv('DB_HOST') ?: 'localhost';
$port = (int) (getenv('DB_PORT') ?: 3306);
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';
$bd = getenv('DB_NAME') ?: 'basededatosproyecto';

mysqli_report(MYSQLI_REPORT_OFF);
$mysqli = mysqli_init();

if (getenv('DB_SSL') === 'true') {
    mysqli_ssl_set($mysqli, null, null, null, null, null);
}

if (!@mysqli_real_connect($mysqli, $host, $user, $password, $bd, $port)) {
    http_response_code(500);
    exit('No se pudo conectar a la base de datos.');
}

mysqli_set_charset($mysqli, 'utf8mb4');
?>
