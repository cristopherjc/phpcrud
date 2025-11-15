<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

if (!in_array($_SESSION['usuario_rol'], ['sysadmin', 'admin_bodega'])) {
    header("Location: ../../index.php");
    exit;
}

$rol = $_SESSION['usuario_rol'];
$usuario_id = $_SESSION['usuario_id'];
$categorias = $pdo->query("SELECT id, nombre FROM categorias ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
$proveedores = $pdo->query("SELECT id, nombre FROM proveedores ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);

// Bodegas según rol
if ($rol === 'sysadmin') {
    $bodegas = $pdo->query("SELECT id, ciudad, cod_bodega FROM bodegas ORDER BY ciudad")->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $pdo->prepare("SELECT bodega_id FROM usuarios WHERE id = ?");
    $stmt->execute([$usuario_id]);
    $bodega_id = $stmt->fetchColumn();

    $stm2 = $pdo->prepare("SELECT id, ciudad, cod_bodega FROM bodegas WHERE id = ?");
    $stm2->execute([$bodega_id]);
    $bodegas = [$stm2->fetch(PDO::FETCH_ASSOC)];
}

// error
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="p-4 bg-light">

<h2 class="mb-4">Crear Producto</h2>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form action="guardar.php" method="POST" class="mb-5">

    <div class="mb-3">
        <label>Nombre *</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Categoría *</label>
        <select name="id_categoria" class="form-control" required>
            <option value="">Seleccione</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= $c['id'] ?>"><?= $c['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label>Proveedor *</label>
        <select name="id_proveedor" class="form-control" required>
            <option value="">Seleccione</option>
            <?php foreach ($proveedores as $p): ?>
                <option value="<?= $p['id'] ?>"><?= $p['nombre'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ($rol === 'sysadmin'): ?>
        <div class="mb-3">
            <label>Bodega *</label>
            <select name="id_bodega" class="form-control" required>
                <option value="">Seleccione</option>
                <?php foreach ($bodegas as $b): ?>
                <option value="<?= $b['id'] ?>">
                    <?= $b['ciudad'] ?> (<?= $b['cod_bodega'] ?>)
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php else: ?>
        <div class="mb-3">
            <label>Bodega asignada</label>
            <input type="text" class="form-control" disabled 
                   value="<?= $bodegas[0]['ciudad'] . ' (' . $bodegas[0]['cod_bodega'] . ')' ?>">
            <input type="hidden" name="id_bodega" value="<?= $bodegas[0]['id'] ?>">
        </div>
    <?php endif; ?>

    <div class="mb-3">
        <label>Precio de compra *</label>
        <input type="number" name="precio_compra" step="0.01" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Precio de venta *</label>
        <input type="number" name="precio_venta" step="0.01" class="form-control" required>
        <small class="text-muted">Debe ser mínimo 30% superior al precio de compra.</small>
    </div>

    <div class="mb-3">
        <label>Stock inicial</label>
        <input type="number" name="stock" class="form-control" value="0" min="0">
    </div>

    <div class="mb-3">
        <label>Stock mínimo *</label>
        <input type="number" name="stock_minimo" class="form-control" value="0" min="0">
    </div>

    <div class="mb-3">
        <label>Descripción</label>
        <textarea name="descripcion" class="form-control"></textarea>
    </div>

    <button class="btn btn-primary mt-3">Guardar</button>
    <a href="index.php" class="btn btn-secondary mt-3">Cancelar</a>

</form>

</body>
</html>
