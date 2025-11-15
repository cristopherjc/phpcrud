<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

// Solo sysadmin puede entrar
if ($_SESSION['usuario_rol'] !== 'sysadmin') {
    $_SESSION['error'] = "No tienes permiso para ver usuarios.";
    header("Location: ../../index.php");
    exit;
}

// Obtener todos los usuarios con su bodega
$stmt = $pdo->query("
    SELECT u.*, b.ciudad AS bodega_ciudad, b.cod_bodega
    FROM usuarios u
    LEFT JOIN bodegas b ON u.bodega_id = b.id
    ORDER BY u.id DESC
");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body class="p-4">
<div class="container">
    <h2>Usuarios</h2>
    <a href="../../index.php" class="btn btn-danger mb-4">Dashboard</a>
    <a href="crear.php" class="btn btn-primary mb-4">Crear Usuario</a>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Cédula</th>
            <th>Alias</th>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Bodega</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['cedula']) ?></td>
                <td><?= htmlspecialchars($u['alias']) ?></td>
                <td><?= htmlspecialchars($u['nombres'] . " " . $u['apellidos']) ?></td>
                <td><?= htmlspecialchars($u['correo']) ?></td>
                <td><?= htmlspecialchars($u['rol']) ?></td>
                <td>
                    <?= $u['bodega_ciudad'] ? htmlspecialchars($u['bodega_ciudad'] . " (" . $u['cod_bodega'] . ")") : '-' ?>
                </td>
                <td>
                    <a href="editar.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <button class="btn btn-danger btn-sm" onclick="confirmarEliminar(<?= $u['id'] ?>)">Eliminar</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
function confirmarEliminar(id) {
  Swal.fire({
    title: '¿Seguro que deseas eliminar este usuario?',
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
