<?php
$servername = "lockygo.mysql.database.azure.com";
$username = "jaileradmin";
$password = "Babahaha19";
$database = "lockygo";

// Ruta del certificado SSL
$ssl_cert = __DIR__ . 'DigiCertGlobalRootCA.crt.pem';

try {
    $conn = new PDO(
        "mysql:host=$servername;dbname=$database;port=3306;charset=utf8",
        $username,
        $password,
        [
            PDO::MYSQL_ATTR_SSL_CA => $ssl_cert,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // ⚠️ Desactiva verificación
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

} catch (PDOException $e) {
    die("❌ Error de conexión con SSL: " . $e->getMessage());
}
?>
