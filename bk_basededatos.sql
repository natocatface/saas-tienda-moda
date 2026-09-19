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

-- Volcando datos para la tabla saas_tienda_moda.caja: ~10 rows (aproximadamente)
DELETE FROM `caja`;
INSERT INTO `caja` (`id`, `user_id`, `fecha`, `monto_inicial`, `monto_final`, `total_ventas`, `total_egresos`, `estado`, `apertura`, `cierre`, `observaciones`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 3, '2026-07-01', 200.00, 2204.30, 2004.30, 23.00, 'abierta', '2026-07-01 14:00:00', NULL, NULL, '2026-07-01 12:46:16', '2026-07-01 12:46:16', 1),
	(2, 2, '2026-06-30', 200.00, 3107.40, 2907.40, 30.00, 'cerrada', '2026-06-30 14:00:00', '2026-07-01 01:00:00', NULL, '2026-06-30 12:46:16', '2026-06-30 12:46:16', 1),
	(3, 3, '2026-06-29', 200.00, 1879.60, 1679.60, 10.00, 'cerrada', '2026-06-29 14:00:00', '2026-06-30 01:00:00', NULL, '2026-06-29 12:46:16', '2026-06-29 12:46:16', 1),
	(4, 5, '2026-06-28', 200.00, 1593.50, 1393.50, 58.00, 'cerrada', '2026-06-28 14:00:00', '2026-06-29 01:00:00', NULL, '2026-06-28 12:46:16', '2026-06-28 12:46:16', 1),
	(5, 3, '2026-06-27', 200.00, 2231.50, 2031.50, 43.00, 'cerrada', '2026-06-27 14:00:00', '2026-06-28 01:00:00', NULL, '2026-06-27 12:46:16', '2026-06-27 12:46:16', 1),
	(6, 4, '2026-06-26', 200.00, 1772.60, 1572.60, 55.00, 'cerrada', '2026-06-26 14:00:00', '2026-06-27 01:00:00', NULL, '2026-06-26 12:46:16', '2026-06-26 12:46:16', 1),
	(7, 4, '2026-06-25', 200.00, 2720.40, 2520.40, 5.00, 'cerrada', '2026-06-25 14:00:00', '2026-06-26 01:00:00', NULL, '2026-06-25 12:46:16', '2026-06-25 12:46:16', 1),
	(8, 5, '2026-06-24', 200.00, 585.00, 385.00, 29.00, 'cerrada', '2026-06-24 14:00:00', '2026-06-25 01:00:00', NULL, '2026-06-24 12:46:16', '2026-06-24 12:46:16', 1),
	(9, 3, '2026-06-23', 200.00, 529.90, 329.90, 40.00, 'cerrada', '2026-06-23 14:00:00', '2026-06-24 01:00:00', NULL, '2026-06-23 12:46:16', '2026-06-23 12:46:16', 1),
	(10, 5, '2026-06-22', 200.00, 944.40, 744.40, 43.00, 'cerrada', '2026-06-22 14:00:00', '2026-06-23 01:00:00', NULL, '2026-06-22 12:46:16', '2026-06-22 12:46:16', 1);

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
	(1, 'Camisas', 'camisas', NULL, NULL, 'caballeros', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(2, 'Blusas', 'blusas', NULL, NULL, 'damas', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(3, 'Pantalones', 'pantalones', NULL, NULL, 'unisex', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(4, 'Vestidos', 'vestidos', NULL, NULL, 'damas', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(5, 'Ropa Deportiva', 'deportiva', NULL, NULL, 'unisex', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(6, 'Ropa Niños', 'ninos', NULL, NULL, 'ninos', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(7, 'Chaquetas', 'chaquetas', NULL, NULL, 'unisex', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(8, 'Jeans', 'jeans', NULL, NULL, 'unisex', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(9, 'Faldas', 'faldas', NULL, NULL, 'damas', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(10, 'Accesorios', 'accesorios', NULL, NULL, 'unisex', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1);

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

-- Volcando datos para la tabla saas_tienda_moda.clientes: ~10 rows (aproximadamente)
DELETE FROM `clientes`;
INSERT INTO `clientes` (`id`, `codigo`, `nombre`, `apellido`, `dni`, `email`, `telefono`, `direccion`, `ciudad`, `fecha_nacimiento`, `genero`, `puntos`, `activo`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 'CLI-0001', 'Sofía', 'Gutiérrez', '65700115', 'sofía.gutiérrez@gmail.com', '912274593', NULL, 'Lima', NULL, 'F', 255.00, 1, '2026-06-10 12:46:14', '2026-07-01 12:46:14', 1),
	(2, 'CLI-0002', 'Mateo', 'Rojas', '64378638', 'mateo.rojas@gmail.com', '979814545', NULL, 'Arequipa', NULL, 'M', 303.00, 1, '2026-04-20 12:46:14', '2026-07-01 12:46:14', 1),
	(3, 'CLI-0003', 'Valentina', 'Flores', '57862255', 'valentina.flores@gmail.com', '959501126', NULL, 'Trujillo', NULL, 'F', 66.00, 1, '2026-06-13 12:46:14', '2026-07-01 12:46:14', 1),
	(4, 'CLI-0004', 'Diego', 'Castro', '44923311', 'diego.castro@gmail.com', '998970834', NULL, 'Lima', NULL, 'M', 113.00, 1, '2026-05-16 12:46:14', '2026-07-01 12:46:14', 1),
	(5, 'CLI-0005', 'Camila', 'Vargas', '68575102', 'camila.vargas@gmail.com', '987857556', NULL, 'Cusco', NULL, 'F', 386.00, 1, '2026-03-24 12:46:14', '2026-07-01 12:46:14', 1),
	(6, 'CLI-0006', 'Sebastián', 'Núñez', '63707793', 'sebastián.núñez@gmail.com', '931731951', NULL, 'Piura', NULL, 'M', 82.00, 1, '2026-05-12 12:46:14', '2026-07-01 12:46:14', 1),
	(7, 'CLI-0007', 'Isabella', 'Ramos', '56351663', 'isabella.ramos@gmail.com', '971386965', NULL, 'Lima', NULL, 'F', 250.00, 1, '2026-05-06 12:46:14', '2026-07-01 12:46:14', 1),
	(8, 'CLI-0008', 'Joaquín', 'Salazar', '48020847', 'joaquín.salazar@gmail.com', '929583062', NULL, 'Chiclayo', NULL, 'M', 250.00, 1, '2026-06-16 12:46:14', '2026-07-01 12:46:14', 1),
	(9, 'CLI-0009', 'Antonella', 'Reyes', '62719737', 'antonella.reyes@gmail.com', '958867871', NULL, 'Ica', NULL, 'F', 489.00, 1, '2026-04-16 12:46:14', '2026-07-01 12:46:14', 1),
	(10, 'CLI-0010', 'Thiago', 'Paredes', '43946944', 'thiago.paredes@gmail.com', '976138638', NULL, 'Lima', NULL, 'M', 101.00, 1, '2026-04-11 12:46:14', '2026-07-01 12:46:14', 1);

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

-- Volcando datos para la tabla saas_tienda_moda.compras: ~10 rows (aproximadamente)
DELETE FROM `compras`;
INSERT INTO `compras` (`id`, `numero_compra`, `proveedor_id`, `user_id`, `fecha`, `total`, `estado`, `observaciones`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 'C-2026-00001', 2, 2, '2026-06-25', 4650.00, 'recibido', 'Reposición de inventario', '2026-06-25 12:46:15', '2026-06-25 12:46:15', 1),
	(2, 'C-2026-00002', 1, 2, '2026-06-15', 2962.00, 'recibido', 'Reposición de inventario', '2026-06-15 12:46:15', '2026-06-15 12:46:15', 1),
	(3, 'C-2026-00003', 1, 2, '2026-06-03', 3685.00, 'recibido', 'Reposición de inventario', '2026-06-03 12:46:15', '2026-06-03 12:46:15', 1),
	(4, 'C-2026-00004', 2, 2, '2026-06-18', 4347.00, 'recibido', 'Reposición de inventario', '2026-06-18 12:46:16', '2026-06-18 12:46:16', 1),
	(5, 'C-2026-00005', 3, 2, '2026-06-02', 4960.00, 'recibido', 'Reposición de inventario', '2026-06-02 12:46:16', '2026-06-02 12:46:16', 1),
	(6, 'C-2026-00006', 5, 2, '2026-06-04', 1164.00, 'recibido', 'Reposición de inventario', '2026-06-04 12:46:16', '2026-06-04 12:46:16', 1),
	(7, 'C-2026-00007', 5, 2, '2026-05-23', 2694.00, 'recibido', 'Reposición de inventario', '2026-05-23 12:46:16', '2026-05-23 12:46:16', 1),
	(8, 'C-2026-00008', 1, 2, '2026-06-19', 3860.00, 'recibido', 'Reposición de inventario', '2026-06-19 12:46:16', '2026-06-19 12:46:16', 1),
	(9, 'C-2026-00009', 10, 2, '2026-06-30', 5940.00, 'recibido', 'Reposición de inventario', '2026-06-30 12:46:16', '2026-06-30 12:46:16', 1),
	(10, 'C-2026-00010', 3, 2, '2026-06-03', 4990.00, 'recibido', 'Reposición de inventario', '2026-06-03 12:46:16', '2026-06-03 12:46:16', 1);

-- Volcando estructura para tabla saas_tienda_moda.configuracion_facturacion
CREATE TABLE IF NOT EXISTS `configuracion_facturacion` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint unsigned NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `modo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'beta_demo',
  `ruc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sol_usuario` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sol_clave` text COLLATE utf8mb4_unicode_ci,
  `certificado_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificado_password` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `configuracion_facturacion_tienda_id_unique` (`tienda_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.configuracion_facturacion: ~0 rows (aproximadamente)
DELETE FROM `configuracion_facturacion`;

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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.detalle_compras: ~33 rows (aproximadamente)
DELETE FROM `detalle_compras`;
INSERT INTO `detalle_compras` (`id`, `compra_id`, `producto_id`, `variante_id`, `cantidad`, `precio_unitario`, `subtotal`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 1, 4, 11, 16, 60.00, 960.00, '2026-06-25 12:46:15', '2026-06-25 12:46:15', 1),
	(2, 1, 1, 3, 18, 45.00, 810.00, '2026-06-25 12:46:15', '2026-06-25 12:46:15', 1),
	(3, 1, 2, 6, 40, 38.00, 1520.00, '2026-06-25 12:46:15', '2026-06-25 12:46:15', 1),
	(4, 1, 7, 20, 17, 80.00, 1360.00, '2026-06-25 12:46:15', '2026-06-25 12:46:15', 1),
	(5, 2, 10, 28, 44, 15.00, 660.00, '2026-06-15 12:46:15', '2026-06-15 12:46:15', 1),
	(6, 2, 7, 19, 15, 80.00, 1200.00, '2026-06-15 12:46:15', '2026-06-15 12:46:15', 1),
	(7, 2, 2, 5, 29, 38.00, 1102.00, '2026-06-15 12:46:15', '2026-06-15 12:46:15', 1),
	(8, 3, 6, 18, 35, 20.00, 700.00, '2026-06-03 12:46:15', '2026-06-03 12:46:15', 1),
	(9, 3, 1, 1, 37, 45.00, 1665.00, '2026-06-03 12:46:15', '2026-06-03 12:46:15', 1),
	(10, 3, 6, 17, 44, 20.00, 880.00, '2026-06-03 12:46:15', '2026-06-03 12:46:15', 1),
	(11, 3, 6, 18, 22, 20.00, 440.00, '2026-06-03 12:46:15', '2026-06-03 12:46:15', 1),
	(12, 4, 5, 13, 33, 70.00, 2310.00, '2026-06-18 12:46:16', '2026-06-18 12:46:16', 1),
	(13, 4, 9, 25, 21, 42.00, 882.00, '2026-06-18 12:46:16', '2026-06-18 12:46:16', 1),
	(14, 4, 3, 9, 21, 55.00, 1155.00, '2026-06-18 12:46:16', '2026-06-18 12:46:16', 1),
	(15, 5, 2, 4, 34, 38.00, 1292.00, '2026-06-02 12:46:16', '2026-06-02 12:46:16', 1),
	(16, 5, 2, 4, 31, 38.00, 1178.00, '2026-06-02 12:46:16', '2026-06-02 12:46:16', 1),
	(17, 5, 4, 10, 38, 60.00, 2280.00, '2026-06-02 12:46:16', '2026-06-02 12:46:16', 1),
	(18, 5, 10, 28, 14, 15.00, 210.00, '2026-06-02 12:46:16', '2026-06-02 12:46:16', 1),
	(19, 6, 10, 28, 44, 15.00, 660.00, '2026-06-04 12:46:16', '2026-06-04 12:46:16', 1),
	(20, 6, 9, 26, 12, 42.00, 504.00, '2026-06-04 12:46:16', '2026-06-04 12:46:16', 1),
	(21, 7, 9, 26, 27, 42.00, 1134.00, '2026-05-23 12:46:16', '2026-05-23 12:46:16', 1),
	(22, 7, 4, 11, 26, 60.00, 1560.00, '2026-05-23 12:46:16', '2026-05-23 12:46:16', 1),
	(23, 8, 6, 18, 40, 20.00, 800.00, '2026-06-19 12:46:16', '2026-06-19 12:46:16', 1),
	(24, 8, 9, 25, 10, 42.00, 420.00, '2026-06-19 12:46:16', '2026-06-19 12:46:16', 1),
	(25, 8, 1, 2, 44, 45.00, 1980.00, '2026-06-19 12:46:16', '2026-06-19 12:46:16', 1),
	(26, 8, 4, 10, 11, 60.00, 660.00, '2026-06-19 12:46:16', '2026-06-19 12:46:16', 1),
	(27, 9, 3, 9, 38, 55.00, 2090.00, '2026-06-30 12:46:16', '2026-06-30 12:46:16', 1),
	(28, 9, 9, 25, 25, 42.00, 1050.00, '2026-06-30 12:46:16', '2026-06-30 12:46:16', 1),
	(29, 9, 8, 24, 14, 50.00, 700.00, '2026-06-30 12:46:16', '2026-06-30 12:46:16', 1),
	(30, 9, 5, 13, 30, 70.00, 2100.00, '2026-06-30 12:46:16', '2026-06-30 12:46:16', 1),
	(31, 10, 8, 23, 33, 50.00, 1650.00, '2026-06-03 12:46:16', '2026-06-03 12:46:16', 1),
	(32, 10, 7, 20, 17, 80.00, 1360.00, '2026-06-03 12:46:16', '2026-06-03 12:46:16', 1),
	(33, 10, 1, 1, 44, 45.00, 1980.00, '2026-06-03 12:46:16', '2026-06-03 12:46:16', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.detalle_ventas: ~113 rows (aproximadamente)
DELETE FROM `detalle_ventas`;
INSERT INTO `detalle_ventas` (`id`, `venta_id`, `producto_id`, `variante_id`, `producto_nombre`, `talla`, `color`, `cantidad`, `precio_unitario`, `descuento`, `subtotal`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 1, 9, 27, 'Falda Plisada Midi', 'L', 'Beige', 2, 95.00, 0.00, 190.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(2, 1, 8, 22, 'Jean Skinny Azul', 'S', 'Blanco', 3, 110.00, 0.00, 330.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(3, 1, 3, 7, 'Pantalón Chino Beige', 'S', 'Azul', 3, 120.00, 0.00, 360.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(4, 1, 1, 2, 'Camisa Slim Fit Cuadros', 'M', 'Blanco', 1, 89.90, 0.00, 89.90, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(5, 2, 4, 11, 'Vestido Casual Verano', 'M', 'Beige', 3, 145.00, 0.00, 435.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(6, 3, 6, 16, 'Polo Niño Estampado', 'S', 'Verde', 3, 39.90, 0.00, 119.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(7, 3, 5, 14, 'Conjunto Deportivo Running', 'M', 'Verde', 3, 159.90, 0.00, 479.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(8, 4, 4, 12, 'Vestido Casual Verano', 'L', 'Verde', 2, 145.00, 0.00, 290.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(9, 4, 5, 13, 'Conjunto Deportivo Running', 'S', 'Beige', 1, 159.90, 0.00, 159.90, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(10, 4, 3, 9, 'Pantalón Chino Beige', 'L', 'Beige', 1, 120.00, 0.00, 120.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(11, 4, 7, 20, 'Chaqueta Jean Oversize', 'M', 'Blanco', 3, 189.00, 0.00, 567.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(12, 5, 7, 19, 'Chaqueta Jean Oversize', 'S', 'Negro', 1, 189.00, 0.00, 189.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(13, 5, 5, 14, 'Conjunto Deportivo Running', 'M', 'Verde', 2, 159.90, 0.00, 319.80, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(14, 5, 1, 3, 'Camisa Slim Fit Cuadros', 'L', 'Azul', 1, 89.90, 0.00, 89.90, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(15, 6, 9, 25, 'Falda Plisada Midi', 'S', 'Azul', 3, 95.00, 0.00, 285.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(16, 6, 5, 13, 'Conjunto Deportivo Running', 'S', 'Beige', 2, 159.90, 0.00, 319.80, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(17, 6, 7, 21, 'Chaqueta Jean Oversize', 'L', 'Azul', 3, 189.00, 0.00, 567.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(18, 7, 1, 2, 'Camisa Slim Fit Cuadros', 'M', 'Blanco', 1, 89.90, 0.00, 89.90, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(19, 8, 4, 11, 'Vestido Casual Verano', 'M', 'Beige', 3, 145.00, 0.00, 435.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(20, 8, 3, 8, 'Pantalón Chino Beige', 'M', 'Rojo', 2, 120.00, 0.00, 240.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(21, 9, 3, 7, 'Pantalón Chino Beige', 'S', 'Azul', 2, 120.00, 0.00, 240.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(22, 9, 5, 13, 'Conjunto Deportivo Running', 'S', 'Beige', 3, 159.90, 0.00, 479.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(23, 9, 10, 28, 'Gorra Snapback Urbana', 'S', 'Rojo', 1, 45.00, 0.00, 45.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(24, 9, 2, 4, 'Blusa Floral Manga Larga', 'S', 'Blanco', 2, 75.00, 0.00, 150.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(25, 10, 4, 10, 'Vestido Casual Verano', 'S', 'Rojo', 1, 145.00, 0.00, 145.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(26, 10, 7, 20, 'Chaqueta Jean Oversize', 'M', 'Blanco', 1, 189.00, 0.00, 189.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(27, 11, 10, 28, 'Gorra Snapback Urbana', 'S', 'Rojo', 3, 45.00, 0.00, 135.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(28, 11, 2, 5, 'Blusa Floral Manga Larga', 'M', 'Azul', 3, 75.00, 0.00, 225.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(29, 12, 5, 15, 'Conjunto Deportivo Running', 'L', 'Negro', 2, 159.90, 0.00, 319.80, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(30, 12, 1, 2, 'Camisa Slim Fit Cuadros', 'M', 'Blanco', 3, 89.90, 0.00, 269.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(31, 12, 8, 22, 'Jean Skinny Azul', 'S', 'Blanco', 1, 110.00, 0.00, 110.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(32, 13, 3, 8, 'Pantalón Chino Beige', 'M', 'Rojo', 1, 120.00, 0.00, 120.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(33, 13, 5, 15, 'Conjunto Deportivo Running', 'L', 'Negro', 1, 159.90, 0.00, 159.90, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(34, 13, 7, 19, 'Chaqueta Jean Oversize', 'S', 'Negro', 1, 189.00, 0.00, 189.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(35, 14, 8, 24, 'Jean Skinny Azul', 'L', 'Rojo', 1, 110.00, 0.00, 110.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(36, 14, 4, 11, 'Vestido Casual Verano', 'M', 'Beige', 2, 145.00, 0.00, 290.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(37, 14, 7, 20, 'Chaqueta Jean Oversize', 'M', 'Blanco', 2, 189.00, 0.00, 378.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(38, 15, 5, 13, 'Conjunto Deportivo Running', 'S', 'Beige', 2, 159.90, 0.00, 319.80, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(39, 15, 9, 26, 'Falda Plisada Midi', 'M', 'Rojo', 3, 95.00, 0.00, 285.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(40, 15, 1, 1, 'Camisa Slim Fit Cuadros', 'S', 'Negro', 2, 89.90, 0.00, 179.80, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(41, 16, 5, 15, 'Conjunto Deportivo Running', 'L', 'Negro', 2, 159.90, 0.00, 319.80, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(42, 17, 9, 25, 'Falda Plisada Midi', 'S', 'Azul', 3, 95.00, 0.00, 285.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(43, 17, 3, 9, 'Pantalón Chino Beige', 'L', 'Beige', 1, 120.00, 0.00, 120.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(44, 17, 1, 1, 'Camisa Slim Fit Cuadros', 'S', 'Negro', 2, 89.90, 0.00, 179.80, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(45, 17, 7, 21, 'Chaqueta Jean Oversize', 'L', 'Azul', 2, 189.00, 0.00, 378.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(46, 18, 4, 10, 'Vestido Casual Verano', 'S', 'Rojo', 2, 145.00, 0.00, 290.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(47, 19, 10, 30, 'Gorra Snapback Urbana', 'L', 'Verde', 2, 45.00, 0.00, 90.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(48, 19, 9, 27, 'Falda Plisada Midi', 'L', 'Beige', 1, 95.00, 0.00, 95.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(49, 19, 8, 23, 'Jean Skinny Azul', 'M', 'Azul', 1, 110.00, 0.00, 110.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(50, 20, 4, 12, 'Vestido Casual Verano', 'L', 'Verde', 3, 145.00, 0.00, 435.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(51, 20, 1, 2, 'Camisa Slim Fit Cuadros', 'M', 'Blanco', 3, 89.90, 0.00, 269.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(52, 20, 10, 29, 'Gorra Snapback Urbana', 'M', 'Beige', 3, 45.00, 0.00, 135.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(53, 20, 7, 21, 'Chaqueta Jean Oversize', 'L', 'Azul', 1, 189.00, 0.00, 189.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(54, 21, 1, 1, 'Camisa Slim Fit Cuadros', 'S', 'Negro', 3, 89.90, 0.00, 269.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(55, 21, 7, 20, 'Chaqueta Jean Oversize', 'M', 'Blanco', 3, 189.00, 0.00, 567.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(56, 21, 3, 7, 'Pantalón Chino Beige', 'S', 'Azul', 3, 120.00, 0.00, 360.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(57, 22, 9, 27, 'Falda Plisada Midi', 'L', 'Beige', 1, 95.00, 0.00, 95.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(58, 22, 4, 11, 'Vestido Casual Verano', 'M', 'Beige', 2, 145.00, 0.00, 290.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(59, 23, 1, 3, 'Camisa Slim Fit Cuadros', 'L', 'Azul', 1, 89.90, 0.00, 89.90, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(60, 23, 3, 9, 'Pantalón Chino Beige', 'L', 'Beige', 2, 120.00, 0.00, 240.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(61, 24, 6, 17, 'Polo Niño Estampado', 'M', 'Negro', 3, 39.90, 0.00, 119.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(62, 24, 4, 11, 'Vestido Casual Verano', 'M', 'Beige', 1, 145.00, 0.00, 145.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(63, 24, 5, 15, 'Conjunto Deportivo Running', 'L', 'Negro', 3, 159.90, 0.00, 479.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(64, 25, 4, 10, 'Vestido Casual Verano', 'S', 'Rojo', 2, 145.00, 0.00, 290.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(65, 25, 5, 13, 'Conjunto Deportivo Running', 'S', 'Beige', 3, 159.90, 0.00, 479.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(66, 25, 10, 28, 'Gorra Snapback Urbana', 'S', 'Rojo', 2, 45.00, 0.00, 90.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(67, 26, 8, 24, 'Jean Skinny Azul', 'L', 'Rojo', 3, 110.00, 0.00, 330.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(68, 27, 8, 23, 'Jean Skinny Azul', 'M', 'Azul', 2, 110.00, 0.00, 220.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(69, 28, 9, 26, 'Falda Plisada Midi', 'M', 'Rojo', 1, 95.00, 0.00, 95.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(70, 29, 5, 15, 'Conjunto Deportivo Running', 'L', 'Negro', 3, 159.90, 0.00, 479.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(71, 29, 6, 18, 'Polo Niño Estampado', 'L', 'Blanco', 2, 39.90, 0.00, 79.80, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(72, 29, 1, 1, 'Camisa Slim Fit Cuadros', 'S', 'Negro', 3, 89.90, 0.00, 269.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(73, 30, 9, 25, 'Falda Plisada Midi', 'S', 'Azul', 3, 95.00, 0.00, 285.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(74, 30, 4, 10, 'Vestido Casual Verano', 'S', 'Rojo', 1, 145.00, 0.00, 145.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(75, 30, 8, 24, 'Jean Skinny Azul', 'L', 'Rojo', 1, 110.00, 0.00, 110.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(76, 30, 2, 4, 'Blusa Floral Manga Larga', 'S', 'Blanco', 1, 75.00, 0.00, 75.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(77, 31, 3, 8, 'Pantalón Chino Beige', 'M', 'Rojo', 3, 120.00, 0.00, 360.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(78, 31, 2, 6, 'Blusa Floral Manga Larga', 'L', 'Rojo', 1, 75.00, 0.00, 75.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(79, 32, 5, 13, 'Conjunto Deportivo Running', 'S', 'Beige', 3, 159.90, 0.00, 479.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(80, 33, 3, 7, 'Pantalón Chino Beige', 'S', 'Azul', 2, 120.00, 0.00, 240.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(81, 33, 1, 1, 'Camisa Slim Fit Cuadros', 'S', 'Negro', 3, 89.90, 0.00, 269.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(82, 34, 7, 21, 'Chaqueta Jean Oversize', 'L', 'Azul', 2, 189.00, 0.00, 378.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(83, 34, 10, 29, 'Gorra Snapback Urbana', 'M', 'Beige', 2, 45.00, 0.00, 90.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(84, 35, 10, 28, 'Gorra Snapback Urbana', 'S', 'Rojo', 1, 45.00, 0.00, 45.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(85, 35, 8, 23, 'Jean Skinny Azul', 'M', 'Azul', 1, 110.00, 0.00, 110.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(86, 36, 7, 20, 'Chaqueta Jean Oversize', 'M', 'Blanco', 3, 189.00, 0.00, 567.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(87, 36, 8, 23, 'Jean Skinny Azul', 'M', 'Azul', 3, 110.00, 0.00, 330.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(88, 37, 4, 10, 'Vestido Casual Verano', 'S', 'Rojo', 3, 145.00, 0.00, 435.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(89, 38, 3, 7, 'Pantalón Chino Beige', 'S', 'Azul', 1, 120.00, 0.00, 120.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(90, 39, 5, 14, 'Conjunto Deportivo Running', 'M', 'Verde', 1, 159.90, 0.00, 159.90, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(91, 40, 2, 5, 'Blusa Floral Manga Larga', 'M', 'Azul', 1, 75.00, 0.00, 75.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(92, 40, 7, 19, 'Chaqueta Jean Oversize', 'S', 'Negro', 2, 189.00, 0.00, 378.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(93, 41, 6, 16, 'Polo Niño Estampado', 'S', 'Verde', 2, 39.90, 0.00, 79.80, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(94, 42, 8, 22, 'Jean Skinny Azul', 'S', 'Blanco', 1, 110.00, 0.00, 110.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(95, 43, 7, 21, 'Chaqueta Jean Oversize', 'L', 'Azul', 2, 189.00, 0.00, 378.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(96, 43, 5, 15, 'Conjunto Deportivo Running', 'L', 'Negro', 3, 159.90, 0.00, 479.70, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(97, 44, 7, 19, 'Chaqueta Jean Oversize', 'S', 'Negro', 2, 189.00, 0.00, 378.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(98, 44, 8, 23, 'Jean Skinny Azul', 'M', 'Azul', 3, 110.00, 0.00, 330.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(99, 44, 3, 9, 'Pantalón Chino Beige', 'L', 'Beige', 1, 120.00, 0.00, 120.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(100, 45, 4, 12, 'Vestido Casual Verano', 'L', 'Verde', 2, 145.00, 0.00, 290.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(101, 45, 8, 24, 'Jean Skinny Azul', 'L', 'Rojo', 3, 110.00, 0.00, 330.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(102, 45, 3, 8, 'Pantalón Chino Beige', 'M', 'Rojo', 2, 120.00, 0.00, 240.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(103, 45, 9, 25, 'Falda Plisada Midi', 'S', 'Azul', 2, 95.00, 0.00, 190.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(104, 46, 7, 19, 'Chaqueta Jean Oversize', 'S', 'Negro', 2, 189.00, 0.00, 378.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(105, 47, 1, 1, 'Camisa Slim Fit Cuadros', 'S', 'Negro', 1, 89.90, 0.00, 89.90, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(106, 48, 3, 9, 'Pantalón Chino Beige', 'L', 'Beige', 2, 120.00, 0.00, 240.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(107, 49, 1, 1, 'Camisa Slim Fit Cuadros', 'S', 'Negro', 1, 89.90, 0.00, 89.90, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(108, 49, 7, 20, 'Chaqueta Jean Oversize', 'M', 'Blanco', 3, 189.00, 0.00, 567.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(109, 50, 2, 6, 'Blusa Floral Manga Larga', 'L', 'Rojo', 2, 75.00, 0.00, 150.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(110, 51, 10, 29, 'Gorra Snapback Urbana', 'M', 'Beige', 3, 45.00, 0.00, 135.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(111, 52, 3, 8, 'Pantalón Chino Beige', 'M', 'Rojo', 2, 120.00, 0.00, 240.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(112, 52, 7, 20, 'Chaqueta Jean Oversize', 'M', 'Blanco', 2, 189.00, 0.00, 378.00, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1),
	(113, 53, 6, 16, 'Polo Niño Estampado', 'S', 'Verde', 1, 39.90, 0.00, 39.90, '2026-07-01 12:46:15', '2026-07-01 12:46:15', 1);

-- Volcando estructura para tabla saas_tienda_moda.facturas_electronicas
CREATE TABLE IF NOT EXISTS `facturas_electronicas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint unsigned DEFAULT NULL,
  `venta_id` bigint unsigned NOT NULL,
  `documento_afectado_id` bigint unsigned DEFAULT NULL,
  `pais` char(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PE',
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BOLETA',
  `serie` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correlativo` bigint unsigned NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'EN_PROCESO',
  `codigo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_fiscal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr` text COLLATE utf8mb4_unicode_ci,
  `ticket` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `xml_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cdr_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `facturas_electronicas_tienda_id_index` (`tienda_id`),
  KEY `facturas_electronicas_venta_id_index` (`venta_id`),
  KEY `facturas_electronicas_documento_afectado_id_index` (`documento_afectado_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.facturas_electronicas: ~0 rows (aproximadamente)
DELETE FROM `facturas_electronicas`;

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
	(1, 'Zara', NULL, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(2, 'H&M', NULL, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(3, 'Adidas', NULL, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(4, 'Nike', NULL, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(5, 'Polo', NULL, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(6, 'Tommy Hilfiger', NULL, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(7, 'Levis', NULL, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(8, 'Forever 21', NULL, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1);

-- Volcando estructura para tabla saas_tienda_moda.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.migrations: ~0 rows (aproximadamente)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(2, '2024_01_01_000000_create_users_table', 1),
	(3, '2024_01_01_000001_create_catalogo_tables', 1),
	(4, '2024_01_01_000002_create_clientes_ventas_tables', 1),
	(5, '2024_01_02_000000_create_saas_tables', 1),
	(6, '2024_01_03_000000_add_config_to_tiendas', 1),
	(7, '2024_01_04_000000_create_movimientos_caja_table', 2),
	(8, '2024_01_05_000000_create_password_reset_tokens_table', 2),
	(9, '2024_01_06_000000_add_tienda_to_subcategorias', 2),
	(10, '2024_01_07_000000_create_pagos_table', 2),
	(11, '2026_07_07_000000_create_facturas_electronicas_table', 3),
	(12, '2026_07_07_000001_facturas_electronicas_fase11', 3),
	(13, '2026_07_07_000002_add_qr_to_facturas_electronicas', 4),
	(14, '2026_07_08_000000_create_configuracion_facturacion_table', 5);

-- Volcando estructura para tabla saas_tienda_moda.movimientos_caja
CREATE TABLE IF NOT EXISTS `movimientos_caja` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint unsigned DEFAULT NULL,
  `caja_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `tipo` enum('ingreso','egreso') COLLATE utf8mb4_unicode_ci NOT NULL,
  `concepto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_caja_caja_id_foreign` (`caja_id`),
  KEY `movimientos_caja_user_id_foreign` (`user_id`),
  KEY `movimientos_caja_tienda_id_index` (`tienda_id`),
  CONSTRAINT `movimientos_caja_caja_id_foreign` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movimientos_caja_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.movimientos_caja: ~0 rows (aproximadamente)
DELETE FROM `movimientos_caja`;

-- Volcando estructura para tabla saas_tienda_moda.pagos
CREATE TABLE IF NOT EXISTS `pagos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint unsigned DEFAULT NULL,
  `plan_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `periodo_meses` tinyint unsigned NOT NULL DEFAULT '1',
  `metodo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tarjeta',
  `referencia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('pendiente','pagado','fallido') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pagado',
  `periodo_inicio` date DEFAULT NULL,
  `periodo_fin` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pagos_numero_unique` (`numero`),
  KEY `pagos_plan_id_foreign` (`plan_id`),
  KEY `pagos_user_id_foreign` (`user_id`),
  KEY `pagos_tienda_id_index` (`tienda_id`),
  CONSTRAINT `pagos_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pagos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.pagos: ~0 rows (aproximadamente)
DELETE FROM `pagos`;

-- Volcando estructura para tabla saas_tienda_moda.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.password_reset_tokens: ~0 rows (aproximadamente)
DELETE FROM `password_reset_tokens`;

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

-- Volcando datos para la tabla saas_tienda_moda.planes: ~2 rows (aproximadamente)
DELETE FROM `planes`;
INSERT INTO `planes` (`id`, `nombre`, `slug`, `precio`, `max_productos`, `max_usuarios`, `max_ventas_mes`, `descripcion`, `caracteristicas`, `activo`, `created_at`, `updated_at`) VALUES
	(1, 'Básico', 'basico', 49.00, 50, 2, 200, 'Ideal para empezar', '["1 sucursal", "Soporte por correo", "Reportes básicos"]', 1, '2026-06-12 13:46:21', '2026-06-12 13:46:21'),
	(2, 'Pro', 'pro', 99.00, 500, 8, -1, 'Para tiendas en crecimiento', '["3 sucursales", "Soporte prioritario", "Reportes avanzados", "Gestión de compras"]', 1, '2026-06-12 13:46:21', '2026-06-12 13:46:21'),
	(3, 'Premium', 'premium', 199.00, -1, -1, -1, 'Sin límites', '["Sucursales ilimitadas", "Soporte 24/7", "Todos los reportes", "API e integraciones"]', 1, '2026-06-12 13:46:21', '2026-06-12 13:46:21');

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

-- Volcando datos para la tabla saas_tienda_moda.productos: ~10 rows (aproximadamente)
DELETE FROM `productos`;
INSERT INTO `productos` (`id`, `categoria_id`, `subcategoria_id`, `marca_id`, `proveedor_id`, `codigo`, `nombre`, `descripcion`, `genero`, `tipo`, `precio_compra`, `precio_venta`, `precio_oferta`, `imagen`, `activo`, `destacado`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 1, NULL, 7, 9, 'PRD-0001', 'Camisa Slim Fit Cuadros', 'Camisa Slim Fit Cuadros de excelente calidad.', 'caballeros', 'casual', 45.00, 89.90, NULL, NULL, 1, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(2, 2, NULL, 3, 3, 'PRD-0002', 'Blusa Floral Manga Larga', 'Blusa Floral Manga Larga de excelente calidad.', 'damas', 'casual', 38.00, 75.00, NULL, NULL, 1, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(3, 3, NULL, 4, 8, 'PRD-0003', 'Pantalón Chino Beige', 'Pantalón Chino Beige de excelente calidad.', 'caballeros', 'casual', 55.00, 120.00, NULL, NULL, 1, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(4, 4, NULL, 7, 10, 'PRD-0004', 'Vestido Casual Verano', 'Vestido Casual Verano de excelente calidad.', 'damas', 'casual', 60.00, 145.00, NULL, NULL, 1, 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(5, 5, NULL, 3, 8, 'PRD-0005', 'Conjunto Deportivo Running', 'Conjunto Deportivo Running de excelente calidad.', 'unisex', 'deportivo', 70.00, 159.90, NULL, NULL, 1, 0, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(6, 6, NULL, 3, 10, 'PRD-0006', 'Polo Niño Estampado', 'Polo Niño Estampado de excelente calidad.', 'ninos', 'casual', 20.00, 39.90, NULL, NULL, 1, 0, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(7, 7, NULL, 4, 7, 'PRD-0007', 'Chaqueta Jean Oversize', 'Chaqueta Jean Oversize de excelente calidad.', 'unisex', 'casual', 80.00, 189.00, NULL, NULL, 1, 0, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(8, 8, NULL, 4, 8, 'PRD-0008', 'Jean Skinny Azul', 'Jean Skinny Azul de excelente calidad.', 'damas', 'casual', 50.00, 110.00, NULL, NULL, 1, 0, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(9, 9, NULL, 3, 9, 'PRD-0009', 'Falda Plisada Midi', 'Falda Plisada Midi de excelente calidad.', 'damas', 'formal', 42.00, 95.00, NULL, NULL, 1, 0, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(10, 10, NULL, 1, 1, 'PRD-0010', 'Gorra Snapback Urbana', 'Gorra Snapback Urbana de excelente calidad.', 'unisex', 'casual', 15.00, 45.00, NULL, NULL, 1, 0, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.producto_variantes: ~30 rows (aproximadamente)
DELETE FROM `producto_variantes`;
INSERT INTO `producto_variantes` (`id`, `producto_id`, `talla`, `color`, `codigo_barra`, `stock`, `stock_minimo`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 1, 'S', 'Negro', '7844758069', 42, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(2, 1, 'M', 'Blanco', '7861361387', 54, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(3, 1, 'L', 'Azul', '7858275113', 28, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(4, 2, 'S', 'Blanco', '7851588870', 48, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(5, 2, 'M', 'Azul', '7829486943', 26, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(6, 2, 'L', 'Rojo', '7852022842', 64, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(7, 3, 'S', 'Azul', '7840941711', 59, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(8, 3, 'M', 'Rojo', '7881432346', 3, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(9, 3, 'L', 'Beige', '7884208742', 76, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(10, 4, 'S', 'Rojo', '7820616528', 45, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(11, 4, 'M', 'Beige', '7898272472', 32, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(12, 4, 'L', 'Verde', '7845568009', 37, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(13, 5, 'S', 'Beige', '7819479637', 26, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(14, 5, 'M', 'Verde', '7894573594', 61, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(15, 5, 'L', 'Negro', '7869684338', 30, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(16, 6, 'S', 'Verde', '7840618424', 42, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(17, 6, 'M', 'Negro', '7828020399', 77, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(18, 6, 'L', 'Blanco', '7897140212', 65, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(19, 7, 'S', 'Negro', '7841178603', 77, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(20, 7, 'M', 'Blanco', '7867402971', 54, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(21, 7, 'L', 'Azul', '7850471423', 42, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(22, 8, 'S', 'Blanco', '7855011629', 61, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(23, 8, 'M', 'Azul', '7856521424', 34, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(24, 8, 'L', 'Rojo', '7829756339', 72, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(25, 9, 'S', 'Azul', '7858091191', 43, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(26, 9, 'M', 'Rojo', '7815303057', 60, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(27, 9, 'L', 'Beige', '7823631582', 3, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(28, 10, 'S', 'Rojo', '7877112275', 70, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(29, 10, 'M', 'Beige', '7895935945', 63, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(30, 10, 'L', 'Verde', '7841168828', 3, 5, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1);

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

-- Volcando datos para la tabla saas_tienda_moda.proveedores: ~10 rows (aproximadamente)
DELETE FROM `proveedores`;
INSERT INTO `proveedores` (`id`, `nombre`, `ruc`, `email`, `telefono`, `direccion`, `ciudad`, `pais`, `contacto`, `activo`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 'Textiles Andinos SAC', '20512345678', 'ventas1@proveedor.com', '01-4964936', 'Av. Industrial 673', 'Lima', 'Perú', 'Juan Pérez', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(2, 'Moda Import EIRL', '20587654321', 'ventas2@proveedor.com', '01-2443899', 'Av. Industrial 967', 'Lima', 'Perú', 'Pedro Díaz', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(3, 'Distribuidora Fashion Perú', '20498765432', 'ventas3@proveedor.com', '01-2351932', 'Av. Industrial 801', 'Arequipa', 'Perú', 'Juan Pérez', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(4, 'Confecciones del Sur', '20456781234', 'ventas4@proveedor.com', '01-5135186', 'Av. Industrial 336', 'Cusco', 'Perú', 'Luis Vega', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(5, 'Comercial Estilo SAC', '20467812345', 'ventas5@proveedor.com', '01-4277870', 'Av. Industrial 553', 'Trujillo', 'Perú', 'Pedro Díaz', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(6, 'Importaciones Glamour', '20478123456', 'ventas6@proveedor.com', '01-4127172', 'Av. Industrial 525', 'Lima', 'Perú', 'María López', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(7, 'Ropa & Más Distribución', '20489234567', 'ventas7@proveedor.com', '01-6203619', 'Av. Industrial 957', 'Piura', 'Perú', 'Rosa Gil', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(8, 'Tendencias Textiles SAC', '20490345678', 'ventas8@proveedor.com', '01-5406775', 'Av. Industrial 871', 'Chiclayo', 'Perú', 'Luis Vega', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(9, 'Grupo Vestir Perú', '20501456789', 'ventas9@proveedor.com', '01-3539188', 'Av. Industrial 966', 'Lima', 'Perú', 'Luis Vega', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1),
	(10, 'Almacenes Bella Moda', '20512567890', 'ventas10@proveedor.com', '01-4669431', 'Av. Industrial 728', 'Ica', 'Perú', 'Juan Pérez', 1, '2026-07-01 12:46:14', '2026-07-01 12:46:14', 1);

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
	(1, 'Administrador', 'Acceso total a la tienda', '2026-06-12 13:46:21', '2026-06-12 13:46:21'),
	(2, 'Vendedor', 'Gestión de ventas y clientes', '2026-06-12 13:46:21', '2026-06-12 13:46:21'),
	(3, 'Almacén', 'Gestión de inventario', '2026-06-12 13:46:21', '2026-06-12 13:46:21');

-- Volcando estructura para tabla saas_tienda_moda.subcategorias
CREATE TABLE IF NOT EXISTS `subcategorias` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tienda_id` bigint unsigned DEFAULT NULL,
  `categoria_id` bigint unsigned NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subcategorias_slug_unique` (`slug`),
  KEY `subcategorias_categoria_id_foreign` (`categoria_id`),
  KEY `subcategorias_tienda_id_index` (`tienda_id`),
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
	(1, 'Moda Demo', 'tienda-demo', NULL, 'admin@tiendamoda.com', '01-7000000', 'Av. La Moda 123, Lima', NULL, 18.00, 'PEN', 'S/', 'B001', 'F001', 2, 'activa', '2026-05-01', '2027-07-01', '2026-06-12 13:46:21', '2026-07-01 12:46:13');

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.users: ~16 rows (aproximadamente)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `rol_id`, `tienda_id`, `name`, `email`, `telefono`, `avatar`, `email_verified_at`, `password`, `activo`, `es_super_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 1, NULL, 'Super Admin', 'superadmin@tiendamoda.com', NULL, NULL, NULL, '$2y$12$d69/12CEdpvcAZgzkxpp0udEfhPD04fEZ3AFAVwoGy0pCemflrteG', 1, 1, NULL, '2026-06-12 13:46:21', '2026-07-01 12:46:13'),
	(2, 1, 1, 'Administrador', 'admin@tiendamoda.com', NULL, NULL, NULL, '$2y$12$t2pQ.wFpy5ags7SDqznH8ePcvAsVVfMTAqCLx1deAxL7q7dPrDSIK', 1, 0, NULL, '2026-06-12 13:46:21', '2026-07-01 12:46:13'),
	(3, 2, 1, 'Lucía Torres', 'vendedor1@tiendamoda.com', NULL, NULL, NULL, '$2y$12$ROtu4cP3HB0qHTJfy3TtlOiD0PC0Xin1XAuehBU4Ii8HgMArKyMzu', 1, 0, NULL, '2026-06-12 13:46:22', '2026-07-01 12:46:14'),
	(4, 2, 1, 'Carlos Mendoza', 'vendedor2@tiendamoda.com', NULL, NULL, NULL, '$2y$12$msGA1CPM0yGOYhExU6kkTOkPDlsw2gXerkyBz2fB7Qhtm7FU7gamK', 1, 0, NULL, '2026-06-12 13:46:22', '2026-07-01 12:46:14'),
	(5, 2, 1, 'Ana Ramírez', 'vendedor3@tiendamoda.com', NULL, NULL, NULL, '$2y$12$KpiJdLvxFKclr/QfX2.W0.JT6uBhSYswbRSgWpS67zmemIQnTjuk6', 1, 0, NULL, '2026-06-12 13:46:22', '2026-07-01 12:46:14'),
	(6, 2, 1, 'Pedro Sánchez', 'vendedor4@tiendamoda.com', NULL, NULL, NULL, '$2y$12$GchmoyVtgkbt0CkGSFIYR.Dw0Tve3fq98mgIhSb9V4KXGlUtE2L5y', 1, 0, NULL, '2026-06-12 13:46:23', '2026-06-12 13:46:23'),
	(7, 2, 1, 'Usuario Nuevo 1', 'usuarioextra1_1781876957@tiendamoda.com', NULL, NULL, NULL, '$2y$12$fgD.Tn3HspTQ5Sj92eFXlOajBuSnlueIy39TXhV4RSljqMjJh4jim', 1, 0, NULL, '2026-06-19 13:49:17', '2026-06-19 13:49:17'),
	(8, 2, 1, 'Usuario Nuevo 2', 'usuarioextra2_1781876957@tiendamoda.com', NULL, NULL, NULL, '$2y$12$eS0JlaDRmSshEHXskqX9TeZmWafzHNfZVi0/L.6ITUmSqTBmVg4yC', 1, 0, NULL, '2026-06-19 13:49:18', '2026-06-19 13:49:18'),
	(9, 2, 1, 'Usuario Nuevo 3', 'usuarioextra3_1781876958@tiendamoda.com', NULL, NULL, NULL, '$2y$12$pHHMuxEmlMr3sFwkL7xEu.SMlAOSHyaPujQf8KHNKmRC9kAMzm4te', 1, 0, NULL, '2026-06-19 13:49:18', '2026-06-19 13:49:18'),
	(10, 2, 1, 'Usuario Nuevo 4', 'usuarioextra4_1781876958@tiendamoda.com', NULL, NULL, NULL, '$2y$12$JTJxCtUCX6YBnaKuy9ziFOqM3ehKpy82hh4CIt2/ycxZyq4LLVXnS', 1, 0, NULL, '2026-06-19 13:49:18', '2026-06-19 13:49:18'),
	(11, 2, 1, 'Usuario Nuevo 5', 'usuarioextra5_1781876958@tiendamoda.com', NULL, NULL, NULL, '$2y$12$fQy5xe7GreE1PPDwJzFAreSRM8K8LI0tJvNE5AZySrPYMqQ8t3x.6', 1, 0, NULL, '2026-06-19 13:49:19', '2026-06-19 13:49:19'),
	(12, 2, 1, 'Usuario Nuevo 6', 'usuarioextra6_1781876959@tiendamoda.com', NULL, NULL, NULL, '$2y$12$RVd1b8uuU/0UAD4keCzy0OROkqjZ5P8ZTbWXHA7QMSGSrn2tSiXtC', 1, 0, NULL, '2026-06-19 13:49:19', '2026-06-19 13:49:19'),
	(13, 2, 1, 'Usuario Nuevo 7', 'usuarioextra7_1781876959@tiendamoda.com', NULL, NULL, NULL, '$2y$12$LT8u6fTyR9NOsT7lOwwoiu6bt1BglRqn0vcr.2FldkQa2Y6c2WyjK', 1, 0, NULL, '2026-06-19 13:49:20', '2026-06-19 13:49:20'),
	(14, 2, 1, 'Usuario Nuevo 8', 'usuarioextra8_1781876960@tiendamoda.com', NULL, NULL, NULL, '$2y$12$FvIyEQ78dOsJZj5NGn71DOnIbqLfw17omtWMwim2Q7XzZ4PWi8ryG', 1, 0, NULL, '2026-06-19 13:49:20', '2026-06-19 13:49:20'),
	(15, 2, 1, 'Usuario Nuevo 9', 'usuarioextra9_1781876960@tiendamoda.com', NULL, NULL, NULL, '$2y$12$6fWvDm0qC/ZZZbfI6KAgPONnaEjxRwpDWHqZjZe/zMOpfk2wQ30ZK', 1, 0, NULL, '2026-06-19 13:49:21', '2026-06-19 13:49:21'),
	(16, 2, 1, 'Usuario Nuevo 10', 'usuarioextra10_1781876961@tiendamoda.com', NULL, NULL, NULL, '$2y$12$gu91cXfFd8lhYo4stHR3O.ELtp.wiMIsPsjx4fcljpJyBYQa9voki', 1, 0, NULL, '2026-06-19 13:49:22', '2026-06-19 13:49:22');

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
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla saas_tienda_moda.ventas: ~53 rows (aproximadamente)
DELETE FROM `ventas`;
INSERT INTO `ventas` (`id`, `numero_venta`, `cliente_id`, `user_id`, `fecha`, `tipo_comprobante`, `serie`, `correlativo`, `subtotal`, `descuento`, `igv`, `total`, `metodo_pago`, `monto_pagado`, `vuelto`, `estado`, `observaciones`, `created_at`, `updated_at`, `tienda_id`) VALUES
	(1, 'V-2026-00001', 7, 3, '2026-07-01', 'factura', 'B001', '000001', 821.95, 0.00, 147.95, 969.90, 'plin', 969.90, 0.00, 'completada', NULL, '2026-07-01 17:32:00', '2026-07-01 12:46:15', 1),
	(2, 'V-2026-00002', 6, 3, '2026-07-01', 'boleta', 'B001', '000002', 368.64, 0.00, 66.36, 435.00, 'plin', 435.00, 0.00, 'completada', NULL, '2026-07-01 14:11:00', '2026-07-01 12:46:15', 1),
	(3, 'V-2026-00003', 3, 4, '2026-07-01', 'factura', 'B001', '000003', 507.97, 0.00, 91.43, 599.40, 'plin', 599.40, 0.00, 'completada', NULL, '2026-07-01 22:36:00', '2026-07-01 12:46:15', 1),
	(4, 'V-2026-00004', 3, 2, '2026-06-30', 'factura', 'B001', '000004', 963.47, 0.00, 173.43, 1136.90, 'tarjeta', 1136.90, 0.00, 'completada', NULL, '2026-06-30 22:16:00', '2026-07-01 12:46:15', 1),
	(5, 'V-2026-00005', 4, 4, '2026-06-30', 'ticket', 'B001', '000005', 507.37, 0.00, 91.33, 598.70, 'tarjeta', 598.70, 0.00, 'completada', NULL, '2026-07-01 01:28:00', '2026-07-01 12:46:15', 1),
	(6, 'V-2026-00006', 1, 4, '2026-06-30', 'factura', 'B001', '000006', 993.05, 0.00, 178.75, 1171.80, 'tarjeta', 1171.80, 0.00, 'completada', NULL, '2026-06-30 19:10:00', '2026-07-01 12:46:15', 1),
	(7, 'V-2026-00007', 9, 2, '2026-06-29', 'boleta', 'B001', '000007', 76.19, 0.00, 13.71, 89.90, 'plin', 89.90, 0.00, 'completada', NULL, '2026-06-29 19:44:00', '2026-07-01 12:46:15', 1),
	(8, 'V-2026-00008', 6, 2, '2026-06-29', 'ticket', 'B001', '000008', 572.03, 0.00, 102.97, 675.00, 'plin', 675.00, 0.00, 'completada', NULL, '2026-06-29 17:05:00', '2026-07-01 12:46:15', 1),
	(9, 'V-2026-00009', 7, 2, '2026-06-29', 'factura', 'B001', '000009', 775.17, 0.00, 139.53, 914.70, 'yape', 914.70, 0.00, 'completada', NULL, '2026-06-29 23:38:00', '2026-07-01 12:46:15', 1),
	(10, 'V-2026-00010', 1, 4, '2026-06-28', 'ticket', 'B001', '000010', 283.05, 0.00, 50.95, 334.00, 'yape', 334.00, 0.00, 'completada', NULL, '2026-06-28 21:36:00', '2026-07-01 12:46:15', 1),
	(11, 'V-2026-00011', 3, 2, '2026-06-28', 'ticket', 'B001', '000011', 305.08, 0.00, 54.92, 360.00, 'efectivo', 360.00, 0.00, 'completada', NULL, '2026-06-28 18:01:00', '2026-07-01 12:46:15', 1),
	(12, 'V-2026-00012', 3, 5, '2026-06-28', 'factura', 'B001', '000012', 592.80, 0.00, 106.70, 699.50, 'plin', 699.50, 0.00, 'completada', NULL, '2026-06-28 21:31:00', '2026-07-01 12:46:15', 1),
	(13, 'V-2026-00013', 7, 3, '2026-06-27', 'factura', 'B001', '000013', 397.37, 0.00, 71.53, 468.90, 'efectivo', 468.90, 0.00, 'completada', NULL, '2026-06-28 00:01:00', '2026-07-01 12:46:15', 1),
	(14, 'V-2026-00014', 3, 3, '2026-06-27', 'boleta', 'B001', '000014', 659.32, 0.00, 118.68, 778.00, 'transferencia', 778.00, 0.00, 'completada', NULL, '2026-06-28 00:58:00', '2026-07-01 12:46:15', 1),
	(15, 'V-2026-00015', 8, 3, '2026-06-27', 'factura', 'B001', '000015', 664.92, 0.00, 119.68, 784.60, 'efectivo', 784.60, 0.00, 'completada', NULL, '2026-06-27 21:42:00', '2026-07-01 12:46:15', 1),
	(16, 'V-2026-00016', 7, 2, '2026-06-26', 'ticket', 'B001', '000016', 271.02, 0.00, 48.78, 319.80, 'plin', 319.80, 0.00, 'completada', NULL, '2026-06-27 01:45:00', '2026-07-01 12:46:15', 1),
	(17, 'V-2026-00017', 6, 5, '2026-06-26', 'ticket', 'B001', '000017', 815.93, 0.00, 146.87, 962.80, 'efectivo', 962.80, 0.00, 'completada', NULL, '2026-06-26 21:48:00', '2026-07-01 12:46:15', 1),
	(18, 'V-2026-00018', 5, 4, '2026-06-26', 'ticket', 'B001', '000018', 245.76, 0.00, 44.24, 290.00, 'tarjeta', 290.00, 0.00, 'completada', NULL, '2026-06-27 00:45:00', '2026-07-01 12:46:15', 1),
	(19, 'V-2026-00019', 1, 3, '2026-06-25', 'factura', 'B001', '000019', 250.00, 0.00, 45.00, 295.00, 'efectivo', 295.00, 0.00, 'completada', NULL, '2026-06-25 20:39:00', '2026-07-01 12:46:15', 1),
	(20, 'V-2026-00020', 7, 2, '2026-06-25', 'ticket', 'B001', '000020', 871.78, 0.00, 156.92, 1028.70, 'efectivo', 1028.70, 0.00, 'completada', NULL, '2026-06-25 21:40:00', '2026-07-01 12:46:15', 1),
	(21, 'V-2026-00021', 6, 2, '2026-06-25', 'factura', 'B001', '000021', 1014.15, 0.00, 182.55, 1196.70, 'plin', 1196.70, 0.00, 'completada', NULL, '2026-06-26 01:18:00', '2026-07-01 12:46:15', 1),
	(22, 'V-2026-00022', 4, 4, '2026-06-24', 'ticket', 'B001', '000022', 326.27, 0.00, 58.73, 385.00, 'yape', 385.00, 0.00, 'completada', NULL, '2026-06-24 23:19:00', '2026-07-01 12:46:15', 1),
	(23, 'V-2026-00023', 1, 3, '2026-06-23', 'ticket', 'B001', '000023', 279.58, 0.00, 50.32, 329.90, 'efectivo', 329.90, 0.00, 'completada', NULL, '2026-06-23 19:44:00', '2026-07-01 12:46:15', 1),
	(24, 'V-2026-00024', 6, 5, '2026-06-22', 'factura', 'B001', '000024', 630.85, 0.00, 113.55, 744.40, 'transferencia', 744.40, 0.00, 'completada', NULL, '2026-06-22 22:20:00', '2026-07-01 12:46:15', 1),
	(25, 'V-2026-00025', 7, 2, '2026-06-21', 'boleta', 'B001', '000025', 728.56, 0.00, 131.14, 859.70, 'yape', 859.70, 0.00, 'completada', NULL, '2026-06-22 01:22:00', '2026-07-01 12:46:15', 1),
	(26, 'V-2026-00026', 8, 5, '2026-06-20', 'factura', 'B001', '000026', 279.66, 0.00, 50.34, 330.00, 'efectivo', 330.00, 0.00, 'completada', NULL, '2026-06-20 20:47:00', '2026-07-01 12:46:15', 1),
	(27, 'V-2026-00027', 9, 2, '2026-06-19', 'ticket', 'B001', '000027', 186.44, 0.00, 33.56, 220.00, 'transferencia', 220.00, 0.00, 'completada', NULL, '2026-06-20 01:21:00', '2026-07-01 12:46:15', 1),
	(28, 'V-2026-00028', 1, 2, '2026-06-18', 'ticket', 'B001', '000028', 80.51, 0.00, 14.49, 95.00, 'efectivo', 95.00, 0.00, 'completada', NULL, '2026-06-18 17:59:00', '2026-07-01 12:46:15', 1),
	(29, 'V-2026-00029', 7, 5, '2026-06-17', 'boleta', 'B001', '000029', 702.71, 0.00, 126.49, 829.20, 'yape', 829.20, 0.00, 'completada', NULL, '2026-06-17 20:46:00', '2026-07-01 12:46:15', 1),
	(30, 'V-2026-00030', 2, 3, '2026-06-16', 'ticket', 'B001', '000030', 521.19, 0.00, 93.81, 615.00, 'efectivo', 615.00, 0.00, 'completada', NULL, '2026-06-16 17:27:00', '2026-07-01 12:46:15', 1),
	(31, 'V-2026-00031', 9, 2, '2026-06-15', 'ticket', 'B001', '000031', 368.64, 0.00, 66.36, 435.00, 'plin', 435.00, 0.00, 'completada', NULL, '2026-06-15 16:43:00', '2026-07-01 12:46:15', 1),
	(32, 'V-2026-00032', 2, 5, '2026-06-14', 'boleta', 'B001', '000032', 406.53, 0.00, 73.17, 479.70, 'efectivo', 479.70, 0.00, 'completada', NULL, '2026-06-14 14:56:00', '2026-07-01 12:46:15', 1),
	(33, 'V-2026-00033', 3, 5, '2026-06-13', 'ticket', 'B001', '000033', 431.95, 0.00, 77.75, 509.70, 'efectivo', 509.70, 0.00, 'completada', NULL, '2026-06-13 20:45:00', '2026-07-01 12:46:15', 1),
	(34, 'V-2026-00034', 5, 4, '2026-06-12', 'ticket', 'B001', '000034', 396.61, 0.00, 71.39, 468.00, 'tarjeta', 468.00, 0.00, 'completada', NULL, '2026-06-13 00:05:00', '2026-07-01 12:46:15', 1),
	(35, 'V-2026-00035', 2, 2, '2026-06-11', 'ticket', 'B001', '000035', 131.36, 0.00, 23.64, 155.00, 'plin', 155.00, 0.00, 'completada', NULL, '2026-06-11 18:09:00', '2026-07-01 12:46:15', 1),
	(36, 'V-2026-00036', 7, 4, '2026-06-10', 'factura', 'B001', '000036', 760.17, 0.00, 136.83, 897.00, 'plin', 897.00, 0.00, 'completada', NULL, '2026-06-10 16:41:00', '2026-07-01 12:46:15', 1),
	(37, 'V-2026-00037', 7, 5, '2026-06-09', 'factura', 'B001', '000037', 368.64, 0.00, 66.36, 435.00, 'plin', 435.00, 0.00, 'completada', NULL, '2026-06-10 00:50:00', '2026-07-01 12:46:15', 1),
	(38, 'V-2026-00038', 8, 2, '2026-06-08', 'boleta', 'B001', '000038', 101.69, 0.00, 18.31, 120.00, 'tarjeta', 120.00, 0.00, 'completada', NULL, '2026-06-08 15:35:00', '2026-07-01 12:46:15', 1),
	(39, 'V-2026-00039', 9, 5, '2026-06-07', 'ticket', 'B001', '000039', 135.51, 0.00, 24.39, 159.90, 'yape', 159.90, 0.00, 'completada', NULL, '2026-06-07 23:11:00', '2026-07-01 12:46:15', 1),
	(40, 'V-2026-00040', 3, 3, '2026-06-06', 'ticket', 'B001', '000040', 383.90, 0.00, 69.10, 453.00, 'plin', 453.00, 0.00, 'completada', NULL, '2026-06-06 19:17:00', '2026-07-01 12:46:15', 1),
	(41, 'V-2026-00041', 7, 3, '2026-06-05', 'boleta', 'B001', '000041', 67.63, 0.00, 12.17, 79.80, 'yape', 79.80, 0.00, 'completada', NULL, '2026-06-05 15:08:00', '2026-07-01 12:46:15', 1),
	(42, 'V-2026-00042', 10, 4, '2026-06-04', 'ticket', 'B001', '000042', 93.22, 0.00, 16.78, 110.00, 'yape', 110.00, 0.00, 'completada', NULL, '2026-06-04 22:48:00', '2026-07-01 12:46:15', 1),
	(43, 'V-2026-00043', 3, 3, '2026-06-03', 'boleta', 'B001', '000043', 726.86, 0.00, 130.84, 857.70, 'yape', 857.70, 0.00, 'completada', NULL, '2026-06-03 14:35:00', '2026-07-01 12:46:15', 1),
	(44, 'V-2026-00044', 3, 4, '2026-06-02', 'factura', 'B001', '000044', 701.69, 0.00, 126.31, 828.00, 'transferencia', 828.00, 0.00, 'completada', NULL, '2026-06-02 23:27:00', '2026-07-01 12:46:15', 1),
	(45, 'V-2026-00045', 3, 3, '2026-06-01', 'ticket', 'B001', '000045', 889.83, 0.00, 160.17, 1050.00, 'efectivo', 1050.00, 0.00, 'completada', NULL, '2026-06-01 19:50:00', '2026-07-01 12:46:15', 1),
	(46, 'V-2026-00046', 4, 2, '2026-05-27', 'boleta', 'B001', '000046', 320.34, 0.00, 57.66, 378.00, 'yape', 378.00, 0.00, 'completada', NULL, '2026-05-27 20:49:00', '2026-07-01 12:46:15', 1),
	(47, 'V-2026-00047', 3, 3, '2026-05-24', 'ticket', 'B001', '000047', 76.19, 0.00, 13.71, 89.90, 'tarjeta', 89.90, 0.00, 'completada', NULL, '2026-05-24 15:05:00', '2026-07-01 12:46:15', 1),
	(48, 'V-2026-00048', 4, 4, '2026-05-22', 'ticket', 'B001', '000048', 203.39, 0.00, 36.61, 240.00, 'efectivo', 240.00, 0.00, 'completada', NULL, '2026-05-22 17:32:00', '2026-07-01 12:46:15', 1),
	(49, 'V-2026-00049', 8, 2, '2026-05-20', 'boleta', 'B001', '000049', 556.69, 0.00, 100.21, 656.90, 'efectivo', 656.90, 0.00, 'completada', NULL, '2026-05-20 15:58:00', '2026-07-01 12:46:15', 1),
	(50, 'V-2026-00050', 4, 3, '2026-05-17', 'boleta', 'B001', '000050', 127.12, 0.00, 22.88, 150.00, 'tarjeta', 150.00, 0.00, 'completada', NULL, '2026-05-17 22:59:00', '2026-07-01 12:46:15', 1),
	(51, 'V-2026-00051', 10, 5, '2026-05-12', 'factura', 'B001', '000051', 114.41, 0.00, 20.59, 135.00, 'efectivo', 135.00, 0.00, 'completada', NULL, '2026-05-12 23:00:00', '2026-07-01 12:46:15', 1),
	(52, 'V-2026-00052', 7, 3, '2026-05-07', 'boleta', 'B001', '000052', 523.73, 0.00, 94.27, 618.00, 'tarjeta', 618.00, 0.00, 'completada', NULL, '2026-05-07 19:15:00', '2026-07-01 12:46:15', 1),
	(53, 'V-2026-00053', 10, 4, '2026-05-02', 'ticket', 'B001', '000053', 33.81, 0.00, 6.09, 39.90, 'tarjeta', 39.90, 0.00, 'completada', NULL, '2026-05-02 17:25:00', '2026-07-01 12:46:15', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
