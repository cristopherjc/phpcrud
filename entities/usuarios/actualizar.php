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
    $_SESSION['error'] = "ID de usuario no recibido.";
    header("Location: index.php");
    exit;
}

$id = $_POST['id'];
$cedula = $_POST['cedula'];
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

// si es rol sysadmin puede ir con bodega NULL
if ($rol !== 'sysadmin') {
    if (empty($bodega_id)) {
        $errors[] = "Debes seleccionar una bodega para este rol.";
    }
} else {
    $bodega_id = null;
}

if (!empty($errors)) {
    // Aqui lanzamos el primer error (solo para que no se haga tan largo el sweetalert) 
    $_SESSION['error'] = $errors[0];
    header("Location: editar.php");
    exit;
}

if (empty($clave_plana)) {
    $sql = "
        UPDATE usuarios 
        SET cedula = ?, alias = ?, nombres = ?, apellidos = ?, correo = ?, rol = ?, bodega_id = ?, updated_at = NOW()
        WHERE id = ?";
    $params = [$cedula, $alias, $nombres, $apellidos, $correo, $rol, $bodega_id, $id];
} else {
    if (strlen($clave_plana) < 6) {
        $_SESSION['error'] = "La clave no puede tener menos de 6 caracteres";
        header("Location: editar.php");
        exit;
    }
    $clave = password_hash($clave_plana, PASSWORD_DEFAULT);
    $sql = "
        UPDATE usuarios 
        SET cedula = ?, alias = ?, nombres = ?, apellidos = ?, correo = ?, clave = ?, rol = ?, bodega_id = ?, updated_at = NOW()
        WHERE id = ?";
    $params = [$cedula, $alias, $nombres, $apellidos, $correo, $clave, $rol, $bodega_id, $id];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

header("Location: index.php");
exit;
?>
