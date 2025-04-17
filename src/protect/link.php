<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../php/auth-config.php';

/* Check for required parameters */
if (!isset($_GET['name'])) {
    http_response_code(400);
    exit();
}

/* If not logged in, redirect user to login page */
if (!isset($_SESSION['cas_data']) || $_SESSION['cas_data'] == "") {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

/* Check that data with this name exists and is a valid URL */
$link = $PROTECTED_LINKS[$_GET['name']];
if (!isset($link)
    || !filter_var($link, FILTER_VALIDATE_URL)
    || !str_starts_with($link, "http")) {
    http_response_code(404);
    exit();
}

header('Location: ' . $link);

?>
