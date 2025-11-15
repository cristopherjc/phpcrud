<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../auth/auth.php';
require_once '../../config/db.php';

if (!in_array($_SESSION['usuario_rol'], ['sysadmin', 'admin_bodega', 'empleado'])) {
    $_SESSION['error'] = "No tienes permiso para esta acción.";
    header("Location: index.php");
    exit;
}

// Validar ID
if (!isset($_GET['id'])) {
    die("ID de producto no especificado.");
}

$id = intval($_GET['id']);

// Obtener producto
$stmt = $pdo->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    $_SESSION['error'] = "Producto no encontrado.";
    header("Location: index.php");
    exit;
}

// Para empleado: no puede modificar costo, descripción ni stock mínimo
$soloLecturaEmpleado = ($_SESSION['usuario_rol'] === 'empleado');

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Editar Producto</h2>

    <form action="actualizar.php" method="POST" id="formEditar">

        <input type="hidden" name="id" value="<?= $producto['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control"
                   value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion"
                      class="form-control"
                      <?= $soloLecturaEmpleado ? 'readonly' : '' ?>><?= htmlspecialchars($producto['descripcion']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Precio compra</label>
            <input type="number" step="0.01" name="precio_compra"
                   class="form-control"
                   value="<?= $producto['precio_compra'] ?>"
                   <?= $soloLecturaEmpleado ? 'readonly' : '' ?>
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Precio venta</label>
            <input type="number" step="0.01" name="precio_venta"
                   class="form-control"
                   value="<?= $producto['precio_venta'] ?>" required>
            <small class="text-muted">
                * Si eres empleado, el precio no puede ser menor al 30% de ganancia (mínimo: 1.3 × precio compra)
            </small>
        </div>

        <div class="mb-3">
            <label class="form-label">Stock</label>
            <input type="number" name="stock"
                   class="form-control"
                   value="<?= $producto['stock'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Stock mínimo</label>
            <input type="number" name="stock_minimo"
                   class="form-control"
                   value="<?= $producto['stock_minimo'] ?>"
                   <?= $soloLecturaEmpleado ? 'readonly' : '' ?>
                   required>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
// Confirmación SweetAlert antes de enviar TODO
document.getElementById("formEditar").addEventListener("submit", function(e) {
    e.preventDefault();

    Swal.fire({
        title: "¿Guardar cambios?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sí, actualizar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit();
        }
    });
});
</script>

<?php if (isset($_SESSION['error'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: 'error',
    title: 'Error',
    text: '<?= $_SESSION["error"] ?>',
    confirmButtonText: 'Entendido'
});
</script>
<?php unset($_SESSION['error']); endif; ?>

</body>
</html>
