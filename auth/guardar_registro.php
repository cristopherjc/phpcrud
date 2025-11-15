<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "../config/db.php";

$errors = [];

$cedula = trim($_POST['cedula'] ?? '');
$alias = trim($_POST['alias'] ?? '');
$nombres = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$clave_plana = trim($_POST['clave'] ?? '');
$bodega_id = $_POST['bodega_id'] ?? '';

// VALIDACIONES
if (!ctype_digit($cedula)) {
    $errors[] = "La cédula debe contener solo números.";
} elseif (strlen($cedula) !== 10) {
    $errors[] = "La cédula debe tener exactamente 10 dígitos.";
}

if (strlen($alias) > 20) {
    $errors[] = "El alias no puede tener más de 20 caracteres.";
} elseif (strpos($alias, ' ') !== false) {
    $errors[] = "El alias no puede contener espacios.";
}

if (strlen($clave_plana) < 6) {
    $errors[] = "La contraseña debe tener mínimo 6 caracteres.";
}

if (empty($bodega_id)) {
    $errors[] = "Debes seleccionar una bodega.";
}

if (!empty($errors)) {
    $_SESSION['form_data'] = $_POST;
    $_SESSION['error'] = $errors[0];
    header("Location: signup.php");
    exit;
}

// Validar cédula existente
$stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE cedula = ?");
$stmt->execute([$cedula]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['form_data'] = $_POST;
    $_SESSION['error'] = "La cédula ya está registrada.";
    header("Location: signup.php");
    exit;
}

$clave = password_hash($clave_plana, PASSWORD_DEFAULT);

// Insertar nuevo empleado
$stmt = $pdo->prepare("
    INSERT INTO usuarios (cedula, alias, nombres, apellidos, correo, clave, rol, bodega_id)
    VALUES (?, ?, ?, ?, ?, ?, 'empleado', ?)
");
$stmt->execute([$cedula, $alias, $nombres, $apellidos, $correo, $clave, $bodega_id]);

$_SESSION['success'] = "Tu cuenta fue creada exitosamente. Ya puedes iniciar sesión.";
header("Location: signup.php");
exit;
