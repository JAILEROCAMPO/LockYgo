<?php
$servername = "lockygo.mysql.database.azure.com";
$username = "jaileradmin";
$password = "Babahaha19";
$database = "lockygo";
$port = 3306;

// Crear conexión con SSL sin especificar cafile
$conn = mysqli_init();
mysqli_real_connect($conn, $servername, $username, $password, $database, $port, NULL, MYSQLI_CLIENT_SSL);

if (mysqli_connect_errno()) {
    die("❌ Error de conexión: " . mysqli_connect_error());
}

echo "✅ Conexión segura establecida correctamente.";
?>
