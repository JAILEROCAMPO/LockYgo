<?php
setcookie("token", "", time() - 3600, "/"); // Eliminar token
header("Location: login.php");
exit();
?>
