-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 15-11-2025 a las 17:05:47
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `crud_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bodegas`
--

CREATE TABLE `bodegas` (
  `id` int(11) NOT NULL,
  `cod_bodega` varchar(10) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bodegas`
--

INSERT INTO `bodegas` (`id`, `cod_bodega`, `ciudad`, `direccion`, `created_at`, `updated_at`) VALUES
(20, 'BODPORTO01', 'Portovelo', 'Calle Gonzalo Díaz', '2025-11-15 10:46:11', '2025-11-15 10:46:11'),
(21, 'BODZARUM01', 'Zaruma', 'Calle Reinaldo Espinoza', '2025-11-15 10:46:11', '2025-11-15 10:46:11'),
(22, 'BODPINAS01', 'Piñas', 'Av. Francisco Carrión', '2025-11-15 10:46:11', '2025-11-15 10:46:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(15, 'Herramientas manuales', 'Martillos, destornilladores, llaves, alicates y demás herramientas manuales'),
(16, 'Herramientas eléctricas', 'Taladros, sierras, lijadoras y otras herramientas eléctricas'),
(17, 'Tornillería y fijaciones', 'Tornillos, clavos, tuercas, pernos y sujetadores'),
(18, 'Pinturas y recubrimientos', 'Pinturas, barnices, esmaltes y brochas'),
(19, 'Materiales de construcción', 'Cemento, arena, ladrillos, bloques y adhesivos'),
(20, 'Fontanería y plomería', 'Tuberías, grifos, válvulas y accesorios de plomería'),
(21, 'Eléctricos e iluminación', 'Cables, enchufes, bombillas, lámparas y regletas'),
(22, 'Jardinería y exterior', 'Mangueras, regaderas, herramientas de jardín y fertilizantes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_log`
--

CREATE TABLE `movimientos_log` (
  `id` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_bodega` int(11) NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `comentario` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `id_bodega` int(11) NOT NULL,
  `id_proveedor` int(11) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `id_bodega`, `id_proveedor`, `id_categoria`, `nombre`, `descripcion`, `precio_compra`, `precio_venta`, `stock`, `stock_minimo`, `created_at`, `updated_at`) VALUES
(42, 20, 17, 15, 'Martillo 16oz', 'Martillo de acero con mango de madera', 5.00, 8.50, 50, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(43, 21, 18, 15, 'Destornillador plano 6mm', 'Destornillador de acero con mango ergonómico', 1.50, 3.00, 100, 10, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(44, 22, 19, 15, 'Llave inglesa 10\"', 'Llave ajustable de acero cromado', 7.00, 12.00, 30, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(45, 20, 17, 15, 'Alicate universal 8\"', 'Alicate multiuso con recubrimiento antideslizante', 4.00, 7.50, 40, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(46, 21, 18, 15, 'Cinta métrica 5m', 'Cinta métrica de acero con carcasa plástica', 3.00, 6.00, 60, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(47, 22, 19, 15, 'Nivel de burbuja 60cm', 'Nivel de aluminio con burbujas precisas', 6.00, 10.50, 25, 3, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(48, 20, 17, 15, 'Juego de llaves Allen 9 piezas', 'Llaves Allen de acero resistente', 8.00, 15.00, 20, 2, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(49, 20, 18, 16, 'Taladro eléctrico 500W', 'Taladro con brocas intercambiables', 35.00, 60.00, 20, 3, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(50, 21, 18, 16, 'Sierra circular 1200W', 'Sierra eléctrica para corte de madera', 50.00, 85.00, 15, 2, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(51, 22, 18, 16, 'Lijadora orbital', 'Lijadora eléctrica para acabado de superficies', 25.00, 40.00, 25, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(52, 20, 18, 16, 'Atornillador inalámbrico 12V', 'Atornillador con batería recargable', 40.00, 70.00, 10, 2, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(53, 21, 18, 16, 'Rotomartillo 800W', 'Rotomartillo para perforaciones en concreto', 80.00, 120.00, 8, 1, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(54, 20, 19, 17, 'Tornillo 5x50mm paquete 100', 'Tornillos de acero para madera', 2.50, 5.00, 200, 20, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(55, 21, 19, 17, 'Clavo 3x50mm paquete 100', 'Clavos de acero galvanizado', 1.80, 3.50, 200, 20, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(56, 22, 19, 17, 'Tuerca M8 paquete 50', 'Tuercas de acero inoxidable', 3.00, 6.00, 150, 15, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(57, 20, 19, 17, 'Arandela M8 paquete 50', 'Arandelas de acero galvanizado', 1.50, 3.00, 150, 10, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(58, 21, 19, 17, 'Taco de expansión 8mm paquete 50', 'Taco para fijaciones en paredes', 4.00, 7.50, 100, 10, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(59, 20, 20, 18, 'Pintura acrílica 1L', 'Pintura blanca para interiores', 8.00, 15.00, 50, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(60, 21, 20, 18, 'Brocha 2\"', 'Brocha para pintura acrílica', 1.50, 3.00, 100, 10, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(61, 22, 20, 18, 'Rodillo de pintura 15cm', 'Rodillo de espuma para pared', 2.00, 4.00, 60, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(62, 20, 20, 18, 'Barniz transparente 1L', 'Barniz para madera', 10.00, 18.00, 40, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(63, 20, 21, 19, 'Cemento 50kg', 'Cemento Portland', 6.00, 10.00, 100, 10, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(64, 21, 21, 19, 'Arena 25kg', 'Arena para construcción', 2.50, 5.00, 200, 20, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(65, 22, 21, 19, 'Ladrillo 20x10x5', 'Ladrillo rojo para construcción', 0.50, 1.00, 500, 50, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(66, 20, 21, 19, 'Bloque de hormigón 40x20x20', 'Bloque de hormigón para muros', 1.00, 2.00, 300, 30, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(67, 20, 22, 21, 'Bombilla LED 9W', 'Bombilla LED de bajo consumo', 1.50, 3.00, 100, 10, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(68, 21, 22, 21, 'Regleta 4 tomas', 'Regleta eléctrica con protección', 3.00, 6.00, 50, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(69, 22, 22, 21, 'Cable eléctrico 3x1.5mm', 'Rollo de cable para instalaciones', 15.00, 25.00, 40, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(70, 20, 22, 21, 'Interruptor simple', 'Interruptor para pared', 2.00, 4.00, 70, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(71, 20, 23, 20, 'Grifo de cocina', 'Grifo de acero inoxidable', 15.00, 25.00, 30, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(72, 21, 23, 20, 'Tubo PVC 1\"', 'Tubo de PVC para plomería', 2.00, 4.00, 100, 10, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(73, 22, 23, 20, 'Válvula de cierre 1/2\"', 'Válvula de acero inoxidable', 5.00, 9.50, 40, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(74, 20, 23, 20, 'Codo PVC 90° 1\"', 'Accesorio para tuberías de PVC', 1.50, 3.00, 80, 10, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(75, 20, 24, 22, 'Manguera de jardín 20m', 'Manguera flexible de PVC resistente', 12.00, 22.00, 25, 3, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(76, 21, 24, 22, 'Regadera de metal', 'Regadera para riego de jardín', 5.00, 9.50, 40, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(77, 22, 24, 22, 'Fertilizante 1kg', 'Fertilizante para césped y plantas', 4.00, 7.50, 50, 5, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(78, 20, 24, 22, 'Tijera de podar', 'Tijera para corte de ramas pequeñas', 6.00, 12.00, 30, 3, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(79, 21, 24, 22, 'Rastrillo de jardín', 'Rastrillo metálico para hojas y césped', 8.00, 15.00, 20, 2, '2025-11-15 10:46:12', '2025-11-15 10:46:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `ruc` varchar(13) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `ruc`, `nombre`, `telefono`, `ciudad`, `created_at`, `updated_at`) VALUES
(17, '0101010101010', 'Proveedor Machala', '0111111111', 'Machala', '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(18, '0202020202020', 'Distribuidora Guayaquil', '0222222222', 'Guayaquil', '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(19, '0303030303030', 'Ferretería Cuenca', '0333333333', 'Cuenca', '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(20, '0404040404040', 'Proveedor Quito', '0444444444', 'Quito', '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(21, '0505050505050', 'Matecons CHILE', '0555555555', 'Cariamanga', '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(22, '0606060606060', 'Proveedor Electricidad', '0666666666', 'Quito', '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(23, '0707070707070', 'Proveedor Peru', '0777777777', 'Lima', '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(24, '0808080808080', 'Fabrica Shenzhen', '0888888888', 'Shenzhen', '2025-11-15 10:46:12', '2025-11-15 10:46:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `cedula` varchar(10) NOT NULL,
  `alias` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` enum('sysadmin','admin_bodega','empleado') NOT NULL DEFAULT 'empleado',
  `bodega_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `cedula`, `alias`, `nombres`, `apellidos`, `correo`, `clave`, `rol`, `bodega_id`, `created_at`, `updated_at`) VALUES
(30, '0750936668', 'cljaramillo', 'Cristopher Leonardo', 'Jaramillo Cumbicos', 'cljaramillo9@utpl.edu.ec', '$2y$10$Oa6NwH4zCwPs6v5HGmkKHubMtXLlziiYIvQP.mQUI8cBRmbHnXaDW', 'sysadmin', NULL, '2025-11-15 10:40:41', '2025-11-15 10:40:41'),
(31, '1111111111', 'juparedes', 'Juan Esteban', 'Paredes Calle', 'juanpaca@demo.com', '$2y$10$MUeSpTN3l//Dfk6/SJuU8u4i/o4LUhcyK2k4b5umuVw5mQyrlH7d6', 'admin_bodega', 20, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(32, '2222222222', 'cargonza', 'Carlos Gonzalo', 'González Fares', 'carlosgonza@demo.com', '$2y$10$Fhn6Or7TF/G3N0AQqd31QOM3WPi37wjDSJiTTIH/pnO6jTVpPooRy', 'admin_bodega', 21, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(33, '3333333333', 'anaguilar', 'Ana Cristina', 'Aguilar Gonzalez', 'anaguilar@demo.com', '$2y$10$UZCbfZPfx32QYdKdcjXm.ezlB6HYQNcOITKUvg0jnct7gs8q6J6S.', 'admin_bodega', 22, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(34, '4444444444', 'luisbenitez', 'Luis Alfredo', 'Benitez Salazar', 'luisbeni@demo.com', '$2y$10$cxWa6/vpmssIe6wJMKbCI.ABatQ4/ai.7e7roU5SsGpLFfLHfbDTG', 'empleado', 20, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(35, '5555555555', 'mariacris', 'María Cristina', 'Jaramillo Carrión', 'mariajaram@demo.com', '$2y$10$sV5fDIE6lywvQOn9A8lxwONBw/cQwQPjakwo0Pa.A3kKowZd/Iynq', 'empleado', 21, '2025-11-15 10:46:12', '2025-11-15 10:46:12'),
(36, '6666666666', 'renatomino', 'José Renato', 'Miño Celi', 'josemino@demo.com', '$2y$10$yO/JTOphIXPVOWGCCw4GZOWS/30dEHittsVgjc29yZ1M2PoPYqGUK', 'empleado', 22, '2025-11-15 10:46:12', '2025-11-15 10:46:12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bodegas`
--
ALTER TABLE `bodegas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cod_bodega` (`cod_bodega`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `movimientos_log`
--
ALTER TABLE `movimientos_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_bodega` (`id_bodega`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_bodega` (`id_bodega`),
  ADD KEY `id_proveedor` (`id_proveedor`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruc` (`ruc`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `alias` (`alias`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `fk_usuario_bodega` (`bodega_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bodegas`
--
ALTER TABLE `bodegas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `movimientos_log`
--
ALTER TABLE `movimientos_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `movimientos_log`
--
ALTER TABLE `movimientos_log`
  ADD CONSTRAINT `movimientos_log_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `movimientos_log_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `movimientos_log_ibfk_3` FOREIGN KEY (`id_bodega`) REFERENCES `bodegas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_bodega`) REFERENCES `bodegas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuario_bodega` FOREIGN KEY (`bodega_id`) REFERENCES `bodegas` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
