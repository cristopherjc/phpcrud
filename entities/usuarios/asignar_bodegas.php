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

$idUsuario = $_GET["id"];

// Usuario
$u = $pdo->prepare("SELECT * FROM usuarios WHERE id=?");
$u->execute([$idUsuario]);
$usuario = $u->fetch();

// Todas las bodegas
$bod = $pdo->query("SELECT * FROM bodegas ORDER BY ciudad");
$bodegas = $bod->fetchAll(PDO::FETCH_ASSOC);

// Bodegas asignadas
$asig = $pdo->prepare("SELECT id_bodega FROM usuarios_bodegas WHERE id_usuario=?");
$asig->execute([$idUsuario]);
$asignadas = array_column($asig->fetchAll(), "id_bodega");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Bodegas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="p-4">

<h3>Asignar Bodega a <?= $usuario['alias'] ?></h3>

<form action="guardar_bodegas.php" method="POST">
    <input type="hidden" name="id_usuario" value="<?= $idUsuario ?>">

    <?php foreach ($bodegas as $b): ?>
        <div class="form-check">
            <input class="form-check-input"
                   type="checkbox"
                   name="bodegas[]"
                   value="<?= $b['id'] ?>"
                   <?= in_array($b['id'], $asignadas) ? "checked" : "" ?>>
            <label class="form-check-label">
                <?= $b['cod_bodega'] ?> - <?= $b['ciudad'] ?>
            </label>
        </div>
    <?php endforeach; ?>

    <button class="btn btn-success mt-3">Guardar Asignaciones</button>
</form>

</body>
</html>
