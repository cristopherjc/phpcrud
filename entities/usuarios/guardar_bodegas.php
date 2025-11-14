<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

if ($_SESSION['usuario_rol'] !== 'sysadmin') {
    header("Location: ../../index.php");
    exit;
}

$idUsuario = $_POST['id_usuario'];
$bodegas = $_POST['bodegas'] ?? [];

// Primero borramos todo
$pdo->prepare("DELETE FROM usuarios_bodegas WHERE id_usuario=?")
    ->execute([$idUsuario]);

// Insertamos lo nuevo
$insert = $pdo->prepare("INSERT INTO usuarios_bodegas (id_usuario, id_bodega) VALUES (?, ?)");

foreach ($bodegas as $idBodega) {
    $insert->execute([$idUsuario, $idBodega]);
}

header("Location: index.php");
exit;
