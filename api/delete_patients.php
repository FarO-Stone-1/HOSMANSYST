<?php
include_once 'auth_check.php';
checkAccess(['Admin']); // Only Admins can delete

$data = json_decode(file_get_contents("php://input"));
$stmt = $conn->prepare("DELETE FROM patients WHERE id = :id");
$stmt->execute([':id' => $data->id]);
echo json_encode(["message" => "Patient record deleted"]);
?>