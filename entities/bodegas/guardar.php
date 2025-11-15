<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

if ($_SESSION['usuario_rol'] != 'sysadmin') {
    $_SESSION['error'] = "No tienes permisos para esta acción.";
    header("Location: index.php");
    exit;
}

$cod_bodega = trim($_POST['cod_bodega'] ?? '');
$ciudad = trim($_POST['ciudad'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');

// validaciones
if (!ctype_alnum($cod_bodega)) {
    $errors[] = "El codigo de bodega es Alfanumerico";
} elseif (strlen($cod_bodega) != 10) {
    $errors[] = "El codigo de bodega debe tener exactamente 10 caracteres";
}

if (strlen($ciudad) < 4) {
    $errors[] = "El nombre de ciudad no puede ser tan corto";
}

if(empty($errors)) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bodegas WHERE cod_bodega = ?");
    $stmt->execute([$cod_bodega]);
    if ($stmt->fetchColumn() > 0) $errors[] = "El codigo de bodega ya existe";
}

if (!empty($errors)) {
    $_SESSION['form_data'] = $_POST;
    $_SESSION['error'] = $errors[0];
    header("Location: crear.php");
    exit;
}

$stmt = $pdo->prepare("INSERT INTO bodegas (cod_bodega, ciudad, direccion) VALUES (?, ?, ?)");
$stmt->execute([$cod_bodega, $ciudad, $direccion]);

header("Location: index.php");
exit;
?>
