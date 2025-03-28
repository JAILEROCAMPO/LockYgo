<?php
session_start();
include "../conexion/dbpdo.php"; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["contraseña"]);

    try {
        // Consultar en la tabla administradores
        $stmt = $conn->prepare("SELECT id, nombre, contrasena FROM administradores WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['contrasena'])) {
            // Guardar datos en sesión
            $_SESSION["autenticado"] = true;
            $_SESSION["id_usuario"] = $admin["id"];
            $_SESSION["nombre"] = $admin["nombre"];
            $_SESSION["rol"] = "admin";

            header("Location: ../admin/index_admin.php");
            exit();
        }

        // Consultar en la tabla estudiantes
        $stmt = $conn->prepare("SELECT id, nombre, contrasena FROM estudiantes WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($estudiante && password_verify($password, $estudiante['contrasena'])) {
            // Guardar datos en sesión
            $_SESSION["autenticado"] = true;
            $_SESSION["id_usuario"] = $estudiante["id"];
            $_SESSION["nombre"] = $estudiante["nombre"];
            $_SESSION["rol"] = "estudiante";

            header("Location: ../estudiante/index_estudiante.php");
            exit();
        }

        echo "<script>alert('Credenciales incorrectas'); window.history.back();</script>";

    } catch (PDOException $e) {
        echo "<script>alert('Error en la conexión: " . $e->getMessage() . "');</script>";
    }
}
?>
