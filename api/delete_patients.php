<?php
header("Content-Type: application/json");
include_once '../config/db.php';
include_once 'auth_check.php';
checkAccess(['Admin']);

$data = json_decode(file_get_contents("php://input"));

if (empty($data->id)) {
    echo json_encode(["message" => "Error: Patient ID is required"]);
} else {
    $stmt = $conn->prepare("DELETE FROM patients WHERE patient_id = :id");
    $stmt->execute([':id' => $data->id]);
    echo json_encode(["message" => "Patient record deleted"]);
}
?>