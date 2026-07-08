<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../config/db.php';
include_once 'auth_check.php';

checkAccess(['Admin', 'Doctor', 'Nurse', 'Receptionist']);

try {
    $query = "SELECT * FROM appointments ORDER BY appointment_datetime ASC";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode($appointments);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Database error: " . $e->getMessage()]);
}