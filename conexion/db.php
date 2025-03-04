<?php
$host = "lockygo.mysql.database.azure.com";
$user = "jaileradmin";
$pass = "Babahaha19"; // Reemplázalo con tu contraseña real
$dbname = "LOCKYGO";
$port = 3306;

// Ruta al nuevo certificado SSL
$ssl_cert_path = realpath("C:/xampp/htdocs/LockYgo/conexion/DigiCertGlobalRootCA.crt.pem");


// Verificar si el archivo del certificado existe
if (!$ssl_cert_path) {
    die("❌ Error: No se encontró el certificado SSL en la ruta especificada.");
}

// Inicializar conexión MySQLi con SSL
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, $ssl_cert_path, NULL, NULL);

// Intentar la conexión
if (!mysqli_real_connect($conn, $host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL)) {
    die("❌ Error de conexión: " . mysqli_connect_error());
}

echo "✅ Conexión exitosa con SSL.";
echo "Base de datos actual: " . mysqli_fetch_assoc($conn->query("SELECT DATABASE()"))["DATABASE()"];


?>
