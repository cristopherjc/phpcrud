<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

$form = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

if ($_SESSION['usuario_rol'] !== 'sysadmin') {
    $_SESSION['error'] = "No tienes permiso para crear categorías.";
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
<div class="container">
    <h2>Crear Categoría</h2>
    <form action="guardar.php" method="POST">
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control"
            value="<?= htmlspecialchars($form['nombre'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" value="<?= htmlspecialchars($form['nombre'] ?? '') ?>"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
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
