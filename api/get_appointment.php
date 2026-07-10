<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../config/db.php';
include_once 'auth_check.php';

checkAccess(['Admin', 'Doctor', 'Nurse', 'Receptionist']);

try {
    $query = "SELECT a.*, 
              CONCAT(p.first_name, ' ', p.last_name) as patient_name,
              u.username as doctor_name
              FROM appointments a
              LEFT JOIN patients p ON a.patient_id = p.patient_id
              LEFT JOIN users u ON a.doctor_id = u.user_id
              ORDER BY a.appointment_datetime ASC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($appointments);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
}
?>
