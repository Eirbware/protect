<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

// TODO('remove this')
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
