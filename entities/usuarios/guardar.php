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

$cedula = $_POST['cedula'];
$alias = $_POST['alias'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$correo = $_POST['correo'];
$clave = password_hash($_POST['clave'], PASSWORD_DEFAULT);
$rol = $_POST['rol'];
$bodega_id = $_POST['bodega_id'] ?: null;

// si es rol sysadmin va con bodega null
if ($rol === "sysadmin") {
    $bodega_id = null;
}

$stmt = $pdo->prepare("
    INSERT INTO usuarios (cedula, alias, nombres, apellidos, correo, clave, rol, bodega_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$cedula, $alias, $nombres, $apellidos, $correo, $clave, $rol, $bodega_id]);

header("Location: index.php");
exit;
?>
