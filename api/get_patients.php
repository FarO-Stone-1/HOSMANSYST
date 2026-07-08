<?php
header("Content-Type: application/json");
include_once '../config/db.php';
include_once 'auth_check.php';
checkAccess(['Doctor', 'Nurse', 'Admin', 'Receptionist']);
$stmt = $conn->prepare("SELECT * FROM patients");
$stmt->execute();
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>