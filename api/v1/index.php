<?php
require_once 'config/auth.php';
require_once 'basicInfo.php';
require_once 'services.php';
require_once 'about.php';
require_once 'contacto-rrss/cotactoController';

// Obtener la ruta solicitada
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);
$pathSegments = explode('/', trim($path, '/'));

// Verificar la ruta y aplicar autenticación si es necesario
if (isset($pathSegments[3])) {
    switch ($pathSegments[3]) {
        case 'basic-info':
            $data = getBasicInfo();
            break;
        case 'services':
            // Aplicar autenticación para /services
            if (!isAuthenticated()) {
                http_response_code(401);
                $data = ['error' => 'Unauthorized'];
                break;
            }
            $data = getServices();
            break;
        case 'about-us':
            $data = getAbout();
            break;
        case 'contacto-rrss':
            $method = $_SERVER['REQUEST_METHOD'];
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
        'about-us' => getAbout()
    ];
}

// Return del contenido
echo json_encode($data);
?>