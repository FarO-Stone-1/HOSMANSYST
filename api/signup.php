<?php
header("Content-Type: application/json");
include_once '../config/db.php';
include_once 'auth_check.php';

// Only Admins should be allowed to register new staff
checkAccess(['Admin']);

$data = json_decode(file_get_contents("php://input"));
if (!$data) $data = (object) $_POST;
if (!empty($data->username) && !empty($data->password) && !empty($data->role)) {
    // Hash the password before saving
    $hashed_password = password_hash($data->password, PASSWORD_DEFAULT);

    $query = "INSERT INTO users (username, password_hash, role) VALUES (:username, :password, :role)";
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute([
        ':username' => $data->username,
        ':password' => $hashed_password,
        ':role'     => $data->role
    ])) {
        echo json_encode(["message" => "User account created successfully"]);
    } else {
        echo json_encode(["message" => "Error: Could not create user"]);
    }
} else {
    echo json_encode(["message" => "Incomplete data. Please provide username, password, and role"]);
}
?>