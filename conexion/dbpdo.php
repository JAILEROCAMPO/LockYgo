<?php
// Ruta del certificado SSL
$ssl_cert = __DIR__ . '/DigiCertGlobalRootCA.crt.pem';

// Datos de conexión a Azure
$servername = "lockygo.mysql.database.azure.com";
$username = "jaileradmin";
$password = "Babahaha19";
$database = "lockygo";

try {
    // Conectar con PDO usando SSL
    $conn = new PDO(
        "mysql:host=$servername;dbname=$database;charset=utf8;port=3306",
        $username,
        $password,
        [
            PDO::MYSQL_ATTR_SSL_CA => $ssl_cert,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true // o false si no quieres validación estricta
        ]
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "✅ Conexión segura con SSL a Azure establecida.";
} catch (PDOException $e) {
    die("❌ Error de conexión con SSL a Azure: " . $e->getMessage());
}
?>
