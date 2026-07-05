<?php
include_once 'auth_check.php';
checkAccess(['Doctor', 'Admin']); // Only Doctors/Admin can edit records

$data = json_decode(file_get_contents("php://input"));
$query = "UPDATE patients SET full_name = :name, age = :age WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->execute([':name' => $data->name, ':age' => $data->age, ':id' => $data->id]);
echo json_encode(["message" => "Patient record updated"]);
?>