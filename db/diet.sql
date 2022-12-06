/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table meal
# ------------------------------------------------------------

DROP TABLE IF EXISTS `meal`;

CREATE TABLE `meal` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int DEFAULT NULL,
  `datetime` datetime DEFAULT CURRENT_TIMESTAMP,
  `portion` decimal(6,0) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `updateResultsInsertMeal` AFTER INSERT ON `meal` FOR EACH ROW BEGIN 
# ---
REPLACE INTO `results` (`date`, `total_proteins`, `total_fats`, `total_carbohydrates`, `total_calories`)
SELECT
	DATE(NEW.`datetime`),
	SUM(`portion`*`proteins`/100) as today_proteins,
	SUM(`portion`*`fats`/100) as today_fats,
	SUM(`portion`*`carbohydrates`/100) as today_carbohydrates,
	SUM(`portion`*`calories`/100) as today_calories
FROM `meal`
    LEFT JOIN `products` ON (`meal`.`product_id` = `products`.`id`)
    WHERE DATE(`meal`.`datetime`) = DATE(NEW.`datetime`)
    ORDER BY `meal`.`datetime` ASC;
# ---
UPDATE `results` AS t1,
(
	SELECT
		`weight`,
		(`weight`.`weight`*`proteins`) as max_proteins,
		(`weight`.`weight`*`fats`) as max_fats,
		(`weight`.`weight`*`plan`.`carbohydrates`) as max_carbohydrates,
		`plan`.`calories` as max_calories
	FROM `weight`
	LEFT JOIN `plan` ON (
		`plan`.`start` <= NEW.`datetime`
		AND `plan`.`end` >= NEW.`datetime`
	)
	WHERE `weight`.`date` <= DATE(NEW.`datetime`)
	ORDER BY `weight`.`date` DESC
	LIMIT 1
) as t2
SET 
	`t1`.`weight` = `t2`.`weight`,
	`t1`.`max_proteins` = `t2`.`max_proteins`, 
	`t1`.`max_fats` = `t2`.`max_fats`, 
	`t1`.`max_carbohydrates` = `t2`.`max_carbohydrates`, 
	`t1`.`max_calories`= `t2`.`max_calories`
WHERE 
	`t1`.`date` = DATE(NEW.`datetime`);
# ---
UPDATE `results`
SET 
	`balance_proteins` = (`max_proteins` - `total_proteins`),
	`balance_fats` = (`max_fats` - `total_fats`),
	`balance_carbohydrates` = (`max_carbohydrates` - `total_carbohydrates`),
	`balance_calories` = (`max_calories` - `total_calories`)
WHERE 
	`date` = DATE(NEW.`datetime`);
# ---
END */;;
/*!50003 SET SESSION SQL_MODE="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `updateResultsUpdateMeal` AFTER UPDATE ON `meal` FOR EACH ROW BEGIN 
# ---
REPLACE INTO `results` (`date`, `total_proteins`, `total_fats`, `total_carbohydrates`, `total_calories`)
SELECT
	DATE(NEW.`datetime`),
	SUM(`portion`*`proteins`/100) as today_proteins,
	SUM(`portion`*`fats`/100) as today_fats,
	SUM(`portion`*`carbohydrates`/100) as today_carbohydrates,
	SUM(`portion`*`calories`/100) as today_calories
FROM `meal`
    LEFT JOIN `products` ON (`meal`.`product_id` = `products`.`id`)
    WHERE DATE(`meal`.`datetime`) = DATE(NEW.`datetime`)
    ORDER BY `meal`.`datetime` ASC;
# ---
UPDATE `results` AS t1,
(
	SELECT
		`weight`,
		(`weight`.`weight`*`proteins`) as max_proteins,
		(`weight`.`weight`*`fats`) as max_fats,
		(`weight`.`weight`*`plan`.`carbohydrates`) as max_carbohydrates,
		`plan`.`calories` as max_calories
	FROM `weight`
	LEFT JOIN `plan` ON (
		`plan`.`start` <= NEW.`datetime`
		AND `plan`.`end` >= NEW.`datetime`
	)
	WHERE `weight`.`date` <= DATE(NEW.`datetime`)
	ORDER BY `weight`.`date` DESC
	LIMIT 1
) as t2
SET 
	`t1`.`weight` = `t2`.`weight`,
	`t1`.`max_proteins` = `t2`.`max_proteins`, 
	`t1`.`max_fats` = `t2`.`max_fats`, 
	`t1`.`max_carbohydrates` = `t2`.`max_carbohydrates`, 
	`t1`.`max_calories`= `t2`.`max_calories`
WHERE 
	`t1`.`date` = DATE(NEW.`datetime`);
# ---
UPDATE `results`
SET 
	`balance_proteins` = (`max_proteins` - `total_proteins`),
	`balance_fats` = (`max_fats` - `total_fats`),
	`balance_carbohydrates` = (`max_carbohydrates` - `total_carbohydrates`),
	`balance_calories` = (`max_calories` - `total_calories`)
WHERE 
	`date` = DATE(NEW.`datetime`);
# ---
END */;;
/*!50003 SET SESSION SQL_MODE="ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `updateResultsDeleteMeal` AFTER DELETE ON `meal` FOR EACH ROW BEGIN 
# ---
REPLACE INTO `results` (`date`, `total_proteins`, `total_fats`, `total_carbohydrates`, `total_calories`)
SELECT
	DATE(OLD.`datetime`),
	SUM(`portion`*`proteins`/100) as today_proteins,
	SUM(`portion`*`fats`/100) as today_fats,
	SUM(`portion`*`carbohydrates`/100) as today_carbohydrates,
	SUM(`portion`*`calories`/100) as today_calories
FROM `meal`
    LEFT JOIN `products` ON (`meal`.`product_id` = `products`.`id`)
    WHERE DATE(`meal`.`datetime`) = DATE(OLD.`datetime`)
    ORDER BY `meal`.`datetime` ASC;
# ---
UPDATE `results` AS t1,
(
	SELECT
		`weight`,
		(`weight`.`weight`*`proteins`) as max_proteins,
		(`weight`.`weight`*`fats`) as max_fats,
		(`weight`.`weight`*`plan`.`carbohydrates`) as max_carbohydrates,
		`plan`.`calories` as max_calories
	FROM `weight`
	LEFT JOIN `plan` ON (
		`plan`.`start` <= OLD.`datetime`
		AND `plan`.`end` >= OLD.`datetime`
	)
	WHERE `weight`.`date` <= DATE(OLD.`datetime`)
	ORDER BY `weight`.`date` DESC
	LIMIT 1
) as t2
SET 
	`t1`.`weight` = `t2`.`weight`,
	`t1`.`max_proteins` = `t2`.`max_proteins`, 
	`t1`.`max_fats` = `t2`.`max_fats`, 
	`t1`.`max_carbohydrates` = `t2`.`max_carbohydrates`, 
	`t1`.`max_calories`= `t2`.`max_calories`
WHERE 
	`t1`.`date` = DATE(OLD.`datetime`);
# ---
UPDATE `results`
SET 
	`balance_proteins` = (`max_proteins` - `total_proteins`),
	`balance_fats` = (`max_fats` - `total_fats`),
	`balance_carbohydrates` = (`max_carbohydrates` - `total_carbohydrates`),
	`balance_calories` = (`max_calories` - `total_calories`)
WHERE 
	`date` = DATE(OLD.`datetime`);
# ---
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table plan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `plan`;

CREATE TABLE `plan` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `proteins` decimal(6,2) DEFAULT NULL,
  `fats` decimal(6,2) DEFAULT NULL,
  `carbohydrates` decimal(6,2) DEFAULT NULL,
  `calories` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table products
# ------------------------------------------------------------

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `barcode` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'EAN-8 or EAN-13',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fullTitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proteins` decimal(6,2) DEFAULT NULL,
  `fats` decimal(6,2) DEFAULT NULL,
  `carbohydrates` decimal(6,2) DEFAULT NULL,
  `calories` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table results
# ------------------------------------------------------------

DROP TABLE IF EXISTS `results`;

CREATE TABLE `results` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `total_proteins` decimal(6,2) DEFAULT NULL,
  `total_fats` decimal(6,2) DEFAULT NULL,
  `total_carbohydrates` decimal(6,2) DEFAULT NULL,
  `total_calories` decimal(6,2) DEFAULT NULL,
  `weight` decimal(6,2) DEFAULT NULL,
  `max_proteins` decimal(6,2) DEFAULT NULL,
  `max_fats` decimal(6,2) DEFAULT NULL,
  `max_carbohydrates` decimal(6,2) DEFAULT NULL,
  `max_calories` decimal(6,2) DEFAULT NULL,
  `balance_proteins` decimal(6,2) DEFAULT NULL,
  `balance_fats` decimal(6,2) DEFAULT NULL,
  `balance_carbohydrates` decimal(6,2) DEFAULT NULL,
  `balance_calories` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table weight
# ------------------------------------------------------------

DROP TABLE IF EXISTS `weight`;

CREATE TABLE `weight` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `weight` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
