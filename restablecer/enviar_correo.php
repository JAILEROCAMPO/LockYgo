<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
include '../conexion/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    $sql = "SELECT 'estudiante' AS tipo_usuario, id FROM estudiantes WHERE email = ?
            UNION
            SELECT 'admin' AS tipo_usuario, id FROM administradores WHERE email = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($tipo_usuario, $id);

    if ($stmt->fetch()) {
        $token = bin2hex(random_bytes(50));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));
        $tabla = ($tipo_usuario === "estudiante") ? "estudiantes" : "administradores";

        $stmt = $conn->prepare("UPDATE $tabla SET token = ?, expira_token = ? WHERE id = ?");
        $stmt->bind_param("ssi", $token, $expira, $id);
        $stmt->execute();

        $link = "https://tu-dominio.com/login/restablecer.php?token=" . $token;

        // Enviar correo con PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP (Gmail)
            $mail->SMTPAuth = true;
            $mail->Username = 'tuemail@gmail.com'; // Correo desde el que se enviará
            $mail->Password = 'tucontraseña'; // Contraseña o App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('tuemail@gmail.com', 'Soporte');
            $mail->addAddress($email);

            $mail->Subject = "Recuperación de Contraseña";
            $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña: " . $link;

            $mail->send();
            echo "<script>alert('Correo enviado correctamente.'); window.location.href = 'inicioSesion_usuario.html';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Error al enviar el correo: {$mail->ErrorInfo}'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('El correo no está registrado.'); window.history.back();</script>";
    }
}
?>
