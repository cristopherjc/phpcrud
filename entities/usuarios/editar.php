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
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

$stmt2 = $pdo->query("SELECT id, cod_bodega, ciudad FROM bodegas");
$bodegas = $stmt2->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Usuario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
  <h2>Editar Usuario</h2>
    <form action="actualizar.php" method="POST">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        <div class="mb-3">
            <label>Cédula</label>
            <input type="text" name="cedula" class="form-control" value="<?= htmlspecialchars($usuario['cedula']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Alias</label>
            <input type="text" name="alias" class="form-control" value="<?= htmlspecialchars($usuario['alias']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Nombres</label>
            <input type="text" name="nombres" class="form-control" value="<?= htmlspecialchars($usuario['nombres']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Apellidos</label>
            <input type="text" name="apellidos" class="form-control" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Correo</label>
            <input type="text" name="correo" class="form-control" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Clave (dejar vacío si no desea cambiarla)</label>
            <input type="password" name="clave" class="form-control">
        </div>
        <div class="mb-3">
            <label>Rol</label>
            <select name="rol" class="form-control" id="rol">
                <option value="sysadmin" <?php echo $usuario['rol'] == 'sysadmin' ? 'selected' : '' ?>>sysadmin</option>
                <option value="admin_bodega" <?php echo $usuario['rol'] == 'admin_bodega' ? 'selected' : '' ?>>admin_bodega</option>
                <option value="empleado" <?php echo $usuario['rol'] == 'empleado' ? 'selected' : '' ?>>empleado</option>
            </select>
        </div>
        <div class="mb-3" id="bodegaSelect">
            <label>Bodega asignada</label>
            <select name="bodega_id" class="form-control">
                <option value="">-- Ninguna --</option>
                <?php foreach ($bodegas as $b): ?>
                <option value="<?= $b['id'] ?>"
                <?= $usuario['bodega_id'] == $b['id'] ? "selected" : "" ?>>
                <?= $b['ciudad'] ?> (<?= $b['cod_bodega'] ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script>
// ocultar bodega si sysadmin
document.getElementById("bodegaSelect").style.display =
    document.getElementById("rol").value === "sysadmin" ? "none" : "block";

document.getElementById("rol").addEventListener("change", function() {
    document.getElementById("bodegaSelect").style.display =
        this.value === "sysadmin" ? "none" : "block";
});
</script>
</body>
</html>
