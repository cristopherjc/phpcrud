<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";

$form = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

if ($_SESSION['usuario_rol'] != 'sysadmin') {
    $_SESSION['error'] = "No tienes permisos para acceder a esta página.";
    header("Location: ../../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Nueva Bodega</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Nueva Bodega</h2>
  <form action="guardar.php" method="POST">
    <div class="mb-3">
      <label>Código</label>
      <input type="text" name="cod_bodega" class="form-control"
      value="<?= htmlspecialchars($form['cod_bodega'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label>Ciudad</label>
      <input type="text" name="ciudad" class="form-control"
      value="<?= htmlspecialchars($form['ciudad'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
      <label>Dirección</label>
      <input type="text" name="direccion" class="form-control"
      value="<?= htmlspecialchars($form['direccion'] ?? '') ?>" required>
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>
</body>

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

</html>

