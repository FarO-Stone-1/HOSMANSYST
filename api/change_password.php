<?php
header("Content-Type: application/json");
include_once '../config/db.php';
include_once 'auth_check.php';

$data = json_decode(file_get_contents("php://input"));

// Security check: only allow if changing own password OR admin changing anyone's
if ($_SESSION['user_id'] != $data->user_id && $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    echo json_encode(["message" => "Access Denied: You cannot change other users' passwords"]);
    exit();
}

if (empty($data->new_password)) {
    echo json_encode(["message" => "Error: New password is required"]);
    exit();
}

$newHash = password_hash($data->new_password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password_hash = :hash WHERE user_id = :id");
$stmt->execute([':hash' => $newHash, ':id' => $data->user_id]);
echo json_encode(["message" => "Password updated successfully"]);
?>