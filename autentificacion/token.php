<?php
session_start(); // Inicia la sesión

function generarToken($id_usuario, $rol) {
    $token = bin2hex(random_bytes(32)); // Token aleatorio seguro
    $_SESSION["tokens"][$token] = [
        "id" => $id_usuario,
        "rol" => $rol,
        "expira" => time() + 3600 // Expira en 1 hora
    ];
    return $token;
}

function validarToken($token) {
    if (isset($_SESSION["tokens"][$token])) {
        if ($_SESSION["tokens"][$token]["expira"] > time()) {
            return [
                "success" => true,
                "id" => $_SESSION["tokens"][$token]["id"],
                "rol" => $_SESSION["tokens"][$token]["rol"]
            ];
        }
    }
    return ["success" => false];
}
?>
