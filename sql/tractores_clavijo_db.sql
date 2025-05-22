-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:33065
-- Tiempo de generación: 22-05-2025 a las 21:21:01
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tractores_clavijo_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auth_tokens`
--

CREATE TABLE `auth_tokens` (
  `id_auth_token` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashed_validator` varchar(255) NOT NULL,
  `fecha_expiracion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito_item` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `id_sesion_invitado` varchar(255) DEFAULT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario_en_carrito` decimal(10,2) NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion_calle` varchar(255) DEFAULT NULL,
  `direccion_numero` varchar(10) DEFAULT NULL,
  `direccion_ciudad` varchar(100) DEFAULT NULL,
  `direccion_provincia` varchar(100) DEFAULT NULL,
  `direccion_codigo_postal` varchar(10) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apellido`, `email`, `password`, `telefono`, `direccion_calle`, `direccion_numero`, `direccion_ciudad`, `direccion_provincia`, `direccion_codigo_postal`, `fecha_registro`, `activo`) VALUES
(1, 'Axel', 'Perez', 'axelperez164623@gmail.com', '$2y$10$UPKjFBkYnzcyV2Riq6oJC.Ac9MQk/6rovzEQZmbRNAsW.baChCtAK', NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-22 19:17:56', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultas_contacto`
--

CREATE TABLE `consultas_contacto` (
  `id_consulta` int(11) NOT NULL,
  `nombre_completo` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `asunto` varchar(255) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_origen` varchar(45) DEFAULT NULL,
  `estado_consulta` enum('Pendiente','Respondida','Cerrada','Spam') NOT NULL DEFAULT 'Pendiente',
  `fecha_respuesta` timestamp NULL DEFAULT NULL,
  `notas_internas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `consultas_contacto`
--

INSERT INTO `consultas_contacto` (`id_consulta`, `nombre_completo`, `email`, `telefono`, `asunto`, `mensaje`, `fecha_envio`, `ip_origen`, `estado_consulta`, `fecha_respuesta`, `notas_internas`) VALUES
(1, 'Axel Perez', 'axelpere164623@gmail.com', '2634322151', 'Mi tractor explotó', 'Hola', '2025-05-21 03:16:17', '::1', 'Pendiente', NULL, NULL),
(2, 'Axel Perez', 'axelpere164623@gmail.com', '2634322151', 'Mi tractor explotó', 'Hola', '2025-05-22 02:18:06', '::1', 'Pendiente', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id_detalle_pedido` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `nombre_producto_en_pedido` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario_en_pedido` decimal(10,2) NOT NULL,
  `atributos_seleccionados_en_pedido` text DEFAULT NULL,
  `subtotal_linea` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id_reset_token` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `selector` varchar(255) NOT NULL,
  `hashed_token` varchar(255) NOT NULL,
  `fecha_expiracion` datetime NOT NULL,
  `utilizado` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`id_reset_token`, `id_cliente`, `email`, `selector`, `hashed_token`, `fecha_expiracion`, `utilizado`) VALUES
(1, 1, 'axelperez164623@gmail.com', '135cb527a7f3f5f08a064c2d15963df8', '$2y$10$0GcdwmXN7.CSLPU0bHE1L.GN28TlWOI1fnqnOeRXJXeEmvjle2E1W', '2025-05-22 07:36:30', 0),
(2, 1, 'axelperez164623@gmail.com', '159e75f80d3ae179e1a02219fcece431', '$2y$10$NeFapWtDQ4HdiIBKEFfD4.UwCdM8kToRc/.oMLD.4tFuhCUiXYy5m', '2025-05-22 07:39:58', 0),
(3, 1, 'axelperez164623@gmail.com', '2cc6dcb24e91874e2c5a321076371411', '$2y$10$Ai8tbGNMeRlozVdbPk/1G.ZelssYVvCExX0syfOE61MIsZ0BZFhny', '2025-05-22 07:42:08', 0),
(4, 1, 'axelperez164623@gmail.com', '49272824da40b4b54d251b75fdb65532', '$2y$10$k5GGP.tgyzCT9qiwhTHcC.AzBseRD/DGdLQcR2BnmT2SXFt5UHdLS', '2025-05-22 07:51:07', 0),
(5, 1, 'axelperez164623@gmail.com', '15cb68bc770981809033a51daaf79002', '$2y$10$wTD/VbYTw1WX/NFZzGBIze6D4VMbgymInMtBIP/Inglyyhe7HE.6O', '2025-05-22 08:00:37', 0),
(6, 1, 'axelperez164623@gmail.com', '2259519c66f144b545bab9921c7485d3', '$2y$10$Yh6FOyVho2CI.i1sv0HnY.O9fd/2WwPHBNFGzBum8WKEv5p07sIeK', '2025-05-22 08:20:06', 1),
(7, 1, 'axelperez164623@gmail.com', '813e25e7d16ef22940a1cd89f02ae03d', '$2y$10$Bj6jQYW2WXdT.diFnRmYNujhcWzyAmsVgZAKHjBusMxtlqQ1CU4nm', '2025-05-22 08:28:06', 1),
(8, 1, 'axelperez164623@gmail.com', '30778c3f6fe503043c8d012b7d1f2bca', '$2y$10$1JJfU.j8RJ59SdLNYoq7U.WAdx8qXKQcMhHIoBTgCEm2qHW3LQFyW', '2025-05-22 08:44:33', 0),
(9, 1, 'axelperez164623@gmail.com', 'e9e09d95337ec36a39b1ca7eb109f102', '$2y$10$lySR2ucklHyn5TdlEZLuIOQV/7rt5RJvyUBqqLpH2dDP0U42U3qcC', '2025-05-22 22:18:10', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_pedido` decimal(10,2) NOT NULL,
  `estado_pedido` varchar(50) NOT NULL DEFAULT 'Procesando',
  `direccion_envio_texto` text DEFAULT NULL,
  `metodo_pago_seleccionado` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre_producto` varchar(255) NOT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen_url` varchar(255) DEFAULT NULL,
  `talles_disponibles` varchar(255) DEFAULT NULL,
  `colores_disponibles` varchar(255) DEFAULT NULL,
  `tipo_mate_disponible` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `sku`, `descripcion`, `precio_venta`, `stock`, `imagen_url`, `talles_disponibles`, `colores_disponibles`, `tipo_mate_disponible`, `activo`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'Remera', 'SKU-REM-001', 'Remera de algodón con logo Tractores Clavijo.', 25000.00, 50, '../merch/img/11.png', 'S, M, L, XL, XXL', NULL, NULL, 1, '2025-05-22 13:15:35', '2025-05-22 13:15:35'),
(2, 'Taza Pequeña', 'SKU-TAZ-001', 'Taza de cerámica ideal para café o té.', 10000.00, 26, '../merch/img/2.png', NULL, 'Blanco, Negro, Verde', NULL, 1, '2025-05-22 13:15:35', '2025-05-22 16:48:35'),
(3, 'Termo Metálico', 'SKU-TER-001', 'Termo de acero inoxidable, mantiene la temperatura.', 45000.00, 18, '../merch/img/3.png', NULL, 'Negro, Verde Oscuro, Gris', NULL, 1, '2025-05-22 13:15:35', '2025-05-22 16:19:26'),
(4, 'Buzo con Capucha', 'SKU-BUZC-001', 'Buzo abrigado con capucha y logo.', 40000.00, 40, '../merch/img/4.png', 'S, M, L, XL, XXL', NULL, NULL, 1, '2025-05-22 13:15:35', '2025-05-22 13:15:35'),
(5, 'Buzo sin Capucha', 'SKU-BUZS-001', 'Buzo clásico sin capucha, cómodo y versátil.', 38000.00, 35, '../merch/img/5.png', 'S, M, L, XL, XXL', NULL, NULL, 1, '2025-05-22 13:15:35', '2025-05-22 13:15:35'),
(6, 'Campera Básica', 'SKU-CAMPB-001', 'Campera liviana para uso diario.', 45000.00, 25, '../merch/img/6.png', 'S, M, L, XL, XXL', NULL, NULL, 1, '2025-05-22 13:15:35', '2025-05-22 13:15:35'),
(7, 'Campera Inflable', 'SKU-CAMPI-001', 'Campera inflable ultra liviana y abrigada.', 90000.00, 14, '../merch/img/7.png', 'S, M, L, XL, XXL', NULL, NULL, 1, '2025-05-22 13:15:35', '2025-05-22 16:27:11'),
(8, 'Gorra Clásica', 'SKU-GOR-001', 'Gorra con visera y logo bordado.', 15000.00, 59, '../merch/img/8.png', 'Talle Único', 'Negro, Verde Militar, Azul Marino', NULL, 1, '2025-05-22 13:15:35', '2025-05-22 16:27:11'),
(9, 'Llavero Logo', 'SKU-LLA-001', 'Llavero metálico con el logo de Tractores Clavijo.', 8000.00, 100, '../merch/img/9.png', NULL, NULL, NULL, 1, '2025-05-22 13:15:35', '2025-05-22 13:15:35'),
(10, 'Mate Personalizado', 'SKU-MAT-001', 'Mate de primera calidad, personalizable.', 35000.00, 28, '../merch/img/10.png', NULL, NULL, 'Calabaza, Madera', 1, '2025-05-22 13:15:35', '2025-05-22 13:15:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tractores`
--

CREATE TABLE `tractores` (
  `id_tractor` int(11) NOT NULL,
  `marca_tractor` varchar(100) NOT NULL,
  `modelo_tractor` varchar(100) NOT NULL,
  `descripcion_tractor` text DEFAULT NULL,
  `caracteristicas_principales` text DEFAULT NULL,
  `potencia_hp` int(11) DEFAULT NULL,
  `tipo_motor` varchar(100) DEFAULT NULL,
  `transmision` varchar(100) DEFAULT NULL,
  `imagen_tractor_url` varchar(255) DEFAULT 'default_tractor.jpg',
  `folleto_url` varchar(255) DEFAULT NULL,
  `es_nuevo` tinyint(1) DEFAULT 1,
  `visible_catalogo` tinyint(1) DEFAULT 1,
  `fecha_lanzamiento_aprox` year(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD PRIMARY KEY (`id_auth_token`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `idx_auth_selector` (`selector`);

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito_item`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `idx_carrito_cliente` (`id_cliente`),
  ADD KEY `idx_carrito_sesion` (`id_sesion_invitado`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `consultas_contacto`
--
ALTER TABLE `consultas_contacto`
  ADD PRIMARY KEY (`id_consulta`),
  ADD KEY `idx_consulta_email` (`email`),
  ADD KEY `idx_consulta_estado` (`estado_consulta`),
  ADD KEY `idx_consulta_fecha` (`fecha_envio`);

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id_detalle_pedido`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id_reset_token`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `idx_reset_selector` (`selector`),
  ADD KEY `idx_reset_email` (`email`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_prod_nombre_simple` (`nombre_producto`),
  ADD KEY `idx_prod_sku_simple` (`sku`);

--
-- Indices de la tabla `tractores`
--
ALTER TABLE `tractores`
  ADD PRIMARY KEY (`id_tractor`),
  ADD KEY `idx_tractor_modelo` (`modelo_tractor`),
  ADD KEY `idx_tractor_marca` (`marca_tractor`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auth_tokens`
--
ALTER TABLE `auth_tokens`
  MODIFY `id_auth_token` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito_item` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `consultas_contacto`
--
ALTER TABLE `consultas_contacto`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id_detalle_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id_reset_token` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tractores`
--
ALTER TABLE `tractores`
  MODIFY `id_tractor` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auth_tokens`
--
ALTER TABLE `auth_tokens`
  ADD CONSTRAINT `auth_tokens_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `detalles_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
