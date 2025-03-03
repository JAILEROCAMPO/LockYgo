<?php
include "../conexion/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $telefono = $_POST["telefono"];
    $identificacion = $_POST["identificacion"];
    $ficha = $_POST["ficha"];
    $email = $_POST["email"];
    $jornada = $_POST["jornada"];
    $contraseña = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre, apellido, telefono, identificacion, ficha, email, jornada, contraseña) 
            VALUES ('$nombre', '$apellido', '$telefono', '$identificacion', '$ficha', '$email', '$jornada', '$contraseña')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Usuario registrado con éxito');
                window.location.href = '../login/inicioSesion_usuario.html';
              </script>";
    } else {
        echo "<script>
                alert('Error al registrar usuario: " . $conn->error . "');
                window.history.back();
              </script>";
    }

    $conn->close();
}
?>
