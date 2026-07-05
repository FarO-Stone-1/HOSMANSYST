<?php
session_start(); // Essential: start the session here!
header("Content-Type: application/json");
include_once '../config/db.php';

$data = json_decode(file_get_contents("php://input"));

$query = "SELECT * FROM users WHERE username = :username";
$stmt = $conn->prepare($query);
$stmt->execute([':username' => $data->username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verify password
if ($user && password_verify($data->password, $user['password_hash'])) {
    // Session is created here
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];
    
    echo json_encode(["message" => "Login successful", "role" => $user['role']]);
} else {
    http_response_code(401);
    echo json_encode(["message" => "Invalid username or password"]);
}
?>