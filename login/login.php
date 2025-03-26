<?php
include "../conexion/dbpdo.php"; // Conexión a la BD
include "../autentificacion/token.php"; // Archivo para generar el token

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["contraseña"];

    try {
        // Buscar en la tabla de administradores
        $sql_admin = "SELECT id, nombre, contrasena FROM administradores WHERE email = :email";
        $stmt = $conn->prepare($sql_admin);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['contrasena'])) {
            $token = generarToken($admin["id"], "admin");

            // Guardar token en cookie (httpOnly y secure)
            setcookie("token", $token, time() + 3600, "/", "", true, true);

            echo "<script>window.location.href = '../admin/index_admin.html';</script>";
            exit();
        }

        // Buscar en la tabla de estudiantes
        $sql_estudiante = "SELECT id, nombre, contrasena FROM estudiantes WHERE email = :email";
        $stmt = $conn->prepare($sql_estudiante);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($estudiante && password_verify($password, $estudiante['contrasena'])) {
            $token = generarToken($estudiante["id"], "estudiante");

            // Guardar token en cookie (httpOnly y secure)
            setcookie("token", $token, time() + 3600, "/", "", true, true);

            echo "<script>window.location.href = '../estudiante/index_estudiante.html';</script>";
            exit();
        }

        echo "<script>alert('Credenciales incorrectas'); window.history.back();</script>";

    } catch (PDOException $e) {
        echo "<script>alert('Error en la conexión: " . $e->getMessage() . "');</script>";
    }
}
?>
