<?php
session_start();
session_unset();
session_destroy();
header("Location: ../login/inicioSesion_Usuario.html");
exit();
?>
