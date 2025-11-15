<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

// solo sysadmin
if ($_SESSION['usuario_rol'] != 'sysadmin') {
    $_SESSION['error'] = "No tienes permisos para acceder a esta página.";
    header("Location: ../../index.php");
    exit;
}
$isSysAdmin = $_SESSION['usuario_rol'] === 'sysadmin';

// Traer todas las categorías
$stmt = $pdo->query("SELECT * FROM categorias ORDER BY id DESC");
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Categorías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body class="p-4 bg-light">

<div class="container">
    <h2>Categorías</h2>

    <a href="../../index.php" class="btn btn-danger mb-3">Dashboard</a>
    <?php if ($isSysAdmin): ?>
        <a href="crear.php" class="btn btn-primary mb-3">Crear Categoría</a>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <?php if ($isSysAdmin): ?><th>Acciones</th><?php endif; ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($categorias as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= htmlspecialchars($c['nombre']) ?></td>
                <td><?= htmlspecialchars($c['descripcion']) ?></td>
                <?php if ($isSysAdmin): ?>
                <td>
                    <a href="editar.php?id=<?= $c['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <button class="btn btn-danger btn-sm" onclick="confirmarEliminar(<?= $c['id'] ?>)">Eliminar</button>
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function confirmarEliminar(id) {
  Swal.fire({
    title: '¿Seguro que deseas eliminar esta categoría?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = 'eliminar.php?id=' + id;
    }
  });
}
</script>
</body>
</html>
