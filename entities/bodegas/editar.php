<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

if ($_SESSION['usuario_rol'] != 'sysadmin') {
    $_SESSION['error'] = "No tienes permisos para acceder a esta página.";
    header("Location: ../../index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM bodegas WHERE id = ?");
$stmt->execute([$id]);
$bodega = $stmt->fetch();

if (!$bodega) {
    $_SESSION['error'] = "Bodega no encontrada";
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Bodega</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Editar Bodega</h2>
  <form action="actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= $bodega['id'] ?>">
    <div class="mb-3">
      <label>Código</label>
      <input type="text" name="cod_bodega" class="form-control" value="<?= htmlspecialchars($bodega['cod_bodega']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Ciudad</label>
      <input type="text" name="ciudad" class="form-control" value="<?= htmlspecialchars($bodega['ciudad']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Dirección</label>
      <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($bodega['direccion']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>
</html>
