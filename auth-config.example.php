<?php
// Whitelisted origins
$WHITELISTED_ORIGINS = [
    "http://localhost:8080",
    "https://<site>.eirb.fr",
];

// OpenId configuration
$OPENID_CONFIG = [
    "server_url" => "<server_url>",
    "client_id" => "<clientId>",
    "client_secret" => "<clientSecret>",
    "redirect_url" => "http://localhost:8080/protect/login.php"
];

// Protected data to return on site authentication (can be a string, an array..)
$PROTECTED_DATA = [
    "message" =>"Je suis protégé !",
    "video" => "https://www.youtube.com/watch?v=xvFZjo5PgG0",
    "theme_du_wei" => "https://wei.eirb.fr"
];
?>
