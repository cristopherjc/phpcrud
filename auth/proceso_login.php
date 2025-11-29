<?php 
// ERRORES DEBUG
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("../config/db.php");
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header("Location: login.php");
  exit;
}
$alias = $_POST['alias'];
$clave = $_POST['clave'];

$stmp = $pdo->prepare("SELECT * FROM usuarios WHERE alias = ?");
$stmp->execute([$alias]);
$usuario = $stmp->fetch();

if (!$usuario) {
  $_SESSION['error'] = "Usuario no encontrado";
  header("Location: login.php");
  exit;
}

if (!password_verify($clave, $usuario['clave'])) {
  $_SESSION['error'] = "Clave incorrecta";
  header("Location: login.php");
  exit;
}

$_SESSION['usuario_id'] = $usuario['id'];
$_SESSION['usuario_alias'] = $usuario['alias'];
$_SESSION['usuario_rol'] = $usuario['rol'];
//tuve que agregar el id de bodega para gestionar permisos
$_SESSION['usuario_bodega'] = $usuario['bodega_id'];
$_SESSION['usuario_nombres'] = $usuario['nombres'];
$_SESSION['usuario_apellidos'] = $usuario['apellidos'];
header("Location: ../index.php");
exit;
?>