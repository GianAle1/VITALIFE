<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

require_once "db_connection.php"; // Conexión a la BD

// Capturar datos del JSON recibido
$data = json_decode(file_get_contents("php://input"), true);

// Validar si el JSON es correcto
if (!$data) {
    echo json_encode(["success" => false, "message" => "Error en la solicitud: JSON inválido"]);
    exit;
}

// Validar campos requeridos
if (!isset($data["nombres"], $data["apellidos"], $data["email"], $data["password"], $data["genero"], $data["nivel_actividad"])) {
    echo json_encode(["success" => false, "message" => "Faltan datos en la solicitud"]);
    exit;
}

// Extraer valores
$nombres = trim($data["nombres"]);
$apellidos = trim($data["apellidos"]);
$email = trim($data["email"]);
$password = password_hash(trim($data["password"]), PASSWORD_BCRYPT); // 🔹 Encriptar la contraseña
$fecha_nacimiento = isset($data["fecha_nacimiento"]) ? trim($data["fecha_nacimiento"]) : null;
$peso = isset($data["peso"]) ? floatval($data["peso"]) : null;
$talla = isset($data["talla"]) ? floatval($data["talla"]) : null;
$genero = trim($data["genero"]);
$nivel_actividad = trim($data["nivel_actividad"]);

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

// Insertar en la base de datos
$sql = "INSERT INTO usuarios (nombres, apellidos, email, password, fecha_nacimiento, peso, talla, genero, nivel_actividad) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssdsss", $nombres, $apellidos, $email, $password, $fecha_nacimiento, $peso, $talla, $genero, $nivel_actividad);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Registro exitoso"]);
} else {
    echo json_encode(["success" => false, "message" => "Error en el registro: " . $stmt->error]);
}

// Cerrar conexiones
$stmt->close();
$conn->close();
?>
