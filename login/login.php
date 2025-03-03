<?php
include "../conexion/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $contraseña = $_POST["contraseña"];

    // Verificar si el usuario es un administrador
    $sql_admin = "SELECT * FROM administrador WHERE email = '$email' AND contrasenaadmin = '$contraseña'";
    $result_admin = $conn->query($sql_admin);

    if ($result_admin->num_rows > 0) {
        echo "<script>
                alert('Login exitoso como Administrador');
                window.location.href = '../admin/index_admin.html';
              </script>";
        exit();
    }

    // Verificar si el usuario es un usuario normal
    $sql_user = "SELECT * FROM usuarios WHERE email = '$email' AND contraseña = '$contraseña'";
    $result_user = $conn->query($sql_user);

    if ($result_user->num_rows > 0) {
        echo "<script>
                alert('Login exitoso');
                window.location.href = '../usuario/index_usuario.html';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Credenciales incorrectas');
                window.history.back();
              </script>";
    }

    $conn->close();
}
?>
