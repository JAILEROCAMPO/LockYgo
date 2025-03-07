<?php
include "../conexion/db.php"; // Asegúrate de que este archivo establece la conexión correctamente

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellido"];
    $telefono = $_POST["telefono"];
    $identificacion = $_POST["identificacion"];
    $email = $_POST["email"];
    $contrasena = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);
    $tipo_usuario = $_POST["tipo_usuario"]; // 'estudiante' o 'admin'

    // Si es un estudiante, se requieren estos campos adicionales
    $ficha = $_POST["ficha"] ?? null;
    $jornada = $_POST["jornada"] ?? null;
    $programa_formacion = $_POST["programa"] ?? null;

    // Determinar a qué tabla insertar
    if ($tipo_usuario === "estudiante") {
        $sql = "INSERT INTO estudiantes (nombre, apellidos, celular, identificacion, ficha, email, jornada, contrasena, programa_formacion) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssssss", $nombre, $apellidos, $telefono, $identificacion, $ficha, $email, $jornada, $contrasena, $programa_formacion);
        }
    } elseif ($tipo_usuario === "admin") {
        $sql = "INSERT INTO administradores (nombre, apellidos, celular, identificacion, email, contrasena) 
                VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssss", $nombre, $apellidos, $telefono, $identificacion, $email, $contrasena);
        }
    } else {
        echo "<script>
                alert('Tipo de usuario no válido.');
                window.history.back();
              </script>";
        exit();
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>
                alert('Usuario registrado con éxito');
                window.location.href = '';
              </script>";
    } else {
        echo "<script>
                alert('Error al registrar usuario: " . $stmt->error . "');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>
