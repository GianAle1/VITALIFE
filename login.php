<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once "db_connection.php";

// Capturar los datos del JSON recibido
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "JSON inválido"]);
    exit;
}

if (!isset($data["email"], $data["password"])) {
    echo json_encode(["success" => false, "message" => "Faltan datos"]);
    exit;
}

$email = $data["email"];
$password = $data["password"];

$sql = "SELECT id, password FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($userId, $dbPassword);
    $stmt->fetch();

    if ($password === $dbPassword) { // ⚠️ Mejora esto con hash en el futuro
        echo json_encode(["success" => true, "message" => "Inicio de sesión exitoso", "userId" => $userId]);
    } else {
        echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
}

$stmt->close();
$conn->close();
?>
