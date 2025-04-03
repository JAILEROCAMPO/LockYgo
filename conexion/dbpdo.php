<?php
#creamos las variables con la informacion de la base de datos 
$servername = "localhost"; #servidor 
$username ="root"; #usuario
$password =""; #contraseña
$database ="lockygo3"; #nombre base de datos 
#usamos try para manejar los errores sin que la pagina se detenga
try{ 
    #conectamos a la bd usando PDO
    $conn = new PDO("mysql:host=$servername;dbname=$database;charset=utf8", $username, $password);
    #configurar PDO para manejar errores correctamente 
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    //mostrar el error si la conexion falla
    die("error de conexion: " . $e -> getMessage());
}
?>