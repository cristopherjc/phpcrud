<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

// Solo sysadmin
if ($_SESSION['usuario_rol'] != 'sysadmin') {
    $_SESSION['error'] = "No tienes permisos para acceder a esta página.";
    header("Location: ../../index.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM bodegas ORDER BY id ASC");
$bodegas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bodegas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container mt-5">

  <h2>Bodegas</h2>
    <a href="../../index.php" class="btn btn-danger mb-4">Dashboard</a>
    <a href="crear.php" class="btn btn-success mb-4">Nueva Bodega</a>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Código</th>
        <th>Ciudad</th>
        <th>Dirección</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($bodegas as $bodega): ?>
      <tr>
        <td><?= $bodega['id'] ?></td>
        <td><?= htmlspecialchars($bodega['cod_bodega']) ?></td>
        <td><?= htmlspecialchars($bodega['ciudad']) ?></td>
        <td><?= htmlspecialchars($bodega['direccion']) ?></td>
        <td>
          <a href="editar.php?id=<?= $bodega['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
          <button class="btn btn-danger btn-sm" onclick="confirmarEliminar(<?= $bodega['id'] ?>)">Eliminar</button>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<script>
function confirmarEliminar(id) {
  Swal.fire({
    title: '¿Seguro que deseas eliminar esta bodega?',
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
