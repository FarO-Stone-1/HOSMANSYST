<?php
include_once 'auth_check.php';
checkAccess(['Doctor', 'Nurse', 'Admin']); // Nurses can view but not add

$stmt = $conn->prepare("SELECT * FROM patients");
$stmt->execute();
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>