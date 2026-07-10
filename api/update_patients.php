<?php
header("Content-Type: application/json");
include_once '../config/db.php';
include_once 'auth_check.php';
checkAccess(['Doctor', 'Admin', 'Receptionist']);

$data = json_decode(file_get_contents("php://input"));

if (empty($data->patient_id)) {
    echo json_encode(["message" => "Error: Patient ID is required"]);
    exit();
}

$query = "UPDATE patients SET 
    first_name              = :first_name,
    last_name               = :last_name,
    date_of_birth           = :date_of_birth,
    gender                  = :gender,
    phone_number            = :phone_number,
    email                   = :email,
    address                 = :address,
    age                     = :age
    WHERE patient_id        = :patient_id";

$stmt = $conn->prepare($query);
$stmt->execute([
    ':first_name'    => $data->first_name,
    ':last_name'     => $data->last_name,
    ':date_of_birth' => $data->date_of_birth,
    ':gender'        => $data->gender,
    ':phone_number'  => $data->phone_number ?? null,
    ':email'         => $data->email ?? null,
    ':address'       => $data->address ?? null,
    ':age'           => $data->age,
    ':patient_id'    => $data->patient_id,
]);

echo json_encode(["message" => "Patient record updated successfully"]);
?>
