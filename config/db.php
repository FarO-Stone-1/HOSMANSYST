<?php
$host = "localhost";
$db_name = "hosmansyst_db";
$username = "root";
$password = "";

try {
    // We are creating the $conn variable here
    $conn = new PDO("mysql:host=$host;port=3307;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
?>