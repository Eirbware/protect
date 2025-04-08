<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../php/vendor/autoload.php';
require_once __DIR__ . '/src/LoginResponse.php';
require_once __DIR__ . '/../../php/auth-config.php';

use Eirbware\Protect\LoginResponse;
use Jumbojett\OpenIDConnectClient;

// TODO('remove this')
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_SESSION['cas_data']) && $_SESSION['cas_data'] != "") {
    $response = new LoginResponse(TRUE, "User already connected", $_SESSION);
    echo $response->toString();
    exit();
}

// Login using OpenId
$openIdClient = new OpenIDConnectClient(
    $OPENID_CONFIG['server_url'],
    $OPENID_CONFIG['client_id'],
    $OPENID_CONFIG['client_secret']
);

$openIdClient->setRedirectURL($OPENID_CONFIG['redirect_url']);

try {
    $openIdClient->authenticate();
} catch (Jumbojett\OpenIDConnectClientException $e) {
    $response = new LoginResponse(FALSE, $e->getMessage(), $_SESSION);
    echo $response->toString();
    exit(0);
}

$userInfo = null;
try {
    $userInfo = $openIdClient->requestUserInfo();
} catch (Jumbojett\OpenIDConnectClientException $e) {
    $response = new LoginResponse(FALSE, $e->getMessage(), $_SESSION);
    echo $response->toString();
    exit(0);
}

if (!isset($userInfo->uid)) {
    $response = new LoginResponse(FALSE, "Login failed for unknown reason", $_SESSION);
    echo $response->toString();
    exit(0);
}

$_SESSION['cas_data'] = $userInfo;

$response = new LoginResponse(TRUE, "Login successful", $_SESSION);
echo $response->toString();

?>
