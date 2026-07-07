<?php
// api/add_patient.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/db.php';
include_once 'auth_check.php';

// 3️⃣ FIX: Added 'Receptionist' to the access check array
checkAccess(['Doctor', 'Nurse', 'Admin', 'Receptionist']);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->name) && !empty($data->dob) && !empty($data->gender)) {
    try {
        $query = "INSERT INTO patients (
                    name, dob, gender, age, phone, address, 
                    blood_group, allergies, emergency_contact_name, emergency_contact_phone, created_at
                  ) VALUES (
                    :name, :dob, :gender, :age, :phone, :address, 
                    :blood_group, :allergies, :emergency_contact_name, :emergency_contact_phone, NOW()
                  )";
        
        $stmt = $db->prepare($query);

        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":dob", $data->dob);
        $stmt->bindParam(":gender", $data->gender);
        $stmt->bindParam(":age", $data->age);
        $stmt->bindParam(":phone", $data->phone);
        $stmt->bindParam(":address", $data->address);
        $stmt->bindParam(":blood_group", $data->blood_group);
        $stmt->bindParam(":allergies", $data->allergies);
        $stmt->bindParam(":emergency_contact_name", $data->emergency_contact_name);
        $stmt->bindParam(":emergency_contact_phone", $data->emergency_contact_phone);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["status" => "success", "message" => "Patient registered successfully."]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Unable to save patient."]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database failure: " . $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Incomplete data structure parameters missing."]);
}