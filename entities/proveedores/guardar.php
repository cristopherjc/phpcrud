<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

// Solo sysadmin o admin_bodega
if (!in_array($_SESSION['usuario_rol'], ['sysadmin', 'admin_bodega'])) {
    $_SESSION['error'] = "No tienes permiso para esta acción.";
    header("Location: index.php");
    exit;
}

$ruc = $_POST['ruc'];
$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$ciudad = $_POST['ciudad'];

$stmt = $pdo->prepare("INSERT INTO proveedores (ruc, nombre, telefono, ciudad) VALUES (?, ?, ?, ?)");
$stmt->execute([$ruc, $nombre, $telefono, $ciudad]);

header("Location: index.php");
exit;
?>
