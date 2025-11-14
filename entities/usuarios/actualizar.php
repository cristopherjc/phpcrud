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
$cedula = $_POST['cedula'];
$alias = $_POST['alias'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$correo = $_POST['correo'];
$clave = $_POST['clave'];
$rol = $_POST['rol'];
$bodega_id = $_POST['bodega_id'] ?: null;

if ($rol === 'sysadmin') {
    $bodega_id = null;
}

if (empty($clave)) {
    $sql = "
        UPDATE usuarios 
        SET cedula = ?, alias = ?, nombres = ?, apellidos = ?, correo = ?, rol = ?, bodega_id = ?, updated_at = NOW()
        WHERE id = ?";
    $params = [$cedula, $alias, $nombres, $apellidos, $correo, $rol, $bodega_id, $id];
} else {
    $clave_hash = password_hash($clave, PASSWORD_DEFAULT);
    $sql = "
        UPDATE usuarios 
        SET cedula = ?, alias = ?, nombres = ?, apellidos = ?, correo = ?, clave = ?, rol = ?, bodega_id = ?, updated_at = NOW()
        WHERE id = ?";
    $params = [$cedula, $alias, $nombres, $apellidos, $correo, $clave_hash, $rol, $bodega_id, $id];
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

header("Location: index.php");
exit;
?>
