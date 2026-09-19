-- ============================================================
--  PARCHE DE BASE DE DATOS · SaaS Tienda Moda
--  Crea las tablas/columnas que faltan en bases de datos que
--  se restauraron ANTES de las migraciones 04–07.
--
--  Corrige estos errores:
--    · Table 'movimientos_caja' doesn't exist        (módulo Caja)
--    · Table 'pagos' doesn't exist                   (módulo Suscripción)
--    · Unknown column 'subcategorias.tienda_id'      (Subcategorías / Productos)
--    · password_reset_tokens                          (Recuperar contraseña)
--
--  Es IDEMPOTENTE: se puede ejecutar varias veces sin peligro.
--  Cómo usarlo: ábrelo en HeidiSQL / phpMyAdmin (Laragon) sobre la
--  base de datos "saas_tienda_moda" y ejecútalo completo.
-- ============================================================

USE `saas_tienda_moda`;

-- 1) Columna tienda_id en subcategorias (multi-tienda) --------
ALTER TABLE `subcategorias`
    ADD COLUMN IF NOT EXISTS `tienda_id` BIGINT UNSIGNED NULL AFTER `id`,
    ADD INDEX IF NOT EXISTS `subcategorias_tienda_id_index` (`tienda_id`);

-- Rellena la tienda de las subcategorías existentes tomándola de su categoría
UPDATE `subcategorias` s
JOIN `categorias` c ON s.`categoria_id` = c.`id`
SET s.`tienda_id` = c.`tienda_id`
WHERE s.`tienda_id` IS NULL;

-- 2) Tabla movimientos_caja (ingresos/egresos de caja) --------
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
  KEY `movimientos_caja_tienda_id_index` (`tienda_id`),
  KEY `movimientos_caja_caja_id_foreign` (`caja_id`),
  KEY `movimientos_caja_user_id_foreign` (`user_id`),
  CONSTRAINT `movimientos_caja_caja_id_foreign` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movimientos_caja_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3) Tabla password_reset_tokens (recuperar contraseña) -------
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4) Tabla pagos (suscripción / historial de pagos) ----------
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
  KEY `pagos_tienda_id_index` (`tienda_id`),
  KEY `pagos_plan_id_foreign` (`plan_id`),
  KEY `pagos_user_id_foreign` (`user_id`),
  CONSTRAINT `pagos_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pagos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5) Registrar las migraciones para que `php artisan migrate`
--    quede sincronizado y no intente volver a crearlas ---------
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2024_01_04_000000_create_movimientos_caja_table', 4
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2024_01_04_000000_create_movimientos_caja_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2024_01_05_000000_create_password_reset_tokens_table', 4
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2024_01_05_000000_create_password_reset_tokens_table');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2024_01_06_000000_add_tienda_to_subcategorias', 4
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2024_01_06_000000_add_tienda_to_subcategorias');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2024_01_07_000000_create_pagos_table', 4
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2024_01_07_000000_create_pagos_table');

-- Listo. Recarga la aplicación y prueba Caja, Suscripción y Subcategorías.
