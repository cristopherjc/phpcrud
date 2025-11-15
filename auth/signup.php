<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Registro público (no requiere auth)
session_start();
require_once "../config/db.php";

// obtener bodegas
$stmt = $pdo->query("SELECT * FROM bodegas");
$bodegas = $stmt->fetchAll();

// recuperar datos del formulario si hubo errores
$form = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Empleado</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="p-4">
<h2>Registro de Empleado</h2>

<form action="guardar_registro.php" method="POST">

    <div class="mb-3">
        <label>Cédula</label>
        <input type="text" name="cedula" maxlength="10"
               class="form-control"
               value="<?= htmlspecialchars($form['cedula'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label>Alias (sin espacios, máx 20)</label>
        <input type="text" name="alias" maxlength="20"
               class="form-control"
               value="<?= htmlspecialchars($form['alias'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label>Nombres</label>
        <input type="text" name="nombres"
               class="form-control"
               value="<?= htmlspecialchars($form['nombres'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label>Apellidos</label>
        <input type="text" name="apellidos"
               class="form-control"
               value="<?= htmlspecialchars($form['apellidos'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label>Correo</label>
        <input type="email" name="correo"
               class="form-control"
               value="<?= htmlspecialchars($form['correo'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
        <label>Contraseña</label>
        <input type="password" name="clave"
               class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Bodega donde trabajará</label>
        <select name="bodega_id" class="form-control" required>
            <option value="">Seleccione...</option>
            <?php foreach ($bodegas as $b): ?>
                <option value="<?= $b['id'] ?>"
                    <?= (isset($form['bodega_id']) && $form['bodega_id'] == $b['id']) ? 'selected' : '' ?>>
                    <?= $b['ciudad'] ?> (<?= $b['cod_bodega'] ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button class="btn btn-primary">Registrarme</button>
    <a href="../index.php" class="btn btn-secondary">Cancelar</a>
</form>

<!-- SweetAlert errores -->
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

<!-- SweetAlert success con redirección -->
<?php if (isset($_SESSION['success'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
  icon: 'success',
  title: 'Cuenta creada',
  text: '<?= addslashes($_SESSION["success"]) ?>'
}).then(() => {
    window.location.href = "../index.php";
});
</script>
<?php unset($_SESSION['success']); endif; ?>

</body>
</html>
