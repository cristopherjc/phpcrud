<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

// Solo sysadmin puede entrar
if ($_SESSION['usuario_rol'] !== 'sysadmin') {
    $_SESSION['error'] = "No tienes permiso para ver usuarios.";
    header("Location: ../../index.php");
    exit;
}

// Obtener todos los usuarios
$stmt = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body class="p-4">

<h2>Usuarios</h2>
<a href="crear.php" class="btn btn-primary mb-3">Crear Usuario</a>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Cédula</th>
        <th>Alias</th>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($usuarios as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= $u['cedula'] ?></td>
            <td><?= $u['alias'] ?></td>
            <td><?= $u['nombres'] . " " . $u['apellidos'] ?></td>
            <td><?= $u['correo'] ?></td>
            <td><?= $u['rol'] ?></td>
            <td>
                <a href="editar.php?id=<?= $u['id'] ?>" class="btn btn-warning btn-sm">Editar</a>

                <a href="asignar_bodegas.php?id=<?= $u['id'] ?>" 
                   class="btn btn-info btn-sm">Bodegas</a>

                <a href="eliminar.php?id=<?= $u['id'] ?>"
                   onclick="return confirm('¿Eliminar usuario?')"
                   class="btn btn-danger btn-sm">Eliminar</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
