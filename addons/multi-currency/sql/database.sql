CREATE TABLE `currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` tinyint NOT NULL DEFAULT '1' COMMENT '1:Before price 2:After price',
  `rate` decimal(28,9) NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_id` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_code_unique` (`code`);

ALTER TABLE `currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

INSERT INTO `currencies` (`code`, `symbol`, `position`, `rate`, `icon`, `sort_id`, `created_at`, `updated_at`) VALUES
('USD', '$', 1, 1.000000000, 'images/currencies/usd.png', 1, '2024-12-04 17:46:58', '2024-12-04 17:46:58');