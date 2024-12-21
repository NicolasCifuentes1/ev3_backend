<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
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

if ($tokenType !== 'Bearer' || $token !== 'ciisa') {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid token']);
    exit;
}