<?php
require 'conexion.php';
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login/inicioSesion_usuario.html");
    exit();
}

$id_estudiante = $_SESSION['id_estudiante'];
$nombre = trim($_POST['nombre']);
$apellidos = trim($_POST['apellidos']);
$celular = trim($_POST['celular']);
$jornada = trim($_POST['jornada']);

try {
    $stmt = $pdo->prepare("UPDATE estudiantes SET nombre = ?, apellidos = ?, celular = ?, jornada = ? WHERE id = ?");
    $stmt->execute([$nombre, $apellidos, $celular, $jornada, $id_estudiante]);

    header("Location: perfil.php?success=1");
    exit();
} catch (PDOException $e) {
    die("Error al actualizar datos: " . $e->getMessage());
}
?>
