<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/db.php';
include_once 'auth_check.php';

checkAccess(['Admin', 'Nurse', 'Doctor']);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->patient_id) && !empty($data->temperature) && !empty($data->blood_pressure)) {
    try {
        $query = "INSERT INTO vitals (patient_id, temperature, blood_pressure, pulse, oxygen_saturation, weight, height, notes, created_at) 
                  VALUES (:patient_id, :temperature, :blood_pressure, :pulse, :oxygen_saturation, :weight, :height, :notes, NOW())";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(":patient_id", $data->patient_id);
        $stmt->bindParam(":temperature", $data->temperature);
        $stmt->bindParam(":blood_pressure", $data->blood_pressure);
        $stmt->bindParam(":pulse", $data->pulse);
        $stmt->bindParam(":oxygen_saturation", $data->oxygen_saturation);
        $stmt->bindParam(":weight", $data->weight);
        $stmt->bindParam(":height", $data->height);
        $stmt->bindParam(":notes", $data->notes);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Vitals logged successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Unable to log vitals."]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Database error: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Required vital metrics missing."]);
}