<?php
include "token.php"; // Archivo donde generamos y verificamos el token

if (!isset($_COOKIE["token"])) {
    // Si no hay token, redirigir a login
    header("Location: ../login.html");
    exit();
}

// Verificar si el token es válido
$datos_usuario = verificarToken($_COOKIE["token"]);

if (!$datos_usuario) {
    // Si el token es inválido o expiró, redirigir a login
    header("Location: ../login.html");
    exit();
}

// Si el token es válido, se pueden obtener los datos del usuario
$id_usuario = $datos_usuario["id"];
$rol_usuario = $datos_usuario["rol"];
?>
