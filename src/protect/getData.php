<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../php/vendor/autoload.php';
require_once __DIR__ . '/src/DataResponse.php';
require_once __DIR__ . '/../../php/auth-config.php';

use Eirbware\Protect\DataResponse;

if (isset($_SESSION['cas_data']) && $_SESSION['cas_data'] != "") {
    $response = new DataResponse($_SESSION, $PROTECTED_DATA);
    echo $response->toString();
    exit();
} else {
    http_response_code(401);
    exit();
}
?>
