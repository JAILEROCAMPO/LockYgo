<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../conexion/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST["token"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    // Buscar el token en ambas tablas
    $sql = "SELECT 'estudiante' AS tipo_usuario, id FROM estudiantes WHERE token = ? AND expira_token > NOW()
            UNION
            SELECT 'admin' AS tipo_usuario, id FROM administradores WHERE token = ? AND expira_token > NOW()";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $token, $token);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($tipo_usuario, $id);

    if ($stmt->fetch()) {
        // Determinar la tabla correcta
        $tabla = ($tipo_usuario === "estudiante") ? "estudiantes" : "administradores";

        // Actualizar la contraseña
        $stmt = $conn->prepare("UPDATE $tabla SET contrasena = ?, token = NULL, expira_token = NULL WHERE id = ?");
        $stmt->bind_param("si", $password, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Tu contraseña ha sido actualizada.'); window.location.href = '../login/inicioSesion_usuario.html';</script>";
        } else {
            echo "<script>alert('Error al actualizar la contraseña.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Token inválido o expirado.'); window.history.back();</script>";
    }
}
?>
