<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "../../auth/auth.php";
require_once "../../config/db.php";

if (!in_array($_SESSION['usuario_rol'], ['sysadmin', 'admin_bodega'])) {
    $_SESSION['error'] = "No tienes permiso para eliminar productos.";
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de producto no recibido.";
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id']);

$stmt = $pdo->prepare("DELETE FROM productos WHERE id = ?");
$ok = $stmt->execute([$id]);

if ($ok) {
    $_SESSION['success'] = "Producto eliminado correctamente.";
} else {
    $_SESSION['error'] = "No se pudo eliminar el producto.";
}
header("Location: index.php");
exit;
