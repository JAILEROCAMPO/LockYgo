<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../conexion/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $tipo_usuario = $_POST["tipo_usuario"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $tabla = ($tipo_usuario === "estudiante") ? "estudiantes" : "administradores";

    $sql = "UPDATE $tabla SET password = ?, token = NULL, expira_token = NULL WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $password, $token);
    $stmt->execute();

    echo "<script>alert('Contraseña actualizada correctamente.'); window.location.href = 'inicioSesion_usuario.html';</script>";
}
?>
