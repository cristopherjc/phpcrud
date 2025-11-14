<?php
// Mostrar errores
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once("./config/db.php");

// INSERTAR USUARIOS
$usuarios = [
    [
        "cedula" => "0102030405",
        "alias" => "sysadmin",
        "nombres" => "Juan",
        "apellidos" => "Paredes",
        "correo" => "sysadmin@demo.com",
        "clave" => password_hash("admin123", PASSWORD_DEFAULT),
        "rol" => "sysadmin"
    ],
    [
        "cedula" => "1112223334",
        "alias" => "bodega_pve",
        "nombres" => "Carlos",
        "apellidos" => "González",
        "correo" => "carlos@demo.com",
        "clave" => password_hash("clave123", PASSWORD_DEFAULT),
        "rol" => "admin_bodega"
    ],
    [
        "cedula" => "2223334445",
        "alias" => "empleado1",
        "nombres" => "Ana",
        "apellidos" => "Mendoza",
        "correo" => "ana@demo.com",
        "clave" => password_hash("empleado123", PASSWORD_DEFAULT),
        "rol" => "empleado"
    ],
    [
        "cedula" => "3334445556",
        "alias" => "empleado2",
        "nombres" => "Luis",
        "apellidos" => "Salazar",
        "correo" => "luis@demo.com",
        "clave" => password_hash("empleado123", PASSWORD_DEFAULT),
        "rol" => "empleado"
    ],
    [
        "cedula" => "4445556667",
        "alias" => "bodega_zrm",
        "nombres" => "María",
        "apellidos" => "Jaramillo",
        "correo" => "maria@demo.com",
        "clave" => password_hash("bodega123", PASSWORD_DEFAULT),
        "rol" => "admin_bodega"
    ]
];

$stmt = $pdo->prepare("
    INSERT INTO usuarios (cedula, alias, nombres, apellidos, correo, clave, rol)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

foreach ($usuarios as $u) {
    $stmt->execute([
        $u["cedula"],
        $u["alias"],
        $u["nombres"],
        $u["apellidos"],
        $u["correo"],
        $u["clave"],
        $u["rol"]
    ]);
}

echo "Usuarios insertados ✔<br>";

// INSERTAR BODEGAS
$bodegas = [
    ["BOD-PVE", "Portovelo", "Av. Loja y Sucre"],
    ["BOD-ZRM", "Zaruma", "Av. 9 de Octubre"],
    ["BOD-PNS", "Piñas", "Av. Carlos Lozada"]
];

$stmt = $pdo->prepare("
    INSERT INTO bodegas (cod_bodega, ciudad, direccion)
    VALUES (?, ?, ?)
");

foreach ($bodegas as $b) {
    $stmt->execute([$b[0], $b[1], $b[2]]);
}

echo "Bodegas insertadas ✔<br>";

// INSERTAR PROVEEDORES
$proveedores = [
    ["0999999999001", "Proveedor Machala S.A.", "0999999999", "Machala"],
    ["0888888888001", "Distribuidora Guayaquil", "0888888888", "Guayaquil"],
    ["0777777777001", "Ferretería Cuenca", "0777777777", "Cuenca"],
    ["0666666666001", "Proveedor Quito", "0666666666", "Quito"]
];

$stmt = $pdo->prepare("
    INSERT INTO proveedores (ruc, nombre, telefono, ciudad)
    VALUES (?, ?, ?, ?)
");

foreach ($proveedores as $p) {
    $stmt->execute([$p[0], $p[1], $p[2], $p[3]]);
}

echo "Proveedores insertados ✔<br>";


// ================================
// RELACIONAR USUARIOS CON BODEGAS
// ================================
/*
Vamos a suponer:
- sysadmin NO necesita bodega
- bodega_pve → Portovelo
- bodega_zrm → Zaruma
- empleados → Portovelo
*/

$relaciones = [
    [2, 1], // usuario bodega_pve con bodega Portovelo
    [5, 2], // bodega_zrm con Zaruma
    [3, 1], // empleado1 con Portovelo
    [4, 1]  // empleado2 con Portovelo
];

$stmt = $pdo->prepare("
    INSERT INTO usuarios_bodegas (id_usuario, id_bodega)
    VALUES (?, ?)
");

foreach ($relaciones as $r) {
    $stmt->execute([$r[0], $r[1]]);
}

echo "Relación usuarios-bodegas insertada ✔<br>";

echo "<br><strong>SEED COMPLETO ✔</strong>";
?>
