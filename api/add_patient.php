<?php
header("Content-Type: application/json");
include_once '../config/db.php';
include_once 'auth_check.php';
checkAccess(['Doctor', 'Nurse', 'Admin', 'Receptionist']);

$data = json_decode(file_get_contents("php://input"));

if (empty($data->first_name)) {
    echo json_encode(["message" => "Error: First name is required"]);
} elseif (empty($data->last_name)) {
    echo json_encode(["message" => "Error: Last name is required"]);
} elseif (empty($data->date_of_birth)) {
    echo json_encode(["message" => "Error: Date of birth is required"]);
} elseif (empty($data->gender)) {
    echo json_encode(["message" => "Error: Gender is required"]);
} elseif (empty($data->age)) {
    echo json_encode(["message" => "Error: Age is required"]);
} else {
    $query = "INSERT INTO patients 
              (first_name, last_name, date_of_birth, gender, phone_number, email, address, emergency_contact_name, emergency_contact_phone, age) 
              VALUES 
              (:first_name, :last_name, :date_of_birth, :gender, :phone_number, :email, :address, :emergency_contact_name, :emergency_contact_phone, :age)";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':first_name'              => $data->first_name,
        ':last_name'               => $data->last_name,
        ':date_of_birth'           => $data->date_of_birth,
        ':gender'                  => $data->gender,
        ':phone_number'            => $data->phone_number ?? null,
        ':email'                   => $data->email ?? null,
        ':address'                 => $data->address ?? null,
        ':emergency_contact_name'  => $data->emergency_contact_name ?? null,
        ':emergency_contact_phone' => $data->emergency_contact_phone ?? null,
        ':age'                     => $data->age,
    ]);
    echo json_encode(["message" => "Patient successfully registered"]);
}
?>
