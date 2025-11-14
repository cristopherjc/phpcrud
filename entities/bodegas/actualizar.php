<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

if ($_SESSION['usuario_rol'] != 'sysadmin') {
    $_SESSION['error'] = "No tienes permisos para esta acción.";
    header("Location: ./index.php");
    exit;
}

$id = $_POST['id'];
$cod_bodega = $_POST['cod_bodega'];
$ciudad = $_POST['ciudad'];
$direccion = $_POST['direccion'];

$stmt = $pdo->prepare("UPDATE bodegas SET cod_bodega = ?, ciudad = ?, direccion = ?, updated_at = NOW() WHERE id = ?");
$stmt->execute([$cod_bodega, $ciudad, $direccion, $id]);

header("Location: index.php");
exit;
?>
