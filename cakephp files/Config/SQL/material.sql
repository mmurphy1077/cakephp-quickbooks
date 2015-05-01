# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.33)
# Database: 360-hh
# Generation Time: 2015-04-09 03:37:55 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table material_assemblies
# ------------------------------------------------------------

DROP TABLE IF EXISTS `material_assemblies`;

CREATE TABLE `material_assemblies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `sort` int(10) DEFAULT '0',
  `status` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table material_assemblies_materials
# ------------------------------------------------------------

DROP TABLE IF EXISTS `material_assemblies_materials`;

CREATE TABLE `material_assemblies_materials` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `material_assembly_id` int(10) DEFAULT NULL,
  `material_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


# Dump of table material_types
# ------------------------------------------------------------

DROP TABLE IF EXISTS `material_types`;

CREATE TABLE `material_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `order` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `material_types` WRITE;
/*!40000 ALTER TABLE `material_types` DISABLE KEYS */;

INSERT INTO `material_types` (`id`, `name`, `order`)
VALUES
	(1,'Material',1),
	(2,'Labor',2);

/*!40000 ALTER TABLE `material_types` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table materials
# ------------------------------------------------------------

DROP TABLE IF EXISTS `materials`;

CREATE TABLE `materials` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `creator_id` int(11) DEFAULT NULL,
  `material_type_id` int(10) unsigned DEFAULT '1',
  `is_category` char(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci,
  `part_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price_per_unit_actual` decimal(15,4) DEFAULT '0.0000',
  `price_per_unit` decimal(15,4) DEFAULT '0.0000',
  `uom_id` int(11) DEFAULT NULL,
  `onhand` int(10) DEFAULT NULL,
  `reserved` int(10) DEFAULT NULL,
  `available` int(10) DEFAULT NULL,
  `parent_id` int(10) DEFAULT NULL,
  `lft` int(10) DEFAULT NULL,
  `rght` int(10) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* 8:15:59 AM squires.business360 */ ALTER TABLE `materials` ADD `favorite` INT(10)  NULL  DEFAULT '0'  AFTER `status`;

/* 9:57:56 AM MAMP */ INSERT INTO `uoms` (`id`, `name`) VALUES (NULL, 'per day');
/* 9:58:03 AM MAMP */ INSERT INTO `uoms` (`id`, `name`) VALUES (NULL, 'per week');
