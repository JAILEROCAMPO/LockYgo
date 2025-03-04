<?php
$host = "lockygo.mysql.database.azure.com";
$user = "jaileradmin";
$pass = "Babahaha19"; // Reemplázalo con tu contraseña real
$dbname = "LOCKYGO";
$port = 3306;

$conn = new mysqli($servidor, $usuario, $clave, $base_datos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
