<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); 
    echo json_encode(['error' => 'Only GET requests are allowed']);
    exit;
}
$headers = apache_request_headers();
if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Authorization header not found']);
    exit;
}

$authHeader = $headers['Authorization'];
list($tokenType, $token) = explode(' ', $authHeader);

if ($tokenType !== 'Bearer') {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token type']);
    exit;
}

// Definan los tokens para cada endpoint
$tokens = [
    'about-us' => 'aboutChomba',
    'contacto-rrss' => 'contactoChomba'
];

// Obtener la ruta solicitada
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$pathSegments = explode('/', trim($path, '/'));

$endpoint = isset($pathSegments[3]) ? $pathSegments[3] : null;

if ($endpoint && isset($tokens[$endpoint])) {
    if ($token !== $tokens[$endpoint]) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid token for ' . $endpoint]);
        exit;
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Endpoint not found']);
    exit;
}
?>