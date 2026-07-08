<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();
session_destroy();

echo json_encode(["message" => "Logged out successfully"]);
?>