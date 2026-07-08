<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/db.php';
include_once 'auth_check.php';

checkAccess(['Admin', 'Doctor']);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->patient_id) && !empty($data->doctor_id) && !empty($data->medication) && !empty($data->dosage) && !empty($data->duration_days)) {
    try {
        $query = "INSERT INTO prescriptions (patient_id, doctor_id, medication, dosage, duration_days, notes, created_at) 
                  VALUES (:patient_id, :doctor_id, :medication, :dosage, :duration_days, :notes, NOW())";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(":patient_id", $data->patient_id);
        $stmt->bindParam(":doctor_id", $data->doctor_id);
        $stmt->bindParam(":medication", $data->medication);
        $stmt->bindParam(":dosage", $data->dosage);
        $stmt->bindParam(":duration_days", $data->duration_days);
        $stmt->bindParam(":notes", $data->notes);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Prescription recorded successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Unable to save prescription."]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Database error: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete parameters."]);
}