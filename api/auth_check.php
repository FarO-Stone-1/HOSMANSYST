<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAccess($allowedRoles) {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(["message" => "Access Denied. Please log in."]);
        exit();
    }
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        http_response_code(403);
        echo json_encode(["message" => "Access Forbidden: You do not have permission."]);
        exit();
    }
}
?>