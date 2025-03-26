<?php
require '../autoload.php'; // Cargamos la librería JWT (si la usas)
include "../conexion/dbpdo.php"; // Conexión a la base de datos
include "../auth/token.php"; // Importamos la función para generar tokens

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
            $token = generarToken($admin["id"], "admin"); // Generar token
            setcookie("token", $token, time() + 3600, "/", "", false, true); // Guardar el token en una cookie

            header("Location: ../admin/index_admin.html");
            exit();
        }

        // Buscar en la tabla de estudiantes
        $sql_estudiante = "SELECT id, nombre, contrasena FROM estudiantes WHERE email = :email";
        $stmt = $conn->prepare($sql_estudiante);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $estudiante = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($estudiante && password_verify($password, $estudiante['contrasena'])) {
            $token = generarToken($estudiante["id"], "estudiante"); // Generar token
            setcookie("token", $token, time() + 3600, "/", "", false, true); // Guardar el token en una cookie

            header("Location: ../estudiante/index_estudiante.html");
            exit();
        }

        // Si no coincide con ningún usuario
        echo "<script>alert('Credenciales incorrectas'); window.history.back();</script>";

    } catch (PDOException $e) {
        echo "Error en la conexión: " . $e->getMessage();
    }
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
