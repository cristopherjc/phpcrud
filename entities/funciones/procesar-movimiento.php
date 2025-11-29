<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

session_start();
$rol = $_SESSION['usuario_rol'] ?? null;
require '../../config/db.php'; // define $pdo
require __DIR__ . '/registrar-movimientos.php';

// Datos POST
$id_producto        = $_POST['id_producto'] ?? null; // <--- corregido
$tipo               = $_POST['tipo'] ?? null;
$cantidad           = $_POST['cantidad'] ?? null;
$comentario         = $_POST['comentario'] ?? '';
$id_usuario         = $_SESSION['usuario_id'] ?? null;
$id_bodega          = $_SESSION['usuario_bodega'] ?? null;
$id_bodega_destino  = $_POST['bodega_destino'] ?? null;

// Detectar bodega origen
if ($rol === 'sysadmin') {
    $id_bodega = $_POST['bodega_origen'] ?? null; // Sysadmin selecciona origen
    if (!$id_bodega) {
        echo json_encode(['success'=>false,'message'=>'Sysadmin debe seleccionar bodega origen']);
        exit;
    }
} else {
    $id_bodega = $_SESSION['usuario_bodega'] ?? null;
    if (!$id_bodega) {
        echo json_encode(['success'=>false,'message'=>'No tienes una bodega asignada']);
        exit;
    }
}

if(!$id_producto || !$tipo || !$cantidad || !$id_usuario){
    echo json_encode(['success'=>false,'message'=>'Faltan datos obligatorios']);
    exit;
}

try {
    if($tipo === 'traspaso'){
    if(!$id_bodega_destino){
        echo json_encode(['success'=>false,'message'=>'Debe seleccionar bodega destino']);
        exit;
    }

    // RESTAR stock en bodega origen
    $sql = "UPDATE productos SET stock = stock - :cantidad WHERE id=:id_producto AND id_bodega=:id_bodega";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':cantidad'=>$cantidad, ':id_producto'=>$id_producto, ':id_bodega'=>$id_bodega]);

    // Registrar salida
    registrarMovimiento($pdo, $id_producto, $id_usuario, $id_bodega, 'salida', $cantidad, "Traspaso a bodega $id_bodega_destino");

    // Verificar si el producto ya existe en bodega destino
    $sql = "SELECT id, stock FROM productos WHERE nombre=(SELECT nombre FROM productos WHERE id=:id_producto) AND id_bodega=:id_bodega_destino";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id_producto'=>$id_producto, ':id_bodega_destino'=>$id_bodega_destino]);
    $producto_destino = $stmt->fetch(PDO::FETCH_ASSOC);

    if($producto_destino){
        // Si existe, sumar stock
        $sql = "UPDATE productos SET stock = stock + :cantidad WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':cantidad'=>$cantidad, ':id'=>$producto_destino['id']]);
        $new_id = $producto_destino['id'];
    } else {
        // Si no existe, crear nuevo producto
        $sql = "INSERT INTO productos (id_bodega, id_proveedor, id_categoria, nombre, descripcion, precio_compra, precio_venta, stock, stock_minimo, created_at, updated_at)
                SELECT :id_bodega_destino, id_proveedor, id_categoria, nombre, descripcion, precio_compra, precio_venta, :cantidad, stock_minimo, NOW(), NOW()
                FROM productos WHERE id=:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_bodega_destino'=>$id_bodega_destino, ':cantidad'=>$cantidad, ':id'=>$id_producto]);
        $new_id = $pdo->lastInsertId();
    }

    // Registrar entrada en destino
    registrarMovimiento($pdo, $new_id, $id_usuario, $id_bodega_destino, 'entrada', $cantidad, "Traspaso desde bodega $id_bodega");
    } elseif ($tipo === 'entrada') {
        // SUMAR stock en bodega
        $sql = "UPDATE productos SET stock = stock + :cantidad WHERE id=:id AND id_bodega=:id_bodega";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':cantidad'=>$cantidad, ':id'=>$id_producto, ':id_bodega'=>$id_bodega]);

        // Registrar entrada
        registrarMovimiento($pdo, $id_producto, $id_usuario, $id_bodega, 'entrada', $cantidad, $comentario);

    } elseif ($tipo === 'salida') {
        // RESTAR stock en bodega
        $sql = "UPDATE productos SET stock = stock - :cantidad WHERE id=:id AND id_bodega=:id_bodega";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':cantidad'=>$cantidad, ':id'=>$id_producto, ':id_bodega'=>$id_bodega]);

        // Registrar salida
        registrarMovimiento($pdo, $id_producto, $id_usuario, $id_bodega, 'salida', $cantidad, $comentario);
    }


    echo json_encode(['success'=>true]);

} catch(Exception $e){
    echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
?>
