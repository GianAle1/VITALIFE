<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json; charset=UTF-8");

require_once "db_connection.php";

if (!isset($_GET["userId"])) {
    echo json_encode(["success" => false, "message" => "Falta el parámetro userId"]);
    exit;
}

$userId = intval($_GET["userId"]);

$sql = "SELECT nombres, apellidos, email, fecha_nacimiento, peso, talla, genero, nivel_actividad, 
        TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad 
        FROM usuarios WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $user = [
        "nombres" => $row["nombres"] ?? "No registrado",
        "apellidos" => $row["apellidos"] ?? "No registrado",
        "email" => $row["email"] ?? "No registrado",
        "fecha_nacimiento" => $row["fecha_nacimiento"] ?? "No registrada",
        "peso" => $row["peso"] ?? "No registrada",
        "talla" => $row["talla"] ?? "No registrada",
        "genero" => $row["genero"] ?? "No especificado",
        "nivel_actividad" => $row["nivel_actividad"] ?? "No especificado",
        "edad" => $row["edad"] ?? "N/A"
    ];

    echo json_encode(["success" => true, "user" => $user]);
} else {
    echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
}

$stmt->close();
$conn->close();
?>
