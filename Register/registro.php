<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../conexion/dbpdo.php"; // Asegúrate que dentro define $conn como instancia de PDO

header("Content-Type: application/json");

$response = ["status" => "error", "message" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validación de datos
    if (
        empty($_POST["nombre"]) || empty($_POST["apellido"]) || empty($_POST["identificacion"]) || 
        empty($_POST["ficha"]) || empty($_POST["programa"]) || empty($_POST["jornada"]) || 
        empty($_POST["telefono"]) || empty($_POST["email"]) || empty($_POST["contraseña"]) || empty($_POST["confirmar_contraseña"])
    ) {
        $response["message"] = "Todos los campos son obligatorios.";
        echo json_encode($response);
        exit();
    }

    // Recibir datos
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $identificacion = trim($_POST["identificacion"]);
    $ficha = trim($_POST["ficha"]);
    $programaf = trim($_POST["programa"]);
    $jornada = trim($_POST["jornada"]);
    $telefono = trim($_POST["telefono"]);
    $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
    $contrasena = $_POST["contraseña"];
    $confirmar_contrasena = $_POST["confirmar_contraseña"];

    if (!$email) {
        $response["message"] = "Correo electrónico no válido.";
        echo json_encode($response);
        exit();
    }

    if ($contrasena !== $confirmar_contrasena) {
        $response["message"] = "Las contraseñas no coinciden.";
        echo json_encode($response);
        exit();
    }

    // Validar que el correo sea institucional
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@soy\.sena\.edu\.co$/", $email)) {
        $response["message"] = "Correo no permitido. Use un correo @soy.sena.edu.co";
        echo json_encode($response);
        exit();
    }

    try {
        // Verificar si ya existe ese correo
        $verificar = $conn->prepare("SELECT id FROM estudiantes WHERE email = :email");
        $verificar->bindParam(":email", $email, PDO::PARAM_STR);
        $verificar->execute();

        if ($verificar->rowCount() > 0) {
            $response["message"] = "Este correo ya está registrado.";
            echo json_encode($response);
            exit();
        }

        // Hash de la contraseña
        $contrasena_hashed = password_hash($contrasena, PASSWORD_DEFAULT);

        $sql = "INSERT INTO estudiantes(nombre, apellidos, celular, identificacion, ficha, email, jornada, programa_formacion, contrasena) 
                VALUES(:nombre, :apellidos, :celular, :identificacion, :ficha, :email, :jornada, :programa_formacion, :contrasena)";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $stmt->bindParam(":apellidos", $apellido, PDO::PARAM_STR);
        $stmt->bindParam(":celular", $telefono, PDO::PARAM_STR);
        $stmt->bindParam(":identificacion", $identificacion, PDO::PARAM_STR);
        $stmt->bindParam(":ficha", $ficha, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":jornada", $jornada, PDO::PARAM_STR);
        $stmt->bindParam(":programa_formacion", $programaf, PDO::PARAM_STR);
        $stmt->bindParam(":contrasena", $contrasena_hashed, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $response["status"] = "success";
            $response["message"] = "Estudiante registrado exitosamente. ¡Inicia sesión!";
        } else {
            $response["message"] = "Error al registrar estudiante.";
        }

    } catch (PDOException $error) {
        if ($error->getCode() == 23000) {
            $response["message"] = "El correo o la identificación ya están registrados.";
        } else {
            $response["message"] = "Registro fallido: " . $error->getMessage();
        }
    }
}

echo json_encode($response);
exit();
