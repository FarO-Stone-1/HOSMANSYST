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

// Rule 1 — Cannot delete your own account
if ($data->user_id == $_SESSION['user_id']) {
    http_response_code(403);
    echo json_encode(["message" => "You cannot delete your own account."]);
    exit();
}

// Rule 2 — Cannot delete the last Admin account
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'Admin'");
$stmt->execute();
$adminCount = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];

$stmt2 = $conn->prepare("SELECT role FROM users WHERE user_id = :id");
$stmt2->execute([':id' => $data->user_id]);
$targetUser = $stmt2->fetch(PDO::FETCH_ASSOC);

if ($targetUser && $targetUser['role'] === 'Admin' && $adminCount <= 1) {
    http_response_code(403);
    echo json_encode(["message" => "Cannot delete the last Admin account. Create another Admin first."]);
    exit();
}

// Safe to delete
$stmt3 = $conn->prepare("DELETE FROM users WHERE user_id = :id");
$stmt3->execute([':id' => $data->user_id]);
echo json_encode(["message" => "Staff account deleted successfully"]);
?>