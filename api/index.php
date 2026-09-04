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

// La capa visual compartida se inyecta únicamente en respuestas HTML.
// Los endpoints AJAX conservan su salida original.
ob_start();
require $target;
$response = ob_get_clean();

if (stripos($response, '</head>') !== false) {
    $pageName = pathinfo($requestedFile, PATHINFO_FILENAME);
    $sharedHead = '<link rel="preconnect" href="https://fonts.googleapis.com">'
        . '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>'
        . '<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&amp;family=Manrope:wght@600;700;800&amp;display=swap" rel="stylesheet">'
        . '<link rel="stylesheet" href="/app.css?v=6">';
    $response = str_ireplace('</head>', $sharedHead . '</head>', $response);
    $response = preg_replace('/<body([^>]*)>/i', '<body$1 data-page="' . htmlspecialchars($pageName, ENT_QUOTES, 'UTF-8') . '">', $response, 1);
    $response = str_ireplace('</body>', '<script src="/app.js?v=6" defer></script></body>', $response);
}

echo $response;
