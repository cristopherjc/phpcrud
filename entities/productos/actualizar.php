<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "../../auth/auth.php";
require_once "../../config/db.php";

if (!in_array($_SESSION['usuario_rol'], ['sysadmin', 'admin_bodega', 'empleado'])) {
    $_SESSION['error'] = "No tienes permiso para esta acción.";
    header("Location: index.php");
    exit;
}

if (!isset($_POST['id'])) {
    $_SESSION['error'] = "ID de producto no recibido.";
    header("Location: index.php");
    exit;
}

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio_compra = floatval($_POST['precio_compra']);
$precio_venta = floatval($_POST['precio_venta']);
$stock = intval($_POST['stock']);
$stock_minimo = intval($_POST['stock_minimo']);

$rol = $_SESSION['usuario_rol'];

// precio minimo permitido
$precio_minimo = $precio_compra * 1.30;
$precio_minimo = number_format($precio_minimo, 2, '.', '');
if ($precio_venta < $precio_minimo) {
    $_SESSION['error'] = "El precio de venta no puede ser menor al 30% de ganancia. Mínimo permitido: $precio_minimo";
    header("Location: editar.php?id=" . $id);
    exit;
}

// si el rol es empleado validar reglas
if ($rol === 'empleado') {
    $stmt = $pdo->prepare("
        UPDATE productos SET
            nombre = ?,
            precio_venta = ?,
            stock = ?,
            updated_at = NOW()
        WHERE id = ?
    ");
    $ok = $stmt->execute([$nombre, $precio_venta, $stock, $id]);

} else {
    // sysadmin y admin_bodega pueden modificar todo
    $stmt = $pdo->prepare("
        UPDATE productos SET
            nombre = ?,
            descripcion = ?,
            precio_compra = ?,
            precio_venta = ?,
            stock = ?,
            stock_minimo = ?,
            updated_at = NOW()
        WHERE id = ?
    ");

    $ok = $stmt->execute([
        $nombre,
        $descripcion,
        $precio_compra,
        $precio_venta,
        $stock,
        $stock_minimo,
        $id
    ]);
}

if ($ok) {
    $_SESSION['success'] = "Producto actualizado correctamente.";
} else {
    $_SESSION['error'] = "Error al actualizar el producto.";
}
header("Location: index.php");
exit;
