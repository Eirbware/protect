<?php
require_once __DIR__ . '/utils.php';
start_protect_session();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../php/auth-config.php';

$redirect=null;
if (isset($_GET['redirect']))
    $redirect = $_GET['redirect'];

// Check for required parameters
if (!isset($_GET['name'])) {
    echo '{"message": "Redirect id was not specified"}';
    http_response_code(400);
    exit();
}

// If not logged in, redirect user to login page
if (!isset($_SESSION['cas_data']) || $_SESSION['cas_data'] == "") {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']) . '&' . SID);
    exit();
}

// Check that data with this name exists and is a valid URL
$link = $PROTECTED_LINKS[$_GET['name']];
if (!isset($link)
    || !filter_var($link, FILTER_VALIDATE_URL)
    || !str_starts_with($link, "http")) {
    echo '{"message": "Redirect is invalid"}';
    http_response_code(404);
    exit();
}

if ($redirect == null)
    header('Location: ' . $link);
else
    header("Location: $redirect?protectRedirect=$link&" . SID);
?>
