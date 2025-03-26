<?php
/** creamos una funciona que maneje el token y expire en una hora 
 *@param int $idUsuario ID del usuario autenticado
 *@return string token generado en base64
*/

function generarToken($idUsuario){
    $claveSecreta = "cX2d9!@#5bP*Ly0m&z8";
    $tiempoExpiracion = time() + 3600; // 1 hora de token valido

    //datos que contienen el token
    $datosToken=[
        "id" => $idUsuario,
        "rol" => $rol,
        "exp" => $tiempoExpiracion
    ];
    //se genera el token firmandolo con HMAC-SHA256
    $token = base64_encode(json_encode($datosToken).".".hash_hmac("sha256", json_encode($datosToken),$claveSecreta));
    //retornamos el token para usar en otros archivos

    return $token;
}
?>