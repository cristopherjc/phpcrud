<?php
require_once "auth/auth.php"; // protege la página
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

<h2>Bienvenido <?= $_SESSION['usuario_alias'] ?> (<?= $_SESSION['usuario_rol'] ?>)</h2>

<a href="auth/logout.php">Cerrar sesión</a>

</body>
</html>
