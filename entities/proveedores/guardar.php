<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

$errors = [];

// Solo sysadmin o admin_bodega
if (!in_array($_SESSION['usuario_rol'], ['sysadmin', 'admin_bodega'])) {
    $_SESSION['error'] = "No tienes permiso para esta acción.";
    header("Location: index.php");
    exit;
}

$ruc = trim($_POST['ruc'] ?? '');
$nombre = trim($_POST['nombre'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');

// validaciones
if (strlen($ruc) !== 13) {
    $errors[] = "El RUC debe contener exactamente 13 caracteres";
}

if (empty($errors)) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM proveedores WHERE ruc = ?");
    $stmt->execute([$ruc]);
    if ($stmt->fetchColumn() > 0) $errors[] = "El RUC ya está registrado";
}

if (!empty($errors)) {
    $_SESSION['form_data'] = $_POST; 
    $_SESSION['error'] = $errors[0];
    header("Location: crear.php");
    exit;
}

$stmt = $pdo->prepare("INSERT INTO proveedores (ruc, nombre, telefono, ciudad) VALUES (?, ?, ?, ?)");
$stmt->execute([$ruc, $nombre, $telefono, $ciudad]);

header("Location: index.php");
exit;
?>
