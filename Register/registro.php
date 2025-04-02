<?php
include "../conexion/dbpdo.php"; // Asegúrate de que este archivo establece la conexión correctamente

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $identificacion = $_POST["identificacion"];
    $ficha = $_POST["ficha"];
    $programaf = $_POST["programa"];       
    $jornada = $_POST["jornada"];
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $contrasena = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);
    
    // Validar que el correo sea del dominio @soy.sena.edu.co
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@soy\.sena\.edu\.co$/", $email)) {
        die("<script>alert('Correo no permitido. Use un correo @soy.sena.edu.co'); window.history.back();</script>");
    }

    try{
        $sql = "INSERT INTO estudiantes(nombre,apellidos,celular,identificacion,ficha,email,jornada,programa_formacion,contrasena)VALUES(:nombre,:apellidos,:celular,:identificacion,:ficha,:email,:jornada,:programa_formacion,:contrasena)";

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(":nombre",$nombre,PDO::PARAM_STR);
        $stmt->bindParam(":apellidos",$apellido,PDO::PARAM_STR);
        $stmt->bindParam(":celular",$telefono,PDO::PARAM_STR);
        $stmt->bindParam(":identificacion",$identificacion,PDO::PARAM_STR);
        $stmt->bindParam(":ficha",$ficha,PDO::PARAM_STR);
        $stmt->bindParam(":email",$email,PDO::PARAM_STR);
        $stmt->bindParam(":jornada",$jornada,PDO::PARAM_STR);
        $stmt->bindParam(":programa_formacion",$programaf,PDO::PARAM_STR);
        $stmt->bindParam(":contrasena",$contrasena,PDO::PARAM_STR);

        if($stmt->execute()){
            echo"Estudiante registrado exitosamente";
        }else{
            echo"error al registar Estudiante";
        }
        
    }catch(PDOException $error){
        echo"Registro fallido: ".$error->getMessage();
    }

}
?>
