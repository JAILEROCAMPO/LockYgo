<?php
include "../conexion/dbpdo.php"; // Incluir conexión a la base de datos

function validarToken($token) {
    global $conn;
    
    if (!$token) {
        return ["success" => false];
    }

    $sql = "SELECT id, rol FROM usuarios WHERE token = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(1, $token, PDO::PARAM_STR);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        return [
            "success" => true,
            "id" => $usuario["id"],
            "rol" => $usuario["rol"]
        ];
    } else {
        return ["success" => false];
    }
}
?>
