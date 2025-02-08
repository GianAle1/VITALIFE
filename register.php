<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once "db_connection.php"; // Incluir conexión a la BD

$data = json_decode(file_get_contents("php://input"), true);

// Validar si el JSON es correcto
if (!$data) {
    echo json_encode(["success" => false, "message" => "Error en la solicitud: JSON inválido"]);
    exit;
}

// Validar campos requeridos
if (!isset($data["nombres"], $data["apellidos"], $data["email"], $data["password"])) {
    echo json_encode(["success" => false, "message" => "Faltan datos en la solicitud"]);
    exit;
}

// Extraer valores
$nombres = trim($data["nombres"]);
$apellidos = trim($data["apellidos"]);
$email = trim($data["email"]);
$password = trim($data["password"]); // ⚠️ No está encriptado
$fecha_nacimiento = isset($data["fecha_nacimiento"]) ? trim($data["fecha_nacimiento"]) : null;
$peso = isset($data["peso"]) ? floatval($data["peso"]) : null;
$talla = isset($data["talla"]) ? floatval($data["talla"]) : null;

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Correo electrónico inválido"]);
    exit;
}

// Verificar si el correo ya está registrado
$checkEmailQuery = "SELECT id FROM usuarios WHERE email = ?";
$checkStmt = $conn->prepare($checkEmailQuery);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "El correo ya está registrado"]);
    exit;
}
$checkStmt->close();

// Insertar en la base de datos sin encriptar la contraseña
$sql = "INSERT INTO usuarios (nombres, apellidos, email, password, fecha_nacimiento, peso, talla) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $nombres, $apellidos, $email, $password, $fecha_nacimiento, $peso, $talla);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Registro exitoso"]);
} else {
    echo json_encode(["success" => false, "message" => "Error en el registro: " . $stmt->error]);
}

// Cerrar conexiones
$stmt->close();
$conn->close();
?>
