<?php
include_once 'auth_check.php';

// 1. Decode data first
$data = json_decode(file_get_contents("php://input"));

// 2. Security Check: Only allow if it's the user themselves OR an Admin
// Note: We use OR (||) here because if either condition is true, they can proceed
if ($_SESSION['user_id'] != $data->user_id && $_SESSION['role'] !== 'Admin') {
    http_response_code(403);
    echo json_encode(["message" => "Access Denied: You cannot change other users' passwords"]);
    exit();
}

// 3. Hash the new password
$newHash = password_hash($data->new_password, PASSWORD_DEFAULT);

// 4. Update
$stmt = $conn->prepare("UPDATE users SET password_hash = :hash WHERE user_id = :id");
$stmt->execute([':hash' => $newHash, ':id' => $data->user_id]);

echo json_encode(["message" => "Password updated successfully"]);
?>