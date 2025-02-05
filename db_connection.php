<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

$host = "127.0.0.1";
$user = "root";
$password = "080322";
$dbname = "vitalife";
$port = 3310;

// Conectar a la base de datos
$conn = new mysqli($host, $user, $password, $dbname, $port);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error en la conexión: " . $conn->connect_error]));
}
?>
