<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/db.php';
include_once 'auth_check.php';

checkAccess(['Admin', 'Doctor', 'Nurse', 'Receptionist']);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->patient_id) && !empty($data->doctor_id) && !empty($data->appointment_datetime) && !empty($data->appointment_type)) {
    try {
        $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_datetime, appointment_type, notes, created_at) 
                  VALUES (:patient_id, :doctor_id, :appointment_datetime, :appointment_type, :notes, NOW())";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(":patient_id", $data->patient_id);
        $stmt->bindParam(":doctor_id", $data->doctor_id);
        $stmt->bindParam(":appointment_datetime", $data->appointment_datetime);
        $stmt->bindParam(":appointment_type", $data->appointment_type);
        $stmt->bindParam(":notes", $data->notes);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Appointment created successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Unable to create appointment."]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Database error: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data structure."]);
}