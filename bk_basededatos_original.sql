-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para saas_tienda_moda
CREATE DATABASE IF NOT EXISTS `saas_tienda_moda` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `saas_tienda_moda`;

-- Volcando estructura para tabla saas_tienda_moda.caja
CREATE TABLE IF NOT EXISTS `caja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `fecha` date NOT NULL,
  `monto_inicial` decimal(10,2) NOT NULL DEFAULT '0.00',
  `monto_final` decimal(10,2) DEFAULT NULL,
  `total_ventas` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_egresos` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` enum('abierta','cerrada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'abierta',
  `apertura` timestamp NULL DEFAULT NULL,
  `cierre` timestamp NULL DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tienda_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `caja_user_id_foreign` (`user_id`),
  KEY `caja_tienda_id_index` (`tienda_id`),
  CONSTRAINT `caja_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.caja: ~0 rows (aproximadamente)
DELETE FROM `caja`;
INSERT INTO `caja` (`id`, `user_id`, `fecha`, `monto_inicial`, `monto_final`, `total_ventas`, `total_egresos`, `estado`, `apertura`, `cierre`, `observaciones`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 3, '2026-06-06', 200.00, 1518.70, 1318.70, 73.00, 'abierta', '2026-06-06 14:00:00', NULL, NULL, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(2, 4, '2026-06-05', 200.00, 1025.00, 825.00, 29.00, 'cerrada', '2026-06-05 14:00:00', '2026-06-06 01:00:00', NULL, '2026-06-05 15:08:43', '2026-06-05 15:08:43', 1),
	(3, 1, '2026-06-04', 200.00, 753.70, 553.70, 33.00, 'cerrada', '2026-06-04 14:00:00', '2026-06-05 01:00:00', NULL, '2026-06-04 15:08:43', '2026-06-04 15:08:43', 1),
	(4, 4, '2026-06-03', 200.00, 599.90, 399.90, 64.00, 'cerrada', '2026-06-03 14:00:00', '2026-06-04 01:00:00', NULL, '2026-06-03 15:08:43', '2026-06-03 15:08:43', 1),
	(5, 3, '2026-06-02', 200.00, 385.00, 185.00, 76.00, 'cerrada', '2026-06-02 14:00:00', '2026-06-03 01:00:00', NULL, '2026-06-02 15:08:43', '2026-06-02 15:08:43', 1),
	(6, 2, '2026-06-01', 200.00, 955.00, 755.00, 71.00, 'cerrada', '2026-06-01 14:00:00', '2026-06-02 01:00:00', NULL, '2026-06-01 15:08:43', '2026-06-01 15:08:43', 1),
	(7, 1, '2026-05-31', 200.00, 200.00, 0.00, 62.00, 'cerrada', '2026-05-31 14:00:00', '2026-06-01 01:00:00', NULL, '2026-05-31 15:08:43', '2026-05-31 15:08:43', 1),
	(8, 2, '2026-05-30', 200.00, 200.00, 0.00, 49.00, 'cerrada', '2026-05-30 14:00:00', '2026-05-31 01:00:00', NULL, '2026-05-30 15:08:43', '2026-05-30 15:08:43', 1),
	(9, 3, '2026-05-29', 200.00, 984.80, 784.80, 41.00, 'cerrada', '2026-05-29 14:00:00', '2026-05-30 01:00:00', NULL, '2026-05-29 15:08:43', '2026-05-29 15:08:43', 1),
	(10, 1, '2026-05-28', 200.00, 200.00, 0.00, 79.00, 'cerrada', '2026-05-28 14:00:00', '2026-05-29 01:00:00', NULL, '2026-05-28 15:08:43', '2026-05-28 15:08:43', 1);

-- Volcando estructura para tabla saas_tienda_moda.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero` enum('damas','caballeros','ninos','unisex') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unisex',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tienda_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categorias_slug_unique` (`slug`),
  KEY `categorias_tienda_id_index` (`tienda_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.categorias: ~10 rows (aproximadamente)
DELETE FROM `categorias`;
INSERT INTO `categorias` (`id`, `nombre`, `slug`, `descripcion`, `imagen`, `genero`, `activo`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 'Camisas', 'camisas', NULL, NULL, 'caballeros', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(2, 'Blusas', 'blusas', NULL, NULL, 'damas', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(3, 'Pantalones', 'pantalones', NULL, NULL, 'unisex', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(4, 'Vestidos', 'vestidos', NULL, NULL, 'damas', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(5, 'Ropa Deportiva', 'deportiva', NULL, NULL, 'unisex', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(6, 'Ropa Niños', 'ninos', NULL, NULL, 'ninos', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(7, 'Chaquetas', 'chaquetas', NULL, NULL, 'unisex', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(8, 'Jeans', 'jeans', NULL, NULL, 'unisex', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(9, 'Faldas', 'faldas', NULL, NULL, 'damas', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(10, 'Accesorios', 'accesorios', NULL, NULL, 'unisex', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1);

-- Volcando estructura para tabla saas_tienda_moda.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dni` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` enum('M','F','otro') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `puntos` decimal(10,2) NOT NULL DEFAULT '0.00',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tienda_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clientes_codigo_unique` (`codigo`),
  UNIQUE KEY `clientes_dni_unique` (`dni`),
  KEY `clientes_tienda_id_index` (`tienda_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.clientes: ~0 rows (aproximadamente)
DELETE FROM `clientes`;
INSERT INTO `clientes` (`id`, `codigo`, `nombre`, `apellido`, `dni`, `email`, `telefono`, `direccion`, `ciudad`, `fecha_nacimiento`, `genero`, `puntos`, `activo`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 'CLI-0001', 'Sofía', 'Gutiérrez', '57105895', 'sofía.gutiérrez@gmail.com', '984743431', NULL, 'Lima', NULL, 'F', 98.00, 1, '2026-03-02 15:08:43', '2026-06-06 15:08:43', 1),
	(2, 'CLI-0002', 'Mateo', 'Rojas', '76747466', 'mateo.rojas@gmail.com', '979787773', NULL, 'Arequipa', NULL, 'M', 214.00, 1, '2026-04-28 15:08:43', '2026-06-06 15:08:43', 1),
	(3, 'CLI-0003', 'Valentina', 'Flores', '56353288', 'valentina.flores@gmail.com', '989722182', NULL, 'Trujillo', NULL, 'F', 165.00, 1, '2026-03-30 15:08:43', '2026-06-06 15:08:43', 1),
	(4, 'CLI-0004', 'Diego', 'Castro', '68377071', 'diego.castro@gmail.com', '917029023', NULL, 'Lima', NULL, 'M', 39.00, 1, '2026-02-15 15:08:43', '2026-06-06 15:08:43', 1),
	(5, 'CLI-0005', 'Camila', 'Vargas', '61806433', 'camila.vargas@gmail.com', '945965267', NULL, 'Cusco', NULL, 'F', 128.00, 1, '2026-02-06 15:08:43', '2026-06-06 15:08:43', 1),
	(6, 'CLI-0006', 'Sebastián', 'Núñez', '57931304', 'sebastián.núñez@gmail.com', '923520330', NULL, 'Piura', NULL, 'M', 54.00, 1, '2026-02-23 15:08:43', '2026-06-06 15:08:43', 1),
	(7, 'CLI-0007', 'Isabella', 'Ramos', '53509158', 'isabella.ramos@gmail.com', '973487387', NULL, 'Lima', NULL, 'F', 489.00, 1, '2026-03-19 15:08:43', '2026-06-06 15:08:43', 1),
	(8, 'CLI-0008', 'Joaquín', 'Salazar', '72810676', 'joaquín.salazar@gmail.com', '965766938', NULL, 'Chiclayo', NULL, 'M', 446.00, 1, '2026-03-01 15:08:43', '2026-06-06 15:08:43', 1),
	(9, 'CLI-0009', 'Antonella', 'Reyes', '77150043', 'antonella.reyes@gmail.com', '962216176', NULL, 'Ica', NULL, 'F', 212.00, 1, '2026-04-09 15:08:43', '2026-06-06 15:08:43', 1),
	(10, 'CLI-0010', 'Thiago', 'Paredes', '58885347', 'thiago.paredes@gmail.com', '948075432', NULL, 'Lima', NULL, 'M', 404.00, 1, '2026-04-04 15:08:43', '2026-06-06 15:08:43', 1);

-- Volcando estructura para tabla saas_tienda_moda.compras
CREATE TABLE IF NOT EXISTS `compras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero_compra` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proveedor_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `fecha` date NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` enum('pendiente','recibido','anulado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'recibido',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tienda_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `compras_numero_compra_unique` (`numero_compra`),
  KEY `compras_proveedor_id_foreign` (`proveedor_id`),
  KEY `compras_user_id_foreign` (`user_id`),
  KEY `compras_tienda_id_index` (`tienda_id`),
  CONSTRAINT `compras_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  CONSTRAINT `compras_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.compras: ~0 rows (aproximadamente)
DELETE FROM `compras`;
INSERT INTO `compras` (`id`, `numero_compra`, `proveedor_id`, `user_id`, `fecha`, `total`, `estado`, `observaciones`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 'C-2026-00001', 7, 1, '2026-05-10', 2230.00, 'recibido', 'Reposición de inventario', '2026-05-10 15:08:43', '2026-05-10 15:08:43', 1),
	(2, 'C-2026-00002', 8, 1, '2026-05-20', 4050.00, 'recibido', 'Reposición de inventario', '2026-05-20 15:08:43', '2026-05-20 15:08:43', 1),
	(3, 'C-2026-00003', 4, 1, '2026-05-11', 4960.00, 'recibido', 'Reposición de inventario', '2026-05-11 15:08:43', '2026-05-11 15:08:43', 1),
	(4, 'C-2026-00004', 5, 1, '2026-05-27', 5985.00, 'recibido', 'Reposición de inventario', '2026-05-27 15:08:43', '2026-05-27 15:08:43', 1),
	(5, 'C-2026-00005', 1, 1, '2026-05-21', 1925.00, 'recibido', 'Reposición de inventario', '2026-05-21 15:08:43', '2026-05-21 15:08:43', 1),
	(6, 'C-2026-00006', 2, 1, '2026-05-09', 3606.00, 'recibido', 'Reposición de inventario', '2026-05-09 15:08:43', '2026-05-09 15:08:43', 1),
	(7, 'C-2026-00007', 3, 1, '2026-05-17', 5526.00, 'recibido', 'Reposición de inventario', '2026-05-17 15:08:43', '2026-05-17 15:08:43', 1),
	(8, 'C-2026-00008', 5, 1, '2026-05-07', 2600.00, 'recibido', 'Reposición de inventario', '2026-05-07 15:08:43', '2026-05-07 15:08:43', 1),
	(9, 'C-2026-00009', 2, 1, '2026-04-28', 6738.00, 'recibido', 'Reposición de inventario', '2026-04-28 15:08:43', '2026-04-28 15:08:43', 1),
	(10, 'C-2026-00010', 8, 1, '2026-04-28', 1465.00, 'recibido', 'Reposición de inventario', '2026-04-28 15:08:43', '2026-04-28 15:08:43', 1);

-- Volcando estructura para tabla saas_tienda_moda.detalle_compras
CREATE TABLE IF NOT EXISTS `detalle_compras` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `compra_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `variante_id` bigint unsigned DEFAULT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tienda_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_compras_compra_id_foreign` (`compra_id`),
  KEY `detalle_compras_producto_id_foreign` (`producto_id`),
  KEY `detalle_compras_variante_id_foreign` (`variante_id`),
  KEY `detalle_compras_tienda_id_index` (`tienda_id`),
  CONSTRAINT `detalle_compras_compra_id_foreign` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_compras_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  CONSTRAINT `detalle_compras_variante_id_foreign` FOREIGN KEY (`variante_id`) REFERENCES `producto_variantes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.detalle_compras: ~0 rows (aproximadamente)
DELETE FROM `detalle_compras`;
INSERT INTO `detalle_compras` (`id`, `compra_id`, `producto_id`, `variante_id`, `cantidad`, `precio_unitario`, `subtotal`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 1, 8, 20, 35, 50.00, 1750.00, '2026-05-10 15:08:43', '2026-05-10 15:08:43', 1),
	(2, 1, 6, 13, 24, 20.00, 480.00, '2026-05-10 15:08:43', '2026-05-10 15:08:43', 1),
	(3, 2, 5, 10, 27, 70.00, 1890.00, '2026-05-20 15:08:43', '2026-05-20 15:08:43', 1),
	(4, 2, 4, 8, 36, 60.00, 2160.00, '2026-05-20 15:08:43', '2026-05-20 15:08:43', 1),
	(5, 3, 6, 14, 50, 20.00, 1000.00, '2026-05-11 15:08:43', '2026-05-11 15:08:43', 1),
	(6, 3, 10, 23, 36, 15.00, 540.00, '2026-05-11 15:08:43', '2026-05-11 15:08:43', 1),
	(7, 3, 4, 8, 24, 60.00, 1440.00, '2026-05-11 15:08:43', '2026-05-11 15:08:43', 1),
	(8, 3, 4, 8, 33, 60.00, 1980.00, '2026-05-11 15:08:43', '2026-05-11 15:08:43', 1),
	(9, 4, 7, 15, 39, 80.00, 3120.00, '2026-05-27 15:08:43', '2026-05-27 15:08:43', 1),
	(10, 4, 3, 6, 19, 55.00, 1045.00, '2026-05-27 15:08:43', '2026-05-27 15:08:43', 1),
	(11, 4, 8, 18, 13, 50.00, 650.00, '2026-05-27 15:08:43', '2026-05-27 15:08:43', 1),
	(12, 4, 1, 1, 26, 45.00, 1170.00, '2026-05-27 15:08:43', '2026-05-27 15:08:43', 1),
	(13, 5, 2, 4, 10, 38.00, 380.00, '2026-05-21 15:08:43', '2026-05-21 15:08:43', 1),
	(14, 5, 4, 9, 10, 60.00, 600.00, '2026-05-21 15:08:43', '2026-05-21 15:08:43', 1),
	(15, 5, 10, 24, 23, 15.00, 345.00, '2026-05-21 15:08:43', '2026-05-21 15:08:43', 1),
	(16, 5, 8, 20, 12, 50.00, 600.00, '2026-05-21 15:08:43', '2026-05-21 15:08:43', 1),
	(17, 6, 9, 21, 23, 42.00, 966.00, '2026-05-09 15:08:43', '2026-05-09 15:08:43', 1),
	(18, 6, 7, 15, 33, 80.00, 2640.00, '2026-05-09 15:08:43', '2026-05-09 15:08:43', 1),
	(19, 7, 2, 4, 14, 38.00, 532.00, '2026-05-17 15:08:43', '2026-05-17 15:08:43', 1),
	(20, 7, 3, 7, 32, 55.00, 1760.00, '2026-05-17 15:08:43', '2026-05-17 15:08:43', 1),
	(21, 7, 4, 9, 33, 60.00, 1980.00, '2026-05-17 15:08:43', '2026-05-17 15:08:43', 1),
	(22, 7, 2, 3, 33, 38.00, 1254.00, '2026-05-17 15:08:43', '2026-05-17 15:08:43', 1),
	(23, 8, 4, 8, 27, 60.00, 1620.00, '2026-05-07 15:08:43', '2026-05-07 15:08:43', 1),
	(24, 8, 5, 10, 14, 70.00, 980.00, '2026-05-07 15:08:43', '2026-05-07 15:08:43', 1),
	(25, 9, 9, 21, 49, 42.00, 2058.00, '2026-04-28 15:08:43', '2026-04-28 15:08:43', 1),
	(26, 9, 2, 3, 10, 38.00, 380.00, '2026-04-28 15:08:43', '2026-04-28 15:08:43', 1),
	(27, 9, 4, 9, 13, 60.00, 780.00, '2026-04-28 15:08:43', '2026-04-28 15:08:43', 1),
	(28, 9, 7, 15, 44, 80.00, 3520.00, '2026-04-28 15:08:43', '2026-04-28 15:08:43', 1),
	(29, 10, 2, 3, 20, 38.00, 760.00, '2026-04-28 15:08:43', '2026-04-28 15:08:43', 1),
	(30, 10, 10, 24, 47, 15.00, 705.00, '2026-04-28 15:08:43', '2026-04-28 15:08:43', 1);

-- Volcando estructura para tabla saas_tienda_moda.detalle_ventas
CREATE TABLE IF NOT EXISTS `detalle_ventas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `venta_id` bigint unsigned NOT NULL,
  `producto_id` bigint unsigned NOT NULL,
  `variante_id` bigint unsigned DEFAULT NULL,
  `producto_nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `talla` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tienda_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_ventas_venta_id_foreign` (`venta_id`),
  KEY `detalle_ventas_producto_id_foreign` (`producto_id`),
  KEY `detalle_ventas_variante_id_foreign` (`variante_id`),
  KEY `detalle_ventas_tienda_id_index` (`tienda_id`),
  CONSTRAINT `detalle_ventas_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  CONSTRAINT `detalle_ventas_variante_id_foreign` FOREIGN KEY (`variante_id`) REFERENCES `producto_variantes` (`id`),
  CONSTRAINT `detalle_ventas_venta_id_foreign` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.detalle_ventas: ~0 rows (aproximadamente)
DELETE FROM `detalle_ventas`;
INSERT INTO `detalle_ventas` (`id`, `venta_id`, `producto_id`, `variante_id`, `producto_nombre`, `talla`, `color`, `cantidad`, `precio_unitario`, `descuento`, `subtotal`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 1, 4, 8, 'Vestido Casual Verano', 'S', 'Rojo', 2, 145.00, 0.00, 290.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(2, 1, 3, 6, 'Pantalón Chino Beige', 'M', 'Rojo', 3, 120.00, 0.00, 360.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(3, 1, 5, 11, 'Conjunto Deportivo Running', 'M', 'Verde', 3, 159.90, 0.00, 479.70, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(4, 2, 7, 16, 'Chaqueta Jean Oversize', 'M', 'Blanco', 1, 189.00, 0.00, 189.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(5, 3, 2, 3, 'Blusa Floral Manga Larga', 'S', 'Blanco', 3, 75.00, 0.00, 225.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(6, 3, 3, 5, 'Pantalón Chino Beige', 'S', 'Azul', 1, 120.00, 0.00, 120.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(7, 4, 1, 1, 'Camisa Slim Fit Cuadros', 'S', 'Negro', 3, 89.90, 0.00, 269.70, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(8, 4, 9, 22, 'Falda Plisada Midi', 'M', 'Rojo', 1, 95.00, 0.00, 95.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(9, 4, 7, 15, 'Chaqueta Jean Oversize', 'S', 'Negro', 1, 189.00, 0.00, 189.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(10, 5, 6, 14, 'Polo Niño Estampado', 'L', 'Blanco', 1, 39.90, 0.00, 39.90, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(11, 5, 3, 7, 'Pantalón Chino Beige', 'L', 'Beige', 3, 120.00, 0.00, 360.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(12, 6, 8, 20, 'Jean Skinny Azul', 'L', 'Rojo', 1, 110.00, 0.00, 110.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(13, 6, 2, 4, 'Blusa Floral Manga Larga', 'M', 'Azul', 1, 75.00, 0.00, 75.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(14, 7, 9, 22, 'Falda Plisada Midi', 'M', 'Rojo', 1, 95.00, 0.00, 95.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(15, 7, 4, 8, 'Vestido Casual Verano', 'S', 'Rojo', 3, 145.00, 0.00, 435.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(16, 7, 2, 4, 'Blusa Floral Manga Larga', 'M', 'Azul', 3, 75.00, 0.00, 225.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(17, 8, 4, 8, 'Vestido Casual Verano', 'S', 'Rojo', 3, 145.00, 0.00, 435.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(18, 8, 10, 23, 'Gorra Snapback Urbana', 'S', 'Rojo', 1, 45.00, 0.00, 45.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(19, 9, 8, 18, 'Jean Skinny Azul', 'S', 'Blanco', 3, 110.00, 0.00, 330.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(20, 9, 5, 11, 'Conjunto Deportivo Running', 'M', 'Verde', 2, 159.90, 0.00, 319.80, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(21, 9, 10, 23, 'Gorra Snapback Urbana', 'S', 'Rojo', 3, 45.00, 0.00, 135.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(22, 10, 2, 3, 'Blusa Floral Manga Larga', 'S', 'Blanco', 3, 75.00, 0.00, 225.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(23, 10, 10, 24, 'Gorra Snapback Urbana', 'M', 'Beige', 1, 45.00, 0.00, 45.00, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1);

-- Volcando estructura para tabla saas_tienda_moda.marcas
CREATE TABLE IF NOT EXISTS `marcas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tienda_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marcas_tienda_id_index` (`tienda_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.marcas: ~8 rows (aproximadamente)
DELETE FROM `marcas`;
INSERT INTO `marcas` (`id`, `nombre`, `logo`, `activo`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 'Zara', NULL, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(2, 'H&M', NULL, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(3, 'Adidas', NULL, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(4, 'Nike', NULL, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(5, 'Polo', NULL, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(6, 'Tommy Hilfiger', NULL, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(7, 'Levis', NULL, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(8, 'Forever 21', NULL, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1);

-- Volcando estructura para tabla saas_tienda_moda.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.migrations: ~0 rows (aproximadamente)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(2, '2024_01_01_000000_create_users_table', 1),
	(3, '2024_01_01_000001_create_catalogo_tables', 1),
	(4, '2024_01_01_000002_create_clientes_ventas_tables', 1),
	(5, '2024_01_02_000000_create_saas_tables', 2),
	(6, '2024_01_03_000000_add_config_to_tiendas', 3);

-- Volcando estructura para tabla saas_tienda_moda.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.personal_access_tokens: ~0 rows (aproximadamente)
DELETE FROM `personal_access_tokens`;

-- Volcando estructura para tabla saas_tienda_moda.planes
CREATE TABLE IF NOT EXISTS `planes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_productos` int NOT NULL DEFAULT '-1',
  `max_usuarios` int NOT NULL DEFAULT '-1',
  `max_ventas_mes` int NOT NULL DEFAULT '-1',
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `caracteristicas` json DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `planes_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.planes: ~0 rows (aproximadamente)
DELETE FROM `planes`;
INSERT INTO `planes` (`id`, `nombre`, `slug`, `precio`, `max_productos`, `max_usuarios`, `max_ventas_mes`, `descripcion`, `caracteristicas`, `activo`, `created_at`, `updated_at`) VALUES
	(1, 'Básico', 'basico', 49.00, 50, 2, 200, 'Ideal para empezar', '["1 sucursal", "Soporte por correo", "Reportes básicos"]', 1, '2026-06-06 15:08:41', '2026-06-06 15:08:41'),
	(2, 'Pro', 'pro', 99.00, 500, 8, -1, 'Para tiendas en crecimiento', '["3 sucursales", "Soporte prioritario", "Reportes avanzados", "Gestión de compras"]', 1, '2026-06-06 15:08:41', '2026-06-06 15:08:41'),
	(3, 'Premium', 'premium', 199.00, -1, -1, -1, 'Sin límites', '["Sucursales ilimitadas", "Soporte 24/7", "Todos los reportes", "API e integraciones"]', 1, '2026-06-06 15:08:41', '2026-06-06 15:08:41');

-- Volcando estructura para tabla saas_tienda_moda.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `categoria_id` bigint unsigned NOT NULL,
  `subcategoria_id` bigint unsigned DEFAULT NULL,
  `marca_id` bigint unsigned DEFAULT NULL,
  `proveedor_id` bigint unsigned DEFAULT NULL,
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `genero` enum('damas','caballeros','ninos','unisex') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unisex',
  `tipo` enum('casual','deportivo','formal','otro') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'casual',
  `precio_compra` decimal(10,2) NOT NULL DEFAULT '0.00',
  `precio_venta` decimal(10,2) NOT NULL,
  `precio_oferta` decimal(10,2) DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `destacado` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tienda_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productos_codigo_unique` (`codigo`),
  KEY `productos_categoria_id_foreign` (`categoria_id`),
  KEY `productos_subcategoria_id_foreign` (`subcategoria_id`),
  KEY `productos_marca_id_foreign` (`marca_id`),
  KEY `productos_proveedor_id_foreign` (`proveedor_id`),
  KEY `productos_tienda_id_index` (`tienda_id`),
  CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  CONSTRAINT `productos_marca_id_foreign` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`),
  CONSTRAINT `productos_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  CONSTRAINT `productos_subcategoria_id_foreign` FOREIGN KEY (`subcategoria_id`) REFERENCES `subcategorias` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.productos: ~0 rows (aproximadamente)
DELETE FROM `productos`;
INSERT INTO `productos` (`id`, `categoria_id`, `subcategoria_id`, `marca_id`, `proveedor_id`, `codigo`, `nombre`, `descripcion`, `genero`, `tipo`, `precio_compra`, `precio_venta`, `precio_oferta`, `imagen`, `activo`, `destacado`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 1, NULL, 4, 7, 'PRD-0001', 'Camisa Slim Fit Cuadros', 'Camisa Slim Fit Cuadros de excelente calidad.', 'caballeros', 'casual', 45.00, 89.90, NULL, NULL, 1, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(2, 2, NULL, 6, 1, 'PRD-0002', 'Blusa Floral Manga Larga', 'Blusa Floral Manga Larga de excelente calidad.', 'damas', 'casual', 38.00, 75.00, NULL, NULL, 1, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(3, 3, NULL, 6, 3, 'PRD-0003', 'Pantalón Chino Beige', 'Pantalón Chino Beige de excelente calidad.', 'caballeros', 'casual', 55.00, 120.00, NULL, NULL, 1, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(4, 4, NULL, 1, 9, 'PRD-0004', 'Vestido Casual Verano', 'Vestido Casual Verano de excelente calidad.', 'damas', 'casual', 60.00, 145.00, NULL, NULL, 1, 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(5, 5, NULL, 1, 7, 'PRD-0005', 'Conjunto Deportivo Running', 'Conjunto Deportivo Running de excelente calidad.', 'unisex', 'deportivo', 70.00, 159.90, NULL, NULL, 1, 0, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(6, 6, NULL, 8, 7, 'PRD-0006', 'Polo Niño Estampado', 'Polo Niño Estampado de excelente calidad.', 'ninos', 'casual', 20.00, 39.90, NULL, NULL, 1, 0, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(7, 7, NULL, 8, 8, 'PRD-0007', 'Chaqueta Jean Oversize', 'Chaqueta Jean Oversize de excelente calidad.', 'unisex', 'casual', 80.00, 189.00, NULL, NULL, 1, 0, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(8, 8, NULL, 7, 10, 'PRD-0008', 'Jean Skinny Azul', 'Jean Skinny Azul de excelente calidad.', 'damas', 'casual', 50.00, 110.00, NULL, NULL, 1, 0, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(9, 9, NULL, 4, 3, 'PRD-0009', 'Falda Plisada Midi', 'Falda Plisada Midi de excelente calidad.', 'damas', 'formal', 42.00, 95.00, NULL, NULL, 1, 0, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(10, 10, NULL, 1, 8, 'PRD-0010', 'Gorra Snapback Urbana', 'Gorra Snapback Urbana de excelente calidad.', 'unisex', 'casual', 15.00, 45.00, NULL, NULL, 1, 0, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1);

-- Volcando estructura para tabla saas_tienda_moda.producto_variantes
CREATE TABLE IF NOT EXISTS `producto_variantes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `producto_id` bigint unsigned NOT NULL,
  `talla` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo_barra` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `stock_minimo` int NOT NULL DEFAULT '5',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tienda_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_variantes_producto_id_foreign` (`producto_id`),
  KEY `producto_variantes_tienda_id_index` (`tienda_id`),
  CONSTRAINT `producto_variantes_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.producto_variantes: ~0 rows (aproximadamente)
DELETE FROM `producto_variantes`;
INSERT INTO `producto_variantes` (`id`, `producto_id`, `talla`, `color`, `codigo_barra`, `stock`, `stock_minimo`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 1, 'S', 'Negro', '7848178515', 0, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(2, 1, 'M', 'Blanco', '7852085363', 24, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(3, 2, 'S', 'Blanco', '7886326421', 8, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(4, 2, 'M', 'Azul', '7851975819', 44, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(5, 3, 'S', 'Azul', '7834028031', 46, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(6, 3, 'M', 'Rojo', '7855283943', 12, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(7, 3, 'L', 'Beige', '7886209113', 43, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(8, 4, 'S', 'Rojo', '7873670456', 6, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(9, 4, 'M', 'Beige', '7820085102', 19, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(10, 5, 'S', 'Beige', '7842980254', 0, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(11, 5, 'M', 'Verde', '7894776062', 6, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(12, 6, 'S', 'Verde', '7881204382', 30, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(13, 6, 'M', 'Negro', '7854152554', 36, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(14, 6, 'L', 'Blanco', '7845804733', 55, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(15, 7, 'S', 'Negro', '7818995626', 45, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(16, 7, 'M', 'Blanco', '7885419404', 53, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(17, 7, 'L', 'Azul', '7866328485', 16, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(18, 8, 'S', 'Blanco', '7892557318', 32, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(19, 8, 'M', 'Azul', '7837331354', 27, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(20, 8, 'L', 'Rojo', '7815845939', 58, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(21, 9, 'S', 'Azul', '7844715323', 1, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(22, 9, 'M', 'Rojo', '7832937888', 58, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(23, 10, 'S', 'Rojo', '7838099033', 28, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(24, 10, 'M', 'Beige', '7814384572', 55, 5, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1);

-- Volcando estructura para tabla saas_tienda_moda.proveedores
CREATE TABLE IF NOT EXISTS `proveedores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pais` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Perú',
  `contacto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tienda_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `proveedores_ruc_unique` (`ruc`),
  KEY `proveedores_tienda_id_index` (`tienda_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.proveedores: ~0 rows (aproximadamente)
DELETE FROM `proveedores`;
INSERT INTO `proveedores` (`id`, `nombre`, `ruc`, `email`, `telefono`, `direccion`, `ciudad`, `pais`, `contacto`, `activo`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 'Textiles Andinos SAC', '20512345678', 'ventas1@proveedor.com', '01-2546831', 'Av. Industrial 282', 'Lima', 'Perú', 'Rosa Gil', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(2, 'Moda Import EIRL', '20587654321', 'ventas2@proveedor.com', '01-4312997', 'Av. Industrial 616', 'Lima', 'Perú', 'Rosa Gil', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(3, 'Distribuidora Fashion Perú', '20498765432', 'ventas3@proveedor.com', '01-7702301', 'Av. Industrial 537', 'Arequipa', 'Perú', 'Luis Vega', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(4, 'Confecciones del Sur', '20456781234', 'ventas4@proveedor.com', '01-4242464', 'Av. Industrial 553', 'Cusco', 'Perú', 'Luis Vega', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(5, 'Comercial Estilo SAC', '20467812345', 'ventas5@proveedor.com', '01-3000951', 'Av. Industrial 276', 'Trujillo', 'Perú', 'Pedro Díaz', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(6, 'Importaciones Glamour', '20478123456', 'ventas6@proveedor.com', '01-7335909', 'Av. Industrial 906', 'Lima', 'Perú', 'Luis Vega', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(7, 'Ropa & Más Distribución', '20489234567', 'ventas7@proveedor.com', '01-6741671', 'Av. Industrial 519', 'Piura', 'Perú', 'Luis Vega', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(8, 'Tendencias Textiles SAC', '20490345678', 'ventas8@proveedor.com', '01-7743447', 'Av. Industrial 528', 'Chiclayo', 'Perú', 'Luis Vega', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(9, 'Grupo Vestir Perú', '20501456789', 'ventas9@proveedor.com', '01-4853997', 'Av. Industrial 383', 'Lima', 'Perú', 'Pedro Díaz', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1),
	(10, 'Almacenes Bella Moda', '20512567890', 'ventas10@proveedor.com', '01-4356649', 'Av. Industrial 126', 'Ica', 'Perú', 'María López', 1, '2026-06-06 15:08:43', '2026-06-06 15:08:43', 1);

-- Volcando estructura para tabla saas_tienda_moda.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.roles: ~2 rows (aproximadamente)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
	(1, 'Administrador', 'Acceso total al sistema', '2026-06-06 12:54:28', '2026-06-06 12:54:28'),
	(2, 'Vendedor', 'Gestión de ventas y clientes', '2026-06-06 12:54:28', '2026-06-06 12:54:28'),
	(3, 'Almacén', 'Gestión de inventario', '2026-06-06 12:54:28', '2026-06-06 12:54:28');

-- Volcando estructura para tabla saas_tienda_moda.subcategorias
CREATE TABLE IF NOT EXISTS `subcategorias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `categoria_id` bigint unsigned NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subcategorias_slug_unique` (`slug`),
  KEY `subcategorias_categoria_id_foreign` (`categoria_id`),
  CONSTRAINT `subcategorias_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.subcategorias: ~0 rows (aproximadamente)
DELETE FROM `subcategorias`;

-- Volcando estructura para tabla saas_tienda_moda.tiendas
CREATE TABLE IF NOT EXISTS `tiendas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `igv` decimal(5,2) NOT NULL DEFAULT '18.00',
  `moneda` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PEN',
  `simbolo_moneda` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'S/',
  `serie_boleta` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'B001',
  `serie_factura` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F001',
  `plan_id` bigint unsigned DEFAULT NULL,
  `estado` enum('prueba','activa','suspendida') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'prueba',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tiendas_slug_unique` (`slug`),
  KEY `tiendas_plan_id_foreign` (`plan_id`),
  CONSTRAINT `tiendas_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.tiendas: ~0 rows (aproximadamente)
DELETE FROM `tiendas`;
INSERT INTO `tiendas` (`id`, `nombre`, `slug`, `ruc`, `email`, `telefono`, `direccion`, `logo`, `igv`, `moneda`, `simbolo_moneda`, `serie_boleta`, `serie_factura`, `plan_id`, `estado`, `fecha_inicio`, `fecha_vencimiento`, `created_at`, `updated_at`) VALUES
	(1, 'Moda Demo', 'tienda-demo', NULL, 'admin@tiendamoda.com', '01-7000000', 'Av. La Moda 123, Lima', NULL, 18.00, 'PEN', 'S/', 'B001', 'F001', 2, 'activa', '2026-04-06', '2027-06-06', '2026-06-06 15:08:41', '2026-06-06 15:08:41');

-- Volcando estructura para tabla saas_tienda_moda.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rol_id` bigint unsigned NOT NULL DEFAULT '2',
  `tienda_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `es_super_admin` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_rol_id_foreign` (`rol_id`),
  KEY `users_tienda_id_foreign` (`tienda_id`),
  CONSTRAINT `users_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `users_tienda_id_foreign` FOREIGN KEY (`tienda_id`) REFERENCES `tiendas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.users: ~0 rows (aproximadamente)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `rol_id`, `tienda_id`, `name`, `email`, `telefono`, `avatar`, `email_verified_at`, `password`, `activo`, `es_super_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'Administrador', 'admin@tiendamoda.com', NULL, NULL, NULL, '$2y$12$yIeljjRN8uxGjCJhQ1fhYuiQZ5ZwPLMCRgiKHnvNYl8S0wEb2yw9q', 1, 0, NULL, '2026-06-06 12:54:28', '2026-06-06 15:08:42'),
	(2, 2, 1, 'Lucía Torres', 'vendedor1@tiendamoda.com', NULL, NULL, NULL, '$2y$12$zy6HnHW8yUBdH4iRmbyRBOKa0is/5x7O/jtgGhc1ovXrUlp/oORT2', 1, 0, NULL, '2026-06-06 13:25:08', '2026-06-06 15:08:42'),
	(3, 2, 1, 'Carlos Mendoza', 'vendedor2@tiendamoda.com', NULL, NULL, NULL, '$2y$12$REn4pRlBF0fOMEkC6T0KhecYxLoNsaXubAiwnhcCweBl26JSByUEy', 1, 0, NULL, '2026-06-06 13:25:09', '2026-06-06 15:08:42'),
	(4, 2, 1, 'Ana Ramírez', 'vendedor3@tiendamoda.com', NULL, NULL, NULL, '$2y$12$MxHX.xQC42nShBgQ28iNL./poil56A4OaIlGoj953GeEVv7y4EZpm', 1, 0, NULL, '2026-06-06 13:25:09', '2026-06-06 15:08:43'),
	(5, 1, NULL, 'Super Admin', 'superadmin@tiendamoda.com', NULL, NULL, NULL, '$2y$12$0aSlqwLOYA7J5cMBmG.i3eIO1SuPfevx9/8P5EMvNQJEz20.PI4Pe', 1, 1, NULL, '2026-06-06 15:08:41', '2026-06-06 15:08:41');

-- Volcando estructura para tabla saas_tienda_moda.ventas
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `numero_venta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cliente_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL,
  `fecha` date NOT NULL,
  `tipo_comprobante` enum('boleta','factura','ticket') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'boleta',
  `serie` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correlativo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `descuento` decimal(10,2) NOT NULL DEFAULT '0.00',
  `igv` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `metodo_pago` enum('efectivo','tarjeta','transferencia','yape','plin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'efectivo',
  `monto_pagado` decimal(10,2) NOT NULL DEFAULT '0.00',
  `vuelto` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` enum('pendiente','completada','anulada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completada',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tienda_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ventas_numero_venta_unique` (`numero_venta`),
  KEY `ventas_cliente_id_foreign` (`cliente_id`),
  KEY `ventas_user_id_foreign` (`user_id`),
  KEY `ventas_tienda_id_index` (`tienda_id`),
  CONSTRAINT `ventas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `ventas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.ventas: ~0 rows (aproximadamente)
DELETE FROM `ventas`;
INSERT INTO `ventas` (`id`, `numero_venta`, `cliente_id`, `user_id`, `fecha`, `tipo_comprobante`, `serie`, `correlativo`, `subtotal`, `descuento`, `igv`, `total`, `metodo_pago`, `monto_pagado`, `vuelto`, `estado`, `observaciones`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 'V-2026-00001', 5, 4, '2026-06-06', 'boleta', 'B001', '000001', 957.37, 0.00, 172.33, 1129.70, 'tarjeta', 1129.70, 0.00, 'completada', NULL, '2026-06-06 19:07:00', '2026-06-06 15:08:43', 1),
	(2, 'V-2026-00002', 4, 3, '2026-06-06', 'factura', 'B001', '000002', 160.17, 0.00, 28.83, 189.00, 'plin', 189.00, 0.00, 'completada', NULL, '2026-06-07 00:30:00', '2026-06-06 15:08:43', 1),
	(3, 'V-2026-00003', 3, 3, '2026-06-05', 'ticket', 'B001', '000003', 292.37, 0.00, 52.63, 345.00, 'yape', 345.00, 0.00, 'completada', NULL, '2026-06-05 23:11:00', '2026-06-06 15:08:43', 1),
	(4, 'V-2026-00004', 7, 2, '2026-06-04', 'factura', 'B001', '000004', 469.24, 0.00, 84.46, 553.70, 'yape', 553.70, 0.00, 'completada', NULL, '2026-06-04 21:03:00', '2026-06-06 15:08:43', 1),
	(5, 'V-2026-00005', 5, 1, '2026-06-03', 'boleta', 'B001', '000005', 338.90, 0.00, 61.00, 399.90, 'tarjeta', 399.90, 0.00, 'completada', NULL, '2026-06-03 16:41:00', '2026-06-06 15:08:43', 1),
	(6, 'V-2026-00006', 3, 3, '2026-06-02', 'boleta', 'B001', '000006', 156.78, 0.00, 28.22, 185.00, 'transferencia', 185.00, 0.00, 'completada', NULL, '2026-06-02 21:25:00', '2026-06-06 15:08:43', 1),
	(7, 'V-2026-00007', 7, 3, '2026-06-01', 'boleta', 'B001', '000007', 639.83, 0.00, 115.17, 755.00, 'efectivo', 755.00, 0.00, 'completada', NULL, '2026-06-01 15:18:00', '2026-06-06 15:08:43', 1),
	(8, 'V-2026-00008', 8, 3, '2026-06-05', 'ticket', 'B001', '000008', 406.78, 0.00, 73.22, 480.00, 'transferencia', 480.00, 0.00, 'completada', NULL, '2026-06-05 17:44:00', '2026-06-06 15:08:43', 1),
	(9, 'V-2026-00009', 2, 2, '2026-05-29', 'boleta', 'B001', '000009', 665.08, 0.00, 119.72, 784.80, 'yape', 784.80, 0.00, 'completada', NULL, '2026-05-29 19:30:00', '2026-06-06 15:08:43', 1),
	(10, 'V-2026-00010', 5, 2, '2026-05-25', 'ticket', 'B001', '000010', 228.81, 0.00, 41.19, 270.00, 'plin', 270.00, 0.00, 'completada', NULL, '2026-05-25 14:44:00', '2026-06-06 15:08:43', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
