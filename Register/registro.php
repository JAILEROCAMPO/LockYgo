<?php
include "../conexion/db.php"; // Asegúrate de que este archivo establece la conexión correctamente

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"] ?? '';
    $apellidos = $_POST["apellido"] ?? '';
    $telefono = $_POST["telefono"] ?? '';
    $identificacion = $_POST["identificacion"] ?? '';
    $email = $_POST["email"] ?? '';
    $contrasena = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);
    $tipo_usuario = $_POST["tipo_usuario"] ?? '';

    if ($tipo_usuario === "estudiante") {
        // Verificar que los campos de estudiante no sean vacíos
        if (empty($_POST["ficha"]) || empty($_POST["jornada"]) || empty($_POST["programa"])) {
            echo "<script>alert('Todos los campos de estudiante son obligatorios.'); window.history.back();</script>";
            exit();
        }

        $ficha = $_POST["ficha"]; // Asegurar que no sea NULL
        $jornada = $_POST["jornada"];
        $programa_formacion = $_POST["programa"];

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
        echo "<script>alert('Tipo de usuario no válido.'); window.history.back();</script>";
        exit();
    }

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "<script>alert('Usuario registrado con éxito'); window.location.href = '../login/inicioSesion_usuario.html';</script>";
    } else {
        echo "<script>alert('Error al registrar usuario: " . $stmt->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
