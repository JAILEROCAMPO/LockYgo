<?php
include "../conexion/dbpdo.php";
include "../autentificacion/token.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["contraseña"];

    try {
        $stmt = $conn->prepare("SELECT id, nombre, contrasena FROM administradores WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['contrasena'])) {
            $token = generarToken($admin["id"], "admin");
            setcookie("token", $token, time() + 3600, "/", "", false, true);
            header("Location: ../admin/index_admin.html");
            exit();
        }

        $stmt = $conn->prepare("SELECT id, nombre, contrasena FROM estudiantes WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($estudiante && password_verify($password, $estudiante['contrasena'])) {
            $token = generarToken($estudiante["id"], "estudiante");
            setcookie("token", $token, time() + 3600, "/", "", false, true);
            header("Location: ../estudiante/index_estudiante.html");
            exit();
        }

        echo "<script>alert('Credenciales incorrectas'); window.history.back();</script>";

    } catch (PDOException $e) {
        echo "<script>alert('Error en la conexión: " . $e->getMessage() . "');</script>";
    }
}
?>
