<?php
// Mostrar errores
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once("./config/db.php");

// INSERTAR USUARIOS
$usuarios = [
    [
        "cedula" => "1111111111",
        "alias" => "juparedes",
        "nombres" => "Juan Esteban",
        "apellidos" => "Paredes Calle",
        "correo" => "juanpaca@demo.com",
        "clave" => password_hash("juanpacalle", PASSWORD_DEFAULT),
        "rol" => "admin_bodega"
    ],
    [
        "cedula" => "2222222222",
        "alias" => "cargonza",
        "nombres" => "Carlos Gonzalo",
        "apellidos" => "González Fares",
        "correo" => "carlosgonza@demo.com",
        "clave" => password_hash("cargonzalez", PASSWORD_DEFAULT),
        "rol" => "admin_bodega"
    ],
    [
        "cedula" => "3333333333",
        "alias" => "anaguilar",
        "nombres" => "Ana Cristina",
        "apellidos" => "Aguilar Gonzalez",
        "correo" => "anaguilar@demo.com",
        "clave" => password_hash("aguilar_ana", PASSWORD_DEFAULT),
        "rol" => "admin_bodega"
    ],
    [
        "cedula" => "4444444444",
        "alias" => "luisbenitez",
        "nombres" => "Luis Alfredo",
        "apellidos" => "Benitez Salazar",
        "correo" => "luisbeni@demo.com",
        "clave" => password_hash("empleado123", PASSWORD_DEFAULT),
        "rol" => "empleado"
    ],
    [
        "cedula" => "5555555555",
        "alias" => "mariacris",
        "nombres" => "María Cristina",
        "apellidos" => "Jaramillo Carrión",
        "correo" => "mariajaram@demo.com",
        "clave" => password_hash("bodega123", PASSWORD_DEFAULT),
        "rol" => "empleado"
    ],
    [
        "cedula" => "6666666666",
        "alias" => "renatomino",
        "nombres" => "José Renato",
        "apellidos" => "Miño Celi",
        "correo" => "josemino@demo.com",
        "clave" => password_hash("josemino123", PASSWORD_DEFAULT),
        "rol" => "empleado"
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

echo "Usuarios insertados";

// INSERTAR BODEGAS
$bodegas = [
    ["BODPOL01", "Portovelo", "Calle Gonzalo Díaz"],
    ["BODZAR01", "Zaruma", "Calle Reinaldo Espinoza"],
    ["BODPIN01", "Piñas", "Av. Francisco Carrión"]
];

$stmt = $pdo->prepare("
    INSERT INTO bodegas (cod_bodega, ciudad, direccion)
    VALUES (?, ?, ?)
");

foreach ($bodegas as $b) {
    $stmt->execute([$b[0], $b[1], $b[2]]);
}

echo "Bodegas insertadas";

// INSERTAR PROVEEDORES
$proveedores = [
    ["0101010101010", "Proveedor Machala", "0111111111111", "Machala"],
    ["0202020202020", "Distribuidora Guayaquil", "0222222222222", "Guayaquil"],
    ["0303030303030", "Ferretería Cuenca", "0333333333333", "Cuenca"],
    ["0404040404040", "Proveedor Quito", "0444444444444", "Quito"]
];

$stmt = $pdo->prepare("
    INSERT INTO proveedores (ruc, nombre, telefono, ciudad)
    VALUES (?, ?, ?, ?)
");

foreach ($proveedores as $p) {
    $stmt->execute([$p[0], $p[1], $p[2], $p[3]]);
}

echo "Proveedores insertados";

echo "<br><strong>SEED COMPLETO ✔</strong>";
?>
