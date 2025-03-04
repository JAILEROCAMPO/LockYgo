<?php
session_start();
include "../conexion/db.php"; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["contraseña"];

    // Buscar en la tabla de administradores
    $sql_admin = "SELECT id, nombre, contrasena FROM administradores WHERE email = ?";
    $stmt = $conn->prepare($sql_admin);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $admin = $resultado->fetch_assoc();
        if (password_verify($password, $admin['contrasena'])) {
            $_SESSION["usuario"] = $admin["nombre"];
            $_SESSION["rol"] = "admin";
            header("Location: ../admin/index_admin.html");
            exit();
        }
    }

    // Buscar en la tabla de estudiantes
    $sql_estudiante = "SELECT id, nombre, contrasena FROM estudiantes WHERE email = ?";
    $stmt = $conn->prepare($sql_estudiante);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $estudiante = $resultado->fetch_assoc();
        if (password_verify($password, $estudiante['contrasena'])) {
            $_SESSION["usuario"] = $estudiante["nombre"];
            $_SESSION["rol"] = "estudiante";
            header("Location: ../estudiante/index_estudiante.html");
            exit();
        }
    }

    // Si no coincide con ningún usuario
    echo "<script>alert('Credenciales incorrectas'); window.history.back();</script>";
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
