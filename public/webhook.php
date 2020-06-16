<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Write code to listen webhook request
} else {
    $VERIFY_TOKEN = '1234';
    $mode = $_REQUEST['hub_mode'];
    $token = $_REQUEST['hub_verify_token'];
    $challenge = $_REQUEST['hub_challenge'];

    if ($mode && $token) {
        // Checks the mode and token sent is correct
        if ($mode === 'subscribe' && $token === $VERIFY_TOKEN) {
            // Responds with the challenge token from the request
            echo $challenge;
            http_response_code(200);
        } else {
            // Responds with '403 Forbidden' if verify tokens do not match
            http_response_code(403);
        }
    } else {
        http_response_code(403);
    }
}