<?php
// Las credenciales se configuran en variables de entorno. Nunca se publican.
$host = getenv('TIDB_HOST') ?: (getenv('DB_HOST') ?: 'localhost');
$port = (int) (getenv('TIDB_PORT') ?: (getenv('DB_PORT') ?: 3306));
$user = getenv('TIDB_USER') ?: (getenv('DB_USER') ?: 'root');
$password = getenv('TIDB_PASSWORD') ?: (getenv('DB_PASSWORD') ?: '');
$bd = getenv('TIDB_DATABASE') ?: (getenv('DB_NAME') ?: 'basededatosproyecto');

mysqli_report(MYSQLI_REPORT_OFF);
$mysqli = mysqli_init();

$useSsl = filter_var(getenv('DB_SSL'), FILTER_VALIDATE_BOOLEAN) || getenv('TIDB_HOST');
if ($useSsl) {
    mysqli_ssl_set($mysqli, null, null, null, null, null);
}

$clientFlags = $useSsl ? MYSQLI_CLIENT_SSL : 0;
if (!@mysqli_real_connect($mysqli, $host, $user, $password, $bd, $port, null, $clientFlags)) {
    error_log('DB connection failed [' . mysqli_connect_errno() . ']: ' . mysqli_connect_error());
    http_response_code(500);
    exit('No se pudo conectar a la base de datos.');
}

mysqli_set_charset($mysqli, 'utf8mb4');
?>
