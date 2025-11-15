<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

$errors = [];

if ($_SESSION['usuario_rol'] != 'sysadmin') {
    $_SESSION['error'] = "No tienes permisos para esta acción.";
    header("Location: index.php");
    exit;
}

if (!isset($_POST['id'])) {
    $_SESSION['error'] = "ID de proveedor no recibido.";
    header("Location: index.php");
    exit;
}

$id = $_POST['id'];
$cod_bodega = trim($_POST['cod_bodega'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

// validaciones
if (strlen($ciudad) < 4) {
    $errors[] = "El nombre de ciudad no puede ser tan corto";
}

if (!empty($errors)) {
    $_SESSION['error'] = $errors[0];
    header("Location: editar.php");
    exit;
}

$stmt = $pdo->prepare("UPDATE bodegas SET cod_bodega = ?, ciudad = ?, direccion = ?, updated_at = NOW() WHERE id = ?");
$stmt->execute([$cod_bodega, $ciudad, $direccion, $id]);

header("Location: index.php");
exit;
?>
