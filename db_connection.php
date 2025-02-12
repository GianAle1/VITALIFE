<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$user = "root";
$password = "0803";
$dbname = "vitalife";
$port = 3306;

// Conectar a la base de datos
$conn = new mysqli($host, $user, $password, $dbname, $port);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error en la conexión: " . $conn->connect_error]));
}
?>
