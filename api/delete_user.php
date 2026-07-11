<?php
header("Content-Type: application/json");
include_once '../config/db.php';
include_once 'auth_check.php';
checkAccess(['Admin']);

$data = json_decode(file_get_contents("php://input"));

if (empty($data->user_id)) {
    echo json_encode(["message" => "Error: User ID is required"]);
    exit();
}

// Prevent admin from deleting themselves
if ($data->user_id == $_SESSION['user_id']) {
    http_response_code(403);
    echo json_encode(["message" => "You cannot delete your own account"]);
    exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE user_id = :id");
$stmt->execute([':id' => $data->user_id]);
echo json_encode(["message" => "Staff account deleted successfully"]);
?>