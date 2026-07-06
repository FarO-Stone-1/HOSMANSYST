<?php
// api/me.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "logged_in" => true,
        "user" => [
            "id" => $_SESSION['user_id'],
            "username" => $_SESSION['username'],
            "role" => $_SESSION['role']
        ]
    ]);
} else {
    http_response_code(401);
    echo json_encode([
        "status" => "error",
        "logged_in" => false,
        "message" => "No active session authentication layer detected."
    ]);
}