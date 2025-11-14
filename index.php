<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['error'] = "Debes iniciar sesión para continuar.";
    header("Location: auth/login.php");
    exit;
}

$rol = $_SESSION['usuario_rol'];
$alias = $_SESSION['usuario_alias'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body class="p-4 bg-light">

<div class="container">
    <h2>Bienvenido, <?= htmlspecialchars($alias) ?> (<?= htmlspecialchars($rol) ?>)</h2>
    <a href="auth/logout.php" class="btn btn-danger mb-4">Cerrar sesión</a>

    <div class="row">
        <?php if ($rol === 'sysadmin'): ?>
            <div class="col-md-3 mb-3">
                <a href="entities/usuarios/index.php" class="btn btn-primary w-100">Usuarios</a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="entities/bodegas/index.php" class="btn btn-primary w-100">Bodegas</a>
            </div>
        <?php if ($rol === 'admin_bodega' || $rol === 'sysadmin'): ?>
            <div class="col-md-3 mb-3">
                <a href="entities/proveedores/index.php" class="btn btn-primary w-100">Proveedores</a>
            </div>
        <?php endif; ?>
            <div class="col-md-3 mb-3">
                <a href="entities/categorias/index.php" class="btn btn-primary w-100">Categorías</a>
            </div>
        <?php endif; ?>

        <?php if ($rol === 'sysadmin' || $rol === 'admin_bodega' || $rol === 'empleado'): ?>
            <div class="col-md-3 mb-3">
                <a href="entities/productos/index.php" class="btn btn-primary w-100">Productos</a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
