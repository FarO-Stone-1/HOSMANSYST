<?php
header("Content-Type: application/json");
include_once '../config/db.php';
include_once 'auth_check.php';
checkAccess(['Admin', 'Doctor', 'Nurse', 'Receptionist']);

$stats = [];

// Total patients
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM patients");
$stmt->execute();
$stats['total_patients'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Total staff (users excluding patients)
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM users");
$stmt->execute();
$stats['total_staff'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['count'];

// Staff by role
$stmt = $conn->prepare("SELECT role, COUNT(*) as count FROM users GROUP BY role");
$stmt->execute();
$stats['staff_by_role'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($stats);
?>