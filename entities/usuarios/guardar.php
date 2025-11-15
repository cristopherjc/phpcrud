<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

// acumulador de errores
$errors = [];

if ($_SESSION['usuario_rol'] != 'sysadmin') {
    $_SESSION['error'] = "No tienes permisos para esta acción.";
    header("Location: index.php");
    exit;
}

$cedula = trim($_POST['cedula'] ?? '');
$alias = trim($_POST['alias'] ?? '');
$nombres = trim($_POST['nombres'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$clave_plana = trim($_POST['clave'] ?? '');
$rol = $_POST['rol'];
$bodega_id = $_POST['bodega_id'] ?: null;

// VALIDACIONES
if (!ctype_digit($cedula)) {
    $errors[] = "La cédula solo debe contener números.";
} elseif (strlen($cedula) !== 10) {
    $errors[] = "La cédula debe tener exactamente 10 dígitos.";
}

if (strlen($alias) > 20) {
    $errors[] = "El alias no puede tener más de 20 caracteres.";
} elseif (strpos($alias, ' ') !== false) {
    $errors[] = "El alias no debe contener espacios.";
}

if ($clave_plana === '') {
    $errors[] = "La clave es obligatoria.";
} elseif (strlen($clave_plana) < 6) {
    $errors[] = "La clave debe tener al menos 6 caracteres.";
}

// si es rol sysadmin puede ir con bodega NULL
if ($rol !== 'sysadmin') {
    if (empty($bodega_id)) {
        $errors[] = "Debes seleccionar una bodega para este rol.";
    }
}

// Esto es para validar en la DB
// Solo se ejectura si no hay errores acumulados
if (empty($errors)) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE cedula = ?");
    $stmt->execute([$cedula]);
    if ($stmt->fetchColumn() > 0) $errors[] = "La cédula ya está registrada.";

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE alias = ?");
    $stmt->execute([$alias]);
    if ($stmt->fetchColumn() > 0) $errors[] = "El alias ya está en uso.";

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    if ($stmt->fetchColumn() > 0) $errors[] = "El correo ya está registrado.";
}

if (!empty($errors)) {
    // guardo el formulario
    $_SESSION['form_data'] = $_POST;
    // Aqui lanzamos el primer error (solo para que no se haga tan largo el sweetalert) 
    $_SESSION['error'] = $errors[0];
    header("Location: crear.php");
    exit;
}

// si no hay errores hasheamos la clave
$clave = password_hash($clave_plana, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO usuarios (cedula, alias, nombres, apellidos, correo, clave, rol, bodega_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$cedula, $alias, $nombres, $apellidos, $correo, $clave, $rol, $bodega_id]);

header("Location: index.php");
exit;
?>
