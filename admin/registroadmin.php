<?php
include "../conexion/dbpdo.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $usuario = $_POST["tipo_usuario"];

    if($usuario === "admin"){
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $identificacion = $_POST["identificacion"];
        $telefono = $_POST["telefono"];
        $email = $_POST["email"];
        $contrasena = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);

        try{
            $sql = "INSERT INTO administradores (nombre,apellidos,identificacion,celular,email,contrasena) VALUES (:nombre,:apellido,:identificacion,:telefono,:email,:contrasena)";
            $stmt = $conn->prepare($sql);

            $stmt->bindParam(":nombre",$nombre,PDO::PARAM_STR);
            $stmt->bindParam(":apellido",$apellido,PDO::PARAM_STR);
            $stmt->bindParam(":identificacion",$identificacion,PDO::PARAM_STR);
            $stmt->bindParam(":telefono",$telefono,PDO::PARAM_STR);
            $stmt->bindParam(":email",$email,PDO::PARAM_STR);
            $stmt->bindParam(":contrasena",$contrasena,PDO::PARAM_STR);

            if($stmt->execute()){
                echo"Administrador registrado exitosamente";
            }else{
                echo"error al registrar usuario";
            }

        }catch(PDOException $error){
            echo"error: ". $error->getMessage();
        }


    }elseif($usuario === "estudiante"){
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $identificacion = $_POST["identificacion"];
        $ficha = $_POST["ficha"];
        $programaf = $_POST["programa"];       
        $jornada = $_POST["jornada"];
        $telefono = $_POST["telefono"];
        $email = $_POST["email"];
        $contrasena = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);

        try{
            $sql = "INSERT INTO estudiantes(nombre, apellidos, celular, identificacion, ficha, email, jornada, programa_formacion, contrasena) 
            VALUES(:nombre, :apellidos, :celular, :identificacion, :ficha, :email, :jornada, :programa_formacion, :contrasena)";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":apellidos", $apellido, PDO::PARAM_STR);
            $stmt->bindParam(":celular", $telefono, PDO::PARAM_STR);
            $stmt->bindParam(":identificacion", $identificacion, PDO::PARAM_STR);
            $stmt->bindParam(":ficha", $ficha, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":jornada", $jornada, PDO::PARAM_STR);
            $stmt->bindParam(":programa_formacion", $programaf, PDO::PARAM_STR);
            $stmt->bindParam(":contrasena", $contrasena, PDO::PARAM_STR);  // Añadir la contraseña aquí

            if ($stmt->execute()) {
                echo "Estudiante registrado exitosamente";
            } else {
                echo "Error al registrar Estudiante";
            }

            
        }catch(PDOException $error){
            echo"Registro fallido: ".$error->getMessage();
        }


    }
}


    





?>


