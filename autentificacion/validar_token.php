<?php
function validarToken($token) {
    $claveSecreta = "cX2d9!@#5bP*Ly0m&z8";

    $partes = explode(".", base64_decode($token));
    if (count($partes) !== 2) {
        return false; // Token inválido
    }

    list($datos, $firma) = $partes;
    $datosArray = json_decode($datos, true);

    if (!$datosArray || !isset($datosArray["exp"]) || $datosArray["exp"] < time()) {
        return false; // Token expirado o inválido
    }

    $firmaVerificada = hash_hmac("sha256", $datos, $claveSecreta);
    if (!hash_equals($firmaVerificada, $firma)) {
        return false; // Token alterado
    }

    // 🔄 Si faltan menos de 5 minutos para expirar, genera un nuevo token
    if ($datosArray["exp"] - time() < 300) {
        $nuevoToken = generarToken($datosArray["id"], $datosArray["rol"]);
        setcookie("token", $nuevoToken, time() + 3600, "/", "", false, true);
    }

    return $datosArray;
}
?>
