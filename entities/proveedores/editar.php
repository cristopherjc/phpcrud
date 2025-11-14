<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

if (!in_array($_SESSION['usuario_rol'], ['sysadmin', 'admin_bodega'])) {
    $_SESSION['error'] = "No tienes permiso para esta acción.";
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM proveedores WHERE id = ?");
$stmt->execute([$id]);
$proveedor = $stmt->fetch();

if (!$proveedor) {
    $_SESSION['error'] = "Proveedor no encontrado";
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Proveedor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Editar Proveedor</h2>
  <form action="actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= $proveedor['id'] ?>">
    <div class="mb-3">
      <label>Ruc</label>
      <input type="text" name="ruc" class="form-control" value="<?= htmlspecialchars($proveedor['ruc']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($proveedor['nombre']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Telégono</label>
      <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($proveedor['telefono']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Ciudad</label>
      <input type="text" name="ciudad" class="form-control" value="<?= htmlspecialchars($proveedor['ciudad']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>
