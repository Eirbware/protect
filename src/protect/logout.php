<?php
require_once __DIR__ . '/utils.php';
start_protect_session();

// TODO('logout openID?')

session_unset();
session_destroy();

if (isset($_GET['redirect'])) {
    header('Location: ' . $_GET['redirect']);
} else {
    header('Location: /');
}
?>
