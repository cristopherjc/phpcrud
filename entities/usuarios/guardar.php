<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

if ($_SESSION['usuario_rol'] !== 'sysadmin') {
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

$stmt = $pdo->prepare("INSERT INTO usuarios 
(cedula, alias, nombres, apellidos, correo, clave, rol)
VALUES (?, ?, ?, ?, ?, ?, ?)");

$stmt->execute([$cedula, $alias, $nombres, $apellidos, $correo, $clave, $rol]);

header("Location: index.php");
exit;
