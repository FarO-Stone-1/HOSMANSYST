<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../config/db.php';
include_once 'auth_check.php';

checkAccess(['Admin', 'Doctor', 'Nurse', 'Receptionist']);

try {
    $query = "SELECT pr.*,
              CONCAT(p.first_name, ' ', p.last_name) as patient_name,
              u.username as doctor_name
              FROM prescriptions pr
              LEFT JOIN patients p ON pr.patient_id = p.patient_id
              LEFT JOIN users u ON pr.doctor_id = u.user_id
              ORDER BY pr.created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $prescriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($prescriptions);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
}
?>
