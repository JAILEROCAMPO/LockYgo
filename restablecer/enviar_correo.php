<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../conexion/db.php';



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    // Buscar el usuario en ambas tablas con UNION
    $sql = "SELECT 'estudiante' AS tipo_usuario, id FROM estudiantes WHERE email = ?
            UNION
            SELECT 'admin' AS tipo_usuario, id FROM administradores WHERE email = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($tipo_usuario, $id);

    if ($stmt->fetch()) {
        // Generar token único y fecha de expiración
        $token = bin2hex(random_bytes(50));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));
        
        // Determinar la tabla correcta
        $tabla = ($tipo_usuario === "estudiante") ? "estudiantes" : "administradores";

        // Guardar el token en la base de datos
        $stmt = $conn->prepare("UPDATE $tabla SET token = ?, expira_token = ? WHERE id = ?");
        $stmt->bind_param("ssi", $token, $expira, $id);
        $stmt->execute();

        // Enlace de recuperación
        $link = "http://tusitio.com/restablecer.php?token=" . $token;

        // Enviar correo
        $asunto = "Recuperación de Contraseña";
        $mensaje = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $link;
        $cabeceras = "From: no-reply@tusitio.com";

        if (mail($email, $asunto, $mensaje, $cabeceras)) {
            echo "<script>alert('Se ha enviado un correo con instrucciones para recuperar tu contraseña.'); window.location.href = '../login/inicioSesion_usuario.html';</script>";
        } else {
            echo "<script>alert('Error al enviar el correo.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('El correo no está registrado.'); window.history.back();</script>";
    }
}
?>
