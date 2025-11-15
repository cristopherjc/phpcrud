<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

$rol = $_SESSION['usuario_rol'];
$bodega_id = null;
if ($rol !== 'sysadmin') {
    if (!isset($_SESSION['usuario_bodega'])) {
        die("Error: No tienes una bodega asignada.");
    }
    $bodega_id = $_SESSION['usuario_bodega'];
}

if ($rol === 'sysadmin') {
    $stmt = $pdo->query("
        SELECT p.*, b.ciudad, pr.nombre AS proveedor
        FROM productos p
        LEFT JOIN bodegas b ON p.id_bodega = b.id
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id
        ORDER BY p.id DESC
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT p.*, b.ciudad, pr.nombre AS proveedor
        FROM productos p
        LEFT JOIN bodegas b ON p.id_bodega = b.id
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id
        WHERE p.id_bodega = ?
        ORDER BY p.id DESC
    ");
    $stmt->execute([$bodega_id]);
}

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body class="p-4 bg-light">
<div class="container">
    <h2>Productos</h2>
    <a href="../../index.php" class="btn btn-danger mb-3">Dashboard</a>
    <?php if ($rol !== 'empleado'): ?>
        <a href="crear.php" class="btn btn-primary mb-3">Crear Producto</a>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
    <thead>
        <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Bodega</th>
        <th>Proveedor</th>
        <th>Precio Compra</th>
        <th>Precio Venta</th>
        <th>Stock</th>
        <th>Acciones</th>
        </tr>
    </thead>
    <tbody>

    <?php foreach ($productos as $p): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= $p['nombre'] ?></td>
        <td><?= $p['ciudad'] ?></td>
        <td><?= $p['proveedor'] ?></td>
        <td>$<?= $p['precio_compra'] ?></td>
        <td>$<?= $p['precio_venta'] ?></td>
        <td><?= $p['stock'] ?></td>
        <td>
            <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Editar</a>

            <?php if ($rol !== 'empleado'): ?>
                <button class="btn btn-danger btn-sm" onclick="eliminar(<?= $p['id'] ?>)">Eliminar</button>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>

    </tbody>
    </table>
</div>
<script>
function eliminar(id) {
    Swal.fire({
        title: 'Eliminar producto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar'
    }).then((res)=>{
        if(res.isConfirmed){
            window.location.href = "eliminar.php?id="+id;
        }
    });
}
</script>
</body>
</html>
