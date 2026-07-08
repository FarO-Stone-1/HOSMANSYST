<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../config/db.php';
include_once 'auth_check.php';

checkAccess(['Admin', 'Receptionist']);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->patient_id) && isset($data->amount) && !empty($data->due_date)) {
    try {
        $query = "INSERT INTO invoices (patient_id, amount, due_date, notes, status, created_at) 
                  VALUES (:patient_id, :amount, :due_date, :notes, 'Unpaid', NOW())";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(":patient_id", $data->patient_id);
        $stmt->bindParam(":amount", $data->amount);
        $stmt->bindParam(":due_date", $data->due_date);
        $stmt->bindParam(":notes", $data->notes);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Invoice generated successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Unable to create invoice."]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["message" => "Database error: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete details."]);
}