<?php
require_once "db_connection.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Verificar si se recibió el parámetro userId
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["userId"])) {
    $userId = intval($_GET["userId"]);

    // Consulta SQL para obtener el perfil del usuario
    $sql = "SELECT nombres, apellidos, email, fecha_nacimiento, peso, talla, genero, nivel_actividad, 
                   TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad 
            FROM usuarios WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        echo json_encode(["success" => true, "user" => $result]);
    } else {
        echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Solicitud inválida"]);
}
?>
