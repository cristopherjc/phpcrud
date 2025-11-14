<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

if ($_SESSION['usuario_rol'] !== 'sysadmin') {
    header("Location: ../../index.php");
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
        <input type="text" name="cedula" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Alias</label>
        <input type="text" name="alias" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nombres</label>
        <input type="text" name="nombres" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Apellidos</label>
        <input type="text" name="apellidos" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Correo</label>
        <input type="email" name="correo" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Clave</label>
        <input type="password" name="clave" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Rol</label>
        <select name="rol" class="form-control" id="rol">
            <option value="sysadmin">sysadmin</option>
            <option value="admin_bodega">admin_bodega</option>
            <option value="empleado">empleado</option>
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
</form>

<script>
// Esto sirve para ocultar la seleccion de bodega si el usuario es un sysadmin
document.getElementById("rol").addEventListener("change", function() {
document.getElementById("bodegaSelect").style.display = this.value === "sysadmin" ? "none" : "block";});
</script>

</body>
</html>
