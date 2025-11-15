<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

// esto es para recuperar los datos del formulario
$form = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

if ($_SESSION['usuario_rol'] !== 'sysadmin') {
    header("Location: index.php");
    exit;
}
$stmt = $pdo->query("SELECT * FROM bodegas");
$bodegas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="p-4">
<h2>Crear Usuario</h2>
<form action="guardar.php" method="POST">
    <div class="mb-3">
        <label>Cédula</label>
        <input type="text" name="cedula" class="form-control"
        value="<?= htmlspecialchars($form['cedula'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label>Alias</label>
        <input type="text" name="alias" class="form-control"
        value="<?= htmlspecialchars($form['alias'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label>Nombres</label>
        <input type="text" name="nombres" class="form-control"
        value="<?= htmlspecialchars($form['nombres'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label>Apellidos</label>
        <input type="text" name="apellidos" class="form-control"
        value="<?= htmlspecialchars($form['apellidos'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label>Correo</label>
        <input type="email" name="correo" class="form-control" 
        value="<?= htmlspecialchars($form['correo'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label>Clave</label>
        <input type="password" name="clave" class="form-control" 
        value="<?= htmlspecialchars($form['clave'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label>Rol</label>
        <select name="rol" class="form-control">
            <option value="sysadmin"     <?= ($form['rol'] ?? '') === 'sysadmin' ? 'selected' : '' ?> >Sysadmin</option>
            <option value="admin_bodega" <?= ($form['rol'] ?? '') === 'admin_bodega' ? 'selected' : '' ?>>Administrador de bodega</option>
            <option value="empleado"     <?= ($form['rol'] ?? '') === 'empleado' ? 'selected' : '' ?>>Empleado</option>
        </select>
    </div>
    <div class="mb-3" id="bodegaSelect">
        <label>Bodega asignada</label>
        <select name="bodega_id" class="form-control">
            <option value="">Seleccione</option>
            <?php foreach ($bodegas as $b): ?>
            <option value="<?= $b['id'] ?>">
            <?= $b['ciudad'] ?> (<?= $b['cod_bodega'] ?>)
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button class="btn btn-primary">Guardar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<!-- Esto sirve para ocultar la seleccion de bodega si el usuario es un sysadmin -->
<script>
document.getElementById("rol").addEventListener("change", function() {
document.getElementById("bodegaSelect").style.display = this.value === "sysadmin" ? "none" : "block";});
</script>

</body>

<!-- Aqui agrego la alerta de las validaciones -->
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
