<?php
session_start();
include '../conexion/dbpdo.php'; // This is your PDO connection
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $identificacion = $_POST['identificacion'];
    $ficha = $_POST['ficha'];
    $email = $_POST['email'];
    $jornada = $_POST['jornada'];
    $programa = $_POST['programa'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_BCRYPT);
    
    // Validar que el correo sea del dominio @soy.sena.edu.co
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@soy\.sena\.edu\.co$/", $email)) {
        die("<script>alert('Correo no permitido. Use un correo @soy.sena.edu.co'); window.history.back();</script>");
    }
    
    // Check if email already exists
    $check_sql = "SELECT email FROM estudiantes WHERE email = :email";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() > 0) {
        die("<script>alert('Este correo ya está registrado. Intenta recuperar tu contraseña.'); window.history.back();</script>");
    }
}
?>