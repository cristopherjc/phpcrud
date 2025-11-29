<?php
// DEBUG
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../../auth/auth.php";
require_once "../../config/db.php";

$id_usuario = $_SESSION['usuario_id'];
$rol = $_SESSION['usuario_rol'];
$bodega_id = null;
if ($rol !== 'sysadmin') {
    if (!isset($_SESSION['usuario_bodega'])) {
        die("Error: No tienes una bodega asignada.");
    }
    $bodega_id = $_SESSION['usuario_bodega'];
}

// Traer productos
if ($rol === 'sysadmin') {
    $stmt = $pdo->query("
        SELECT p.*, b.ciudad, pr.nombre AS proveedor
        FROM productos p
        LEFT JOIN bodegas b ON p.id_bodega = b.id
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id
        ORDER BY p.id DESC
    ");
} else {
    $stmt = $pdo->prepare("
        SELECT p.*, b.ciudad, pr.nombre AS proveedor
        FROM productos p
        LEFT JOIN bodegas b ON p.id_bodega = b.id
        LEFT JOIN proveedores pr ON p.id_proveedor = pr.id
        WHERE p.id_bodega = ?
        ORDER BY p.id DESC
    ");
    $stmt->execute([$bodega_id]);
}
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traer todas las bodegas para el select
$bodegas = $pdo->query("SELECT id, ciudad FROM bodegas ORDER BY ciudad")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="p-4 bg-light">
<div class="container">
    <h2>Productos</h2>
    <a href="../../index.php" class="btn btn-danger mb-3">Dashboard</a>
    <?php if ($rol !== 'empleado'): ?>
        <a href="crear.php" class="btn btn-primary mb-3">Crear Producto</a>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Bodega</th>
            <th>Proveedor</th>
            <th>Precio Compra</th>
            <th>Precio Venta</th>
            <th>Stock</th>
            <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($productos as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['nombre'] ?></td>
            <td><?= $p['ciudad'] ?></td>
            <td><?= $p['proveedor'] ?></td>
            <td>$<?= $p['precio_compra'] ?></td>
            <td>$<?= $p['precio_venta'] ?></td>
            <td><?= $p['stock'] ?></td>
            <td>
                <button class="btn btn-warning btn-sm" 
                    onclick="prepararModal(<?= $p['id'] ?>, '<?= $p['nombre'] ?>', <?= $p['stock'] ?>)">
                    Registrar Movimiento
                </button>

                <?php if ($rol !== 'empleado'): ?>
                    <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                    <button class="btn btn-danger btn-sm" onclick="eliminar(<?= $p['id'] ?>)">Eliminar</button>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="modalMovimiento" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitulo">Registrar Movimiento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form id="formMovimiento">
          <input type="hidden" name="id_producto" id="id_producto">

          <div class="mb-3">
            <label for="tipo">Tipo:</label>
            <select name="tipo" id="tipo" class="form-select" required>
              <option value="entrada">Entrada</option>
              <option value="salida">Salida</option>
              <option value="traspaso">Traspaso</option>
            </select>
          </div>

          <div class="mb-3" id="bodegaDestinoContainer" style="display:none;">
            <label for="bodega_destino">Bodega destino:</label>
            <select name="bodega_destino" id="bodega_destino" class="form-select">
              <?php foreach($bodegas as $b): ?>
                <option value="<?= $b['id'] ?>"><?= $b['ciudad'] ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" required>
          </div>

          <div class="mb-3">
            <label for="comentario">Comentario:</label>
            <input type="text" name="comentario" id="comentario" class="form-control">
          </div>

          <button type="submit" class="btn btn-success">Registrar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Mostrar/ocultar bodega destino
document.getElementById('tipo').addEventListener('change', function() {
    document.getElementById('bodegaDestinoContainer').style.display = (this.value==='traspaso') ? 'block' : 'none';
});

function prepararModal(id, nombre, stock){
    document.getElementById('id_producto').value = id;
    document.getElementById('modalTitulo').innerText = `Registrar movimiento: ${nombre} (Stock actual: ${stock})`;
    document.getElementById('tipo').value = 'entrada';
    document.getElementById('cantidad').value = '';
    document.getElementById('comentario').value = '';
    document.getElementById('bodegaDestinoContainer').style.display = 'none';
    let modal = new bootstrap.Modal(document.getElementById('modalMovimiento'));
    modal.show();
}

function eliminar(id) {
    Swal.fire({
        title: 'Eliminar producto?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Eliminar'
    }).then((res)=>{
        if(res.isConfirmed){
            window.location.href = "eliminar.php?id="+id;
        }
    });
}

// Enviar formulario vía AJAX
document.getElementById('formMovimiento').addEventListener('submit', function(e){
    e.preventDefault();
    let formData = new FormData(this);
    // debug
    for(let pair of formData.entries()) {
        console.log(pair[0]+ ': '+ pair[1]);
    }
    fetch('../funciones/procesar-movimiento.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            alert('Movimiento registrado!');
            let modalEl = document.getElementById('modalMovimiento');
            let modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(err => alert('Error en la solicitud'));
});
</script>
</body>
</html>
