<?php
// Whitelisted origins
$WHITELISTED_ORIGINS = [
    "http://localhost:PORT",
    "https://CLIENT_ID.eirb.fr",
];

// OpenId configuration
$OPENID_CONFIG = [
    "server_url" => "https://connect.eirb.fr/realms/eirb",
    "client_id" => "CLIENT_ID",
    "client_secret" => "CLIENT_SECRET",
    "redirect_url" => "REDIRECT_URL"
];

// Protected data to return on site authentication (can be a string, an array..)
$PROTECTED_DATA = [
    "message" =>"Je suis protégé !",
    "video" => "https://www.youtube.com/watch?v=dQw4w9WgXcQ",
    "theme_du_wei" => "wei.eirb.fr"
];
?>
