<?php
header("Content-Type: application/json");
include_once '../config/db.php';
include_once 'auth_check.php';
checkAccess([ 'Doctor', 'Nurse', 'Admin' ]); // Only allow Doctors, Nurses, and Admins to add patients

$data = json_decode(file_get_contents("php://input"));

// Strict Validation
if (empty($data->name)) {
    echo json_encode(["message" => "Error: Name is required"]);
} elseif (empty($data->dob)) {
    echo json_encode(["message" => "Error: Date of Birth is required"]);
} elseif (empty($data->gender)) {
    echo json_encode(["message" => "Error: Gender is required"]);
} elseif (empty($data->age)) {
    echo json_encode(["message" => "Error: Age is required"]);
} else {
    $query = "INSERT INTO patients (full_name, dob, gender, age) VALUES (:name, :dob, :gender, :age)";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':name'   => $data->name,
        ':dob'    => $data->dob,
        ':gender' => $data->gender,
        ':age'    => $data->age
    ]);
    echo json_encode(["message" => "Patient successfully registered"]);
}
?>