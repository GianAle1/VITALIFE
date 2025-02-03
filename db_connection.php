<?php
$servername = "localhost";
$username = "root";  // Usuario de MySQL
$password = "0803";      // Si tienes contraseña en MySQL, agrégala aquí
$database = "vitalife"; // Nombre de tu base de datos

// Conectar a la base de datos
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conn->connect_error]));
}
?>
