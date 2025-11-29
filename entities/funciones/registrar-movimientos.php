<?php
function registrarMovimiento($pdo, $id_producto, $id_usuario, $id_bodega, $tipo, $cantidad, $comentario = null) {
    $sql = "INSERT INTO movimientos_log 
            (id_producto, id_usuario, id_bodega, tipo, cantidad, comentario, fecha) 
            VALUES (:id_producto, :id_usuario, :id_bodega, :tipo, :cantidad, :comentario, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id_producto' => $id_producto,
        ':id_usuario' => $id_usuario,
        ':id_bodega' => $id_bodega,
        ':tipo' => $tipo,
        ':cantidad' => $cantidad,
        ':comentario' => $comentario
    ]);
}
?>
