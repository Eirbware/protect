<?php
function is_url_whitelisted (string $redirect_url, array $whitelisted_origins) {
    // Required to check if url is valid, otherwise parse_url may not work
    if (!str_starts_with($redirect_url, "http") &&
        filter_var($redirect_url, FILTER_VALIDATE_URL))
        return false;

    $parsed_url = parse_url($redirect_url);
    // To be absolutely sure there is no error when accessing keys later
    if (!array_key_exists("scheme", $parsed_url) ||
        !array_key_exists("host", $parsed_url))
        return false;

    foreach ($whitelisted_origins as $origin) {
        $parsed_origin = parse_url($origin);

        // Make sure there is no error when acccessing keys later
        if (!array_key_exists("scheme", $parsed_origin) ||
            !array_key_exists("host", $parsed_origin) ||
            array_key_exists("port", $parsed_url) != array_key_exists('port', $parsed_origin))
            continue;

        // Final origin test
        if ($parsed_url["scheme"] == $parsed_origin["scheme"] &&
            $parsed_url["host"] == $parsed_origin["host"] &&
            (
                !array_key_exists("port", $parsed_url) ||
                $parsed_url["port"] == $parsed_origin["port"]
            )
        )
            return true;
    }

    return false;
}
?>
