<?php
header("Content-Type: application/json");
include_once '../config/db.php';
include_once 'auth_check.php';
checkAccess(['Admin', 'Receptionist', 'Doctor']);
$stmt = $conn->prepare("SELECT user_id, username, role FROM users");
$stmt->execute();
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>