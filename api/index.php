<?php
// Una sola función PHP para respetar el límite de funciones del plan Hobby.
$sourceDirectory = realpath(__DIR__ . '/../src');
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestedFile = basename((string) $requestPath);

if ($requestedFile === '' || $requestedFile === '/' || $requestedFile === 'index.php') {
    $requestedFile = 'logininiciosesiones.php';
}

$blockedFiles = ['conexion.php', 'conexionEjemplo.php'];
$target = realpath($sourceDirectory . DIRECTORY_SEPARATOR . $requestedFile);

if (
    !$target ||
    dirname($target) !== $sourceDirectory ||
    pathinfo($target, PATHINFO_EXTENSION) !== 'php' ||
    in_array($requestedFile, $blockedFiles, true)
) {
    http_response_code(404);
    exit('Página no encontrada.');
}

chdir($sourceDirectory);
require $target;
