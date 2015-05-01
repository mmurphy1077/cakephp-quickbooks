DROP TABLE IF EXISTS `reminders`;

CREATE TABLE `reminders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `model` varchar(25) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `foreign_key` int(10) DEFAULT NULL,
  `date_reminder` datetime DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `application_settings`;

CREATE TABLE `application_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contract_language` longtext COLLATE utf8_unicode_ci,
  `margin` decimal(15,4) DEFAULT NULL,
  `apply_margin_to_materials` int(10) unsigned DEFAULT '0',
  `default_reminder_lead` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_reminder_quote` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `default_reminder_order` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* 10:15:35 AM MAMP */ ALTER TABLE `invoices` ADD `internal_notes` TEXT  CHARACTER SET utf8  NULL  AFTER `display_subtotals`;
