<?php
setcookie("token", "", time() - 3600, "/"); // Eliminar token
header("Location: inicioSesion_usuario.html");
exit();
?>
