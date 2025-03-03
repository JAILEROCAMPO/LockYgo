<?php
$host = "localhost"; 
$user = "root"; // Cambia si tienes otro usuario
$pass = ""; // Pon tu contraseña si la tienes
$dbname = "lockandgo1";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
