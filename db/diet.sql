SET NAMES utf8mb4;


# Dump of table meal
# ------------------------------------------------------------

DROP TABLE IF EXISTS `meal`;

CREATE TABLE `meal` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `datetime` datetime DEFAULT CURRENT_TIMESTAMP,
  `portion` decimal(10,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `barcode` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'EAN-8 or EAN-13',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fullTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proteins` decimal(6,3) DEFAULT NULL,
  `carbohydrates` decimal(6,3) DEFAULT NULL,
  `fats` decimal(6,3) DEFAULT NULL,
  `calories` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table weight
# ------------------------------------------------------------

DROP TABLE IF EXISTS `weight`;

CREATE TABLE `weight` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `datetime` datetime DEFAULT CURRENT_TIMESTAMP,
  `weight` decimal(6,3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;