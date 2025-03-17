<?php
include "../conexion/db.php"; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"] ?? "";
    $apellidos = $_POST["apellido"] ?? "";
    $telefono = $_POST["telefono"] ?? "";
    $identificacion = $_POST["identificacion"] ?? "";
    $email = $_POST["email"] ?? "";
    $contraseña = $_POST["contraseña"] ?? "";
    $confirmar_contraseña = $_POST["confirmar_contraseña"] ?? "";
    $tipo_usuario = $_POST["tipo_usuario"] ?? ""; // 'admin' o 'estudiante'

    // Si es estudiante, obtener datos adicionales
    $ficha = $_POST["ficha"] ?? null;
    $jornada = $_POST["jornada"] ?? null;
    $programa_formacion = $_POST["programa"] ?? null;

    // Verificar si las contraseñas coinciden
    if ($contraseña !== $confirmar_contraseña) {
        echo "<script>alert('❌ Error: Las contraseñas no coinciden.'); window.history.back();</script>";
        exit();
    }

    // Hashear la contraseña antes de guardarla en la base de datos
    $contrasena_hashed = password_hash($contraseña, PASSWORD_DEFAULT);

    try {
        if ($tipo_usuario === "admin") {
            $sql = "INSERT INTO administradores (nombre, apellidos, celular, identificacion, email, contrasena) 
                    VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $nombre, $apellidos, $telefono, $identificacion, $email, $contrasena_hashed);
        } elseif ($tipo_usuario === "estudiante") {
            // Verificar que ficha no sea NULL para estudiantes
            if (empty($ficha) || empty($jornada) || empty($programa_formacion)) {
                echo "<script>alert('❌ Error: Todos los campos de estudiante son obligatorios.'); window.history.back();</script>";
                exit();
            }

            $sql = "INSERT INTO estudiantes (nombre, apellidos, celular, identificacion, ficha, email, jornada, contrasena, programa_formacion) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss", $nombre, $apellidos, $telefono, $identificacion, $ficha, $email, $jornada, $contrasena_hashed, $programa_formacion);
        } else {
            echo "<script>alert('❌ Error: Tipo de usuario no válido.'); window.history.back();</script>";
            exit();
        }

        if ($stmt->execute()) {
            echo "<script>alert('✅ Usuario registrado con éxito.'); window.location.href = 'registros.html';</script>";
        } else {
            throw new Exception("Error en la ejecución: " . $stmt->error);
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo "<script>alert('❌ Error: " . $e->getMessage() . "'); window.history.back();</script>";
        exit();
    }
}
?>
/*
