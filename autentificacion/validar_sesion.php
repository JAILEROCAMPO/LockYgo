<?php
include "validar_token.php";

if (!isset($_COOKIE["token"])) {
    echo json_encode(["success" => false]);
    exit();
}

$datos = validarToken($_COOKIE["token"]);

if (!$datos["success"]) {
    echo json_encode(["success" => false]);
    exit();
}

// Si el usuario tiene una sesión válida
echo json_encode(["success" => true, "rol" => $datos["rol"]]);
?>
