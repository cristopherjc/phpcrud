<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para continuar";
    header("Location: /phpcrud/index.php");
    exit;
}
?>