<?php
require_once __DIR__ . '/utils.php';
start_protect_session();

require_once __DIR__ . '/../../php/vendor/autoload.php';
require_once __DIR__ . '/response/LoginResponse.php';
require_once __DIR__ . '/../../php/auth-config.php';

use Eirbware\Protect\LoginResponse;
use Jumbojett\OpenIDConnectClient;

// Process redirect
$redirect = null;
if (isset($_SESSION['protect_redirect'])) {
    $redirect = $_SESSION['protect_redirect'];
}
else if (isset($_GET['redirect'])) {
    $redirect = $_GET['redirect'];
    $_SESSION['protect_redirect'] = $redirect;
}
if ($redirect != null)  // Add session id to the redirect
    $redirect .= (parse_url($redirect, PHP_URL_QUERY) ? '&' : '?') . SID;

// Use cache
if (isset($_SESSION['cas_data']) && $_SESSION['cas_data'] != "") {
    $response = new LoginResponse(TRUE, "User already connected", $_SESSION);
    $response->send($redirect);
    $_SESSION['protect_redirect'] = null;
    exit();
}

// Setup OpenId
$openIdClient = new OpenIDConnectClient(
    $OPENID_CONFIG['server_url'],
    $OPENID_CONFIG['client_id'],
    $OPENID_CONFIG['client_secret']
);

// Make sure a whitelisted origin is being processed
$redirect_url = (is_incoming_https() ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]?" . SID;
if (!is_url_whitelisted($redirect_url, $WHITELISTED_ORIGINS)) {
    $response = new LoginResponse(FALSE, "Request origin is not whitelisted", $_SESSION);
    $response->send($redirect);
    $_SESSION['protect_redirect'] = null;
    exit(0);
}
$openIdClient->setRedirectURL($redirect_url);

// Redirect to openid login page
try {
    $openIdClient->authenticate();
} catch (Jumbojett\OpenIDConnectClientException $e) {
    $response = new LoginResponse(FALSE, $e->getMessage(), $_SESSION);
    $response->send($redirect);
    $_SESSION['protect_redirect'] = null;
    exit(0);
}

// Need to fetch user infos right after login
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
