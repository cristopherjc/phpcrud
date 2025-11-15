<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
$sesionActiva = isset($_SESSION['usuario_id']);
$rol = $sesionActiva ? $_SESSION['usuario_rol'] : null;
$alias = $sesionActiva ? $_SESSION['usuario_alias'] : null;
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
    <?php if (!$sesionActiva): ?>
    <div class="text-center mt-5">
        <h2>Bienvenido</h2>
        <p class="mb-4">Debes iniciar sesión para acceder al panel.</p>
        <div class="d-flex flex-column align-items-center gap-3 mt-4">
            <a href="auth/login.php" class="btn btn-primary">Iniciar Sesión</a>
            <a href="auth/signup.php" class="btn btn-success">Registro Empleado</a>
        </div>
    </div>
    <?php else: ?>
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
        <?php endif; ?>
        <?php if ($rol === 'admin_bodega' || $rol === 'sysadmin'): ?>
            <div class="col-md-3 mb-3">
                <a href="entities/proveedores/index.php" class="btn btn-primary w-100">Proveedores</a>
            </div>
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
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
  icon: 'error',
  title: 'Error',
  text: '<?= addslashes($_SESSION["error"]) ?>',
  confirmButtonText: 'Entendido'
});
</script>
<?php unset($_SESSION['error']); endif; ?>

</div>

</body>
</html>
