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
$stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = ?");
$stmt->execute([$id]);
$categoria = $stmt->fetch();

if (!$categoria) {
    $_SESSION['error'] = "Categoria no encontrada";
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Categoria</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Editar Categoría</h2>
  <form action="actualizar.php" method="POST">
    <input type="hidden" name="id" value="<?= $categoria['id'] ?>">
    <div class="mb-3">
      <label>Nombre</label>
      <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($categoria['nombre']) ?>" required>
    </div>
    <div class="mb-3">
      <label>Descripcion</label>
      <input type="text" name="descripcion" class="form-control" value="<?= htmlspecialchars($categoria['descripcion']) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
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
