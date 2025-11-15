<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../auth/auth.php';
require_once '../../config/db.php';

// Solo sysadmin y admin_bodega pueden crear productos
if (!in_array($_SESSION['usuario_rol'], ['sysadmin', 'admin_bodega'])) {
    $_SESSION['error'] = "No tienes permiso para crear productos.";
    header("Location: index.php");
    exit;
}

$rol = $_SESSION['usuario_rol'];
$usuario_id = $_SESSION['usuario_id'] ?? null;

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$id_categoria = intval($_POST['id_categoria'] ?? 0);
$id_proveedor = intval($_POST['id_proveedor'] ?? 0);
$stock = intval($_POST['stock'] ?? 0);
$stock_minimo = intval($_POST['stock_minimo'] ?? 0);

$precio_compra = floatval($_POST['precio_compra'] ?? 0);
$precio_venta = floatval($_POST['precio_venta'] ?? 0);

// Bodega
$id_bodega = $_POST['id_bodega'];

// Validación básica
if (
    $nombre === '' || $id_categoria <= 0 || $id_proveedor <= 0 ||
    $precio_compra <= 0 || $precio_venta <= 0 || $id_bodega <= 0
) {
    $_SESSION['error'] = "Completa todos los campos obligatorios.";
    header("Location: crear.php");
    exit;
}

// Validación 30% mínimo
$precioMinimo = $precio_compra * 1.30;

if ($precio_venta < $precioMinimo) {
    $_SESSION['error'] = "El precio de venta mínimo permitido es $" . number_format($precioMinimo, 2);
    header("Location: crear.php");
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO productos (
        id_bodega, id_proveedor, id_categoria, nombre, descripcion,
        precio_compra, precio_venta, stock, stock_minimo,
        created_at, updated_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
");

$ok = $stmt->execute([
    $id_bodega,
    $id_proveedor,
    $id_categoria,
    $nombre,
    $descripcion,
    $precio_compra,
    $precio_venta,
    $stock,
    $stock_minimo
]);

if ($ok) {
    $_SESSION['success'] = "Producto guardado correctamente.";
} else {
    $_SESSION['error'] = "Error al guardar el producto.";
}
header("Location: index.php");
exit;
