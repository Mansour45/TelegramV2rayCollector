<?php
function ParseShadowsocks($config_str)
{
    // Parse the config string as a URL
    $url = parse_url($config_str);

    // Extract the encryption method and password from the user info
    list($encryption_method, $password) = explode(
        ":",
        base64_decode($url["user"])
    );

    // Extract the server address and port from the host and path
    $server_address = $url["host"];
    $server_port = $url["port"];

    // Extract the name from the fragment (if present)
    $name = isset($url["fragment"]) ? urldecode($url["fragment"]) : null;

    // Create an array to hold the server configuration
    $server = [
        "encryption_method" => $encryption_method,
        "password" => $password,
        "server_address" => $server_address,
        "server_port" => $server_port,
        "name" => $name,
    ];

    // Return the server configuration as a JSON string
    return $server;
}

function BuildShadowsocks($server)
{
    // Encode the encryption method and password as a Base64-encoded string
    $user = base64_encode(
        $server["encryption_method"] . ":" . $server["password"]
    );

    // Construct the URL from the server address, port, and user info
    $url = "ss://$user@{$server["server_address"]}:{$server["server_port"]}";

    // If the name is present, add it as a fragment to the URL
    if (!empty($server["name"])) {
        $url .= "#" . urlencode($server["name"]);
    }

    // Return the URL as a string
    return $url;
}

?>
