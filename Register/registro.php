<?php
include "../conexion/db.php"; // Asegúrate de que este archivo se conecta correctamente

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellido"];
    $telefono = $_POST["telefono"];
    $identificacion = $_POST["identificacion"];
    $ficha = $_POST["ficha"];
    $email = $_POST["email"];
    $jornada = $_POST["jornada"];
    $programa_formacion = $_POST["programa"];
    $contrasena = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);

    // Preparamos la consulta segura
    $sql = "INSERT INTO estudiantes (nombre, apellidos, celular, identificacion, ficha, email, jornada, contrasena, programa_formacion) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssssssss", $nombre, $apellidos, $telefono, $identificacion, $ficha, $email, $jornada, $contrasena, $programa_formacion);

        // 🔍 Imprimir consulta antes de ejecutar (para depuración)
        echo "Consulta preparada: " . $sql . "<br>";

        if ($stmt->execute()) {
            echo "<script>
                    alert('Usuario registrado con éxito');
                    window.location.href = '../login/inicioSesion_usuario.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Error al registrar usuario: " . $stmt->error . "');
                    window.history.back();
                  </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
                alert('Error en la preparación de la consulta.');
                window.history.back();
              </script>";
    }

    $conn->close();
}
?>
