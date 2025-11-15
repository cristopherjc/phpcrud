<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

$errors = [];

if ($_SESSION['usuario_rol'] !== 'sysadmin') {
    $_SESSION['error'] = "No tienes permiso para esta acción.";
    header("Location: index.php");
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

// validaciones
if (strlen($nombre) < 4) {
    $errors[] = "El nombre de la categoria no puede ser tan corto";
}

if (!empty($errors)) {
    $_SESSION['form_data'] = $_POST;
    $_SESSION['error'] = $errors[0];
    header("Location: crear.php");
    exit;
}

$stmt = $pdo->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
$stmt->execute([$nombre, $descripcion]);

header("Location: index.php");
exit;
?>
