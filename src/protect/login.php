<?php
session_start();

require_once __DIR__ . '/../../php/vendor/autoload.php';
require_once __DIR__ . '/src/LoginResponse.php';
require_once __DIR__ . '/../../php/auth-config.php';

use Eirbware\Protect\LoginResponse;
use Jumbojett\OpenIDConnectClient;

$redirect = null;
if (isset($_SESSION['protect_redirect'])) {
    $redirect = $_SESSION['protect_redirect'];
}
if (isset($_GET['redirect'])) {
    $redirect = $_GET['redirect'];
    $_SESSION['protect_redirect'] = $redirect;
}


if (isset($_SESSION['cas_data']) && $_SESSION['cas_data'] != "") {
    $response = new LoginResponse(TRUE, "User already connected", $_SESSION);
    $response->send($redirect);
    $_SESSION['protect_redirect'] = null;
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
    $response->send($redirect);
    $_SESSION['protect_redirect'] = null;
    exit(0);
}

$userInfo = null;
try {
    $userInfo = $openIdClient->requestUserInfo();
} catch (Jumbojett\OpenIDConnectClientException $e) {
    $response = new LoginResponse(FALSE, $e->getMessage(), $_SESSION);
    $response->send($redirect);
    $_SESSION['protect_redirect'] = null;
    exit(0);
}

if (!isset($userInfo->uid)) {
    $response = new LoginResponse(FALSE, "Login failed for unknown reason", $_SESSION);
    $response->send($redirect);
    $_SESSION['protect_redirect'] = null;
    exit(0);
}

$_SESSION['cas_data'] = $userInfo;

$response = new LoginResponse(TRUE, "Login successful", $_SESSION);
$response->send($redirect);
$_SESSION['protect_redirect'] = null;

?>
