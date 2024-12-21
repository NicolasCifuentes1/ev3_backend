<?php
require_once 'config/auth.php';
require_once 'basicInfo.php';
require_once 'services.php';
require_once 'about.php';
require_once 'contacto-rrss/cotactoController.php';
require_once 'quienes-somos/quienesController.php'; // Importar funciones de quienes-somos

// Obtener la ruta solicitada
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$pathSegments = explode('/', trim($path, '/'));

// Verificar la ruta y aplicar autenticación si es necesario
if (isset($pathSegments[3])) {
    $endpoint = $pathSegments[3];
    $method = $_SERVER['REQUEST_METHOD'];

    // Verificar autenticación
    if (!isAuthenticated($endpoint)) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    switch ($endpoint) {
        case 'basic-info':
            $data = getBasicInfo();
            break;
        case 'services':
            $data = getServices();
            break;
        case 'about-us':
            switch ($method) {
                case 'GET':
                    $data = getAboutInfo();
                    break;
                case 'PUT':
                    $data = updateAboutInfo();
                    break;
                default:
                    http_response_code(405);
                    $data = ['error' => 'Method Not Allowed'];
                    break;
            }
            break;
        case 'contacto-rrss':
            switch ($method) {
                case 'GET':
                    $data = getContactInfo();
                    break;
                case 'POST':
                    $data = addContactInfo();
                    break;
                case 'PUT':
                    $data = updateContactInfo();
                    break;
                case 'DELETE':
                    $data = disableContactInfo();
                    break;
                case 'PATCH':
                    $data = enableContactInfo();
                    break;
                default:
                    http_response_code(405);
                    $data = ['error' => 'Method Not Allowed'];
                    break;
            }
            break;
        default:
            http_response_code(404);
            $data = ['error' => 'Not Found'];
            break;
    }
} else {
    $data = [
        'basic-info' => getBasicInfo(),
        'services' => getServices(),
        'about-us' => getAboutInfo()
    ];
}

// Return del contenido
echo json_encode($data);
?>