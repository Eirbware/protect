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

// Protected links accessible through `/protect/links.php?name=<link-name>` when authentified
// URLs listed here must be valid according to RFC 2396 and start with http (or https)
$PROTECTED_LINKS = [
    "video" => "https://www.youtube.com/watch?v=xvFZjo5PgG0",
    "url_pas_valide" => "example.org"
];

// Protected data to return on site authentication (can be a string, an array..)
$PROTECTED_DATA = [
    "message" =>"Je suis protégé !",
    "video" => "https://www.youtube.com/watch?v=xvFZjo5PgG0",
    "theme_du_wei" => "wei.eirb.fr"
];
?>
