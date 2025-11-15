<?php
// Mostrar errores
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once("db.php");

// INSERTAR BODEGAS
$bodegas = [
    ["BODPORTO01","Portovelo","Calle Gonzalo Díaz"],
    ["BODZARUM01","Zaruma","Calle Reinaldo Espinoza"],
    ["BODPINAS01","Piñas","Av. Francisco Carrión"]
];

$stmt = $pdo->prepare("INSERT INTO bodegas (cod_bodega, ciudad, direccion) VALUES (?, ?, ?)");
foreach ($bodegas as $b) {
    $stmt->execute($b);
}

echo "Bodegas insertadas<br>";

$bodegaPorto = getId($pdo, "bodegas", "cod_bodega", "BODPORTO01");
$bodegaZaruma = getId($pdo, "bodegas", "cod_bodega", "BODZARUM01");
$bodegaPinas = getId($pdo, "bodegas", "cod_bodega", "BODPINAS01");

// INSERTAR USUARIOS
$usuarios = [
    ["1111111111","juparedes","Juan Esteban","Paredes Calle","juanpaca@demo.com",password_hash("juanpacalle", PASSWORD_DEFAULT),"admin_bodega",$bodegaPorto],
    ["2222222222","cargonza","Carlos Gonzalo","González Fares","carlosgonza@demo.com",password_hash("cargonzalez", PASSWORD_DEFAULT),"admin_bodega",$bodegaZaruma],
    ["3333333333","anaguilar","Ana Cristina","Aguilar Gonzalez","anaguilar@demo.com",password_hash("aguilar_ana", PASSWORD_DEFAULT),"admin_bodega",$bodegaPinas],
    ["4444444444","luisbenitez","Luis Alfredo","Benitez Salazar","luisbeni@demo.com",password_hash("empleado123", PASSWORD_DEFAULT),"empleado",$bodegaPorto],
    ["5555555555","mariacris","María Cristina","Jaramillo Carrión","mariajaram@demo.com",password_hash("bodega123", PASSWORD_DEFAULT),"empleado",$bodegaZaruma],
    ["6666666666","renatomino","José Renato","Miño Celi","josemino@demo.com",password_hash("josemino123", PASSWORD_DEFAULT),"empleado",$bodegaPinas]
];

$stmt = $pdo->prepare("INSERT INTO usuarios (cedula, alias, nombres, apellidos, correo, clave, rol, bodega_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($usuarios as $u) {
    $stmt->execute($u);
}

echo "Usuarios insertados<br>";

// INSERTAR PROVEEDORES
$proveedores = [
    ["0101010101010","Proveedor Machala","0111111111","Machala"],
    ["0202020202020","Distribuidora Guayaquil","0222222222","Guayaquil"],
    ["0303030303030","Ferretería Cuenca","0333333333","Cuenca"],
    ["0404040404040","Proveedor Quito","0444444444","Quito"],
    ["0505050505050","Matecons CHILE","0555555555","Cariamanga"],
    ["0606060606060","Proveedor Electricidad","0666666666","Quito"],
    ["0707070707070","Proveedor Peru","0777777777","Lima"],
    ["0808080808080","Fabrica Shenzhen","0888888888","Shenzhen"]
];

$stmt = $pdo->prepare("INSERT INTO proveedores (ruc, nombre, telefono, ciudad) VALUES (?, ?, ?, ?)");
foreach ($proveedores as $p) {
    $stmt->execute($p);
}

echo "Proveedores insertados<br>";

// INSERTAR CATEGORIAS
$categorias = [
    ["Herramientas manuales","Martillos, destornilladores, llaves, alicates y demás herramientas manuales"],
    ["Herramientas eléctricas","Taladros, sierras, lijadoras y otras herramientas eléctricas"],
    ["Tornillería y fijaciones","Tornillos, clavos, tuercas, pernos y sujetadores"],
    ["Pinturas y recubrimientos","Pinturas, barnices, esmaltes y brochas"],
    ["Materiales de construcción","Cemento, arena, ladrillos, bloques y adhesivos"],
    ["Fontanería y plomería","Tuberías, grifos, válvulas y accesorios de plomería"],
    ["Eléctricos e iluminación","Cables, enchufes, bombillas, lámparas y regletas"],
    ["Jardinería y exterior","Mangueras, regaderas, herramientas de jardín y fertilizantes"]
];

$stmt = $pdo->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
foreach ($categorias as $c) {
    $stmt->execute($c);
}

echo "Categorias insertadas<br>";

function getId($pdo, $table, $column, $value) {
    $stmt = $pdo->prepare("SELECT id FROM $table WHERE $column = ? LIMIT 1");
    $stmt->execute([$value]);
    return $stmt->fetchColumn();
}

// INSERTAR PRODUCTOS
$productos = [
    // Herramientas manuales
    ["BODPORTO01","0101010101010","Herramientas manuales","Martillo 16oz","Martillo de acero con mango de madera",5.00,8.50,50,5],
    ["BODZARUM01","0202020202020","Herramientas manuales","Destornillador plano 6mm","Destornillador de acero con mango ergonómico",1.50,3.00,100,10],
    ["BODPINAS01","0303030303030","Herramientas manuales","Llave inglesa 10\"","Llave ajustable de acero cromado",7.00,12.00,30,5],
    ["BODPORTO01","0101010101010","Herramientas manuales","Alicate universal 8\"","Alicate multiuso con recubrimiento antideslizante",4.00,7.50,40,5],
    ["BODZARUM01","0202020202020","Herramientas manuales","Cinta métrica 5m","Cinta métrica de acero con carcasa plástica",3.00,6.00,60,5],
    ["BODPINAS01","0303030303030","Herramientas manuales","Nivel de burbuja 60cm","Nivel de aluminio con burbujas precisas",6.00,10.50,25,3],
    ["BODPORTO01","0101010101010","Herramientas manuales","Juego de llaves Allen 9 piezas","Llaves Allen de acero resistente",8.00,15.00,20,2],

    // Herramientas eléctricas
    ["BODPORTO01","0202020202020","Herramientas eléctricas","Taladro eléctrico 500W","Taladro con brocas intercambiables",35.00,60.00,20,3],
    ["BODZARUM01","0202020202020","Herramientas eléctricas","Sierra circular 1200W","Sierra eléctrica para corte de madera",50.00,85.00,15,2],
    ["BODPINAS01","0202020202020","Herramientas eléctricas","Lijadora orbital","Lijadora eléctrica para acabado de superficies",25.00,40.00,25,5],
    ["BODPORTO01","0202020202020","Herramientas eléctricas","Atornillador inalámbrico 12V","Atornillador con batería recargable",40.00,70.00,10,2],
    ["BODZARUM01","0202020202020","Herramientas eléctricas","Rotomartillo 800W","Rotomartillo para perforaciones en concreto",80.00,120.00,8,1],

    // Tornillería y fijaciones
    ["BODPORTO01","0303030303030","Tornillería y fijaciones","Tornillo 5x50mm paquete 100","Tornillos de acero para madera",2.50,5.00,200,20],
    ["BODZARUM01","0303030303030","Tornillería y fijaciones","Clavo 3x50mm paquete 100","Clavos de acero galvanizado",1.80,3.50,200,20],
    ["BODPINAS01","0303030303030","Tornillería y fijaciones","Tuerca M8 paquete 50","Tuercas de acero inoxidable",3.00,6.00,150,15],
    ["BODPORTO01","0303030303030","Tornillería y fijaciones","Arandela M8 paquete 50","Arandelas de acero galvanizado",1.50,3.00,150,10],
    ["BODZARUM01","0303030303030","Tornillería y fijaciones","Taco de expansión 8mm paquete 50","Taco para fijaciones en paredes",4.00,7.50,100,10],

    // Pinturas y recubrimientos
    ["BODPORTO01","0404040404040","Pinturas y recubrimientos","Pintura acrílica 1L","Pintura blanca para interiores",8.00,15.00,50,5],
    ["BODZARUM01","0404040404040","Pinturas y recubrimientos","Brocha 2\"","Brocha para pintura acrílica",1.50,3.00,100,10],
    ["BODPINAS01","0404040404040","Pinturas y recubrimientos","Rodillo de pintura 15cm","Rodillo de espuma para pared",2.00,4.00,60,5],
    ["BODPORTO01","0404040404040","Pinturas y recubrimientos","Barniz transparente 1L","Barniz para madera",10.00,18.00,40,5],

    // Materiales de construcción
    ["BODPORTO01","0505050505050","Materiales de construcción","Cemento 50kg","Cemento Portland",6.00,10.00,100,10],
    ["BODZARUM01","0505050505050","Materiales de construcción","Arena 25kg","Arena para construcción",2.50,5.00,200,20],
    ["BODPINAS01","0505050505050","Materiales de construcción","Ladrillo 20x10x5","Ladrillo rojo para construcción",0.50,1.00,500,50],
    ["BODPORTO01","0505050505050","Materiales de construcción","Bloque de hormigón 40x20x20","Bloque de hormigón para muros",1.00,2.00,300,30],

    // Eléctricos e iluminación
    ["BODPORTO01","0606060606060","Eléctricos e iluminación","Bombilla LED 9W","Bombilla LED de bajo consumo",1.50,3.00,100,10],
    ["BODZARUM01","0606060606060","Eléctricos e iluminación","Regleta 4 tomas","Regleta eléctrica con protección",3.00,6.00,50,5],
    ["BODPINAS01","0606060606060","Eléctricos e iluminación","Cable eléctrico 3x1.5mm","Rollo de cable para instalaciones",15.00,25.00,40,5],
    ["BODPORTO01","0606060606060","Eléctricos e iluminación","Interruptor simple","Interruptor para pared",2.00,4.00,70,5],

    // Fontanería y plomería
    ["BODPORTO01","0707070707070","Fontanería y plomería","Grifo de cocina","Grifo de acero inoxidable",15.00,25.00,30,5],
    ["BODZARUM01","0707070707070","Fontanería y plomería","Tubo PVC 1\"","Tubo de PVC para plomería",2.00,4.00,100,10],
    ["BODPINAS01","0707070707070","Fontanería y plomería","Válvula de cierre 1/2\"","Válvula de acero inoxidable",5.00,9.50,40,5],
    ["BODPORTO01","0707070707070","Fontanería y plomería","Codo PVC 90° 1\"","Accesorio para tuberías de PVC",1.50,3.00,80,10],

    // Jardinería y exterior
    ["BODPORTO01","0808080808080","Jardinería y exterior","Manguera de jardín 20m","Manguera flexible de PVC resistente",12.00,22.00,25,3],
    ["BODZARUM01","0808080808080","Jardinería y exterior","Regadera de metal","Regadera para riego de jardín",5.00,9.50,40,5],
    ["BODPINAS01","0808080808080","Jardinería y exterior","Fertilizante 1kg","Fertilizante para césped y plantas",4.00,7.50,50,5],
    ["BODPORTO01","0808080808080","Jardinería y exterior","Tijera de podar","Tijera para corte de ramas pequeñas",6.00,12.00,30,3],
    ["BODZARUM01","0808080808080","Jardinería y exterior","Rastrillo de jardín","Rastrillo metálico para hojas y césped",8.00,15.00,20,2]
];

$stmt = $pdo->prepare("
    INSERT INTO productos 
    (id_bodega, id_proveedor, id_categoria, nombre, descripcion, precio_compra, precio_venta, stock, stock_minimo) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

foreach ($productos as $p) {
    $id_bodega = getId($pdo, "bodegas", "cod_bodega", $p[0]);
    $id_proveedor = getId($pdo, "proveedores", "ruc", $p[1]);
    $id_categoria = getId($pdo, "categorias", "nombre", $p[2]);
    
    $stmt->execute([$id_bodega,$id_proveedor,$id_categoria,$p[3],$p[4],$p[5],$p[6],$p[7],$p[8]]);
}

echo "Productos insertados<br>";
?>
