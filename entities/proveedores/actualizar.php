<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

$errors = [];

if (!in_array($_SESSION['usuario_rol'], ['sysadmin', 'admin_bodega'])) {
    $_SESSION['error'] = "No tienes permiso para esta acción.";
    header("Location: index.php");
    exit;
}

if (!isset($_POST['id'])) {
    $_SESSION['error'] = "Codigo de bodega no recibido.";
    header("Location: index.php");
    exit;
}

$id = $_POST['id'];
$ruc = $_POST['ruc'];
$nombre = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');

$stmt = $pdo->prepare("UPDATE proveedores SET ruc = ?, nombre = ?, telefono = ?, ciudad = ?, updated_at = NOW() WHERE id = ?");
$stmt->execute([$ruc, $nombre, $telefono, $ciudad, $id]);

header("Location: index.php");
exit;
?>
