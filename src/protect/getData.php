<?php
require_once __DIR__ . '/utils.php';
start_protect_session();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../php/vendor/autoload.php';
require_once __DIR__ . '/response/DataResponse.php';
require_once __DIR__ . '/../../php/auth-config.php';

use Eirbware\Protect\DataResponse;

// Allow CORS if the origin is whitelisted
$req_origin = (is_incoming_https() ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]";
if (is_url_whitelisted($req_origin, $WHITELISTED_ORIGINS)) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET');
}
else {
    echo "{\"message\":\"L'origine n'est pas valide\"}";
    http_response_code(403);
    exit();
}

if (isset($_SESSION['cas_data']) && $_SESSION['cas_data'] != "") {
    $response = new DataResponse($_SESSION, $PROTECTED_DATA);
    echo $response->toString();
    exit();
} else {
    echo "{\"message\":\"Veuillez vous connecter dans un premier temps\"}";
    http_response_code(401);
    exit();
}
?>
