<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

// Incluir el archivo de conexión a la base de datos
include("db_connection.php");

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar que los datos existan en $_POST antes de usarlos
    $nombres = isset($_POST["nombres"]) ? $_POST["nombres"] : "";
    $apellidos = isset($_POST["apellidos"]) ? $_POST["apellidos"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    // Verificar que los campos no estén vacíos
    if (empty($nombres) || empty($apellidos) || empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios"]);
        exit();
    }

    // Hash de la contraseña
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insertar usuario en la base de datos
    $query = "INSERT INTO users (nombres, apellidos, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $nombres, $apellidos, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Registro exitoso"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error en el registro: " . $stmt->error]);
    }

    $stmt->close();
}

// Cerrar conexión
$conn->close();
?>

