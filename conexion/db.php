<?php
$host = "localhost";
$user = "root";
$pass = ""; // Reemplázalo con tu contraseña real si tienes una
$dbname = "lockygo";
$port = 3306;

// Crear la conexión con las variables correctas
$conn = new mysqli($host, $user, $pass, $dbname, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
