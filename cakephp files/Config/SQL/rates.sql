/* 10:15:20 PM MAMP */ ALTER TABLE `groups` CHANGE `rate` `rate_id` DECIMAL(15,4)  NULL  DEFAULT NULL;
/* 10:15:29 PM MAMP */ ALTER TABLE `groups` DROP `expense_rate`;
/* 10:15:33 PM MAMP */ ALTER TABLE `groups` DROP `first_hour`;

CREATE TABLE `rates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `rate` decimal(15,4) DEFAULT NULL,
  `sort` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/* 11:58:40 AM creationsite.business360 */ ALTER TABLE `users` CHANGE `rate` `rate_id` DECIMAL(15,2)  NULL  DEFAULT NULL;
/* 11:58:49 AM creationsite.business360 */ ALTER TABLE `users` CHANGE `rate_id` `rate_id` INT(10)  UNSIGNED  NULL  DEFAULT NULL;


/* 8:07:39 AM MAMP */ ALTER TABLE `rates` ADD `status` INT(10)  NULL  DEFAULT NULL  AFTER `sort`;
/* 8:07:45 AM MAMP */ ALTER TABLE `rates` CHANGE `status` `status` INT(10)  NULL  DEFAULT '1';
/* 2:43:53 PM MAMP */ ALTER TABLE `quote_line_item_labor_items` CHANGE `rate` `rate_id` INT(10)  NULL  DEFAULT NULL;

/* 5:55:43 PM MAMP */ ALTER TABLE `quote_line_item_labor_items` ADD `rate_id` INT(10)  UNSIGNED  NULL  DEFAULT NULL  AFTER `labor_cost_dollars`;
/* 5:55:45 PM MAMP */ ALTER TABLE `quote_line_item_labor_items` MODIFY COLUMN `rate_id` INT(10) UNSIGNED DEFAULT NULL AFTER `quote_line_item_id`;
UPDATE quote_line_item_labor_items SET quote_line_item_labor_items.rate_id = 1 WHERE quote_line_item_labor_items.rate_id = 100;
UPDATE quote_line_item_labor_items SET quote_line_item_labor_items.rate_id = 2 WHERE quote_line_item_labor_items.rate_id = 80;
UPDATE order_line_item_labor_items SET order_line_item_labor_items.rate_id = 1 WHERE order_line_item_labor_items.rate_id = 100;
UPDATE order_line_item_labor_items SET order_line_item_labor_items.rate_id = 2 WHERE order_line_item_labor_items.rate_id = 80;

/* 7:08:39 PM MAMP */ ALTER TABLE `order_times` ADD `rate_id` VARCHAR(10)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NOT NULL  DEFAULT 'work'  AFTER `invoice_total`;
/* 7:08:43 PM MAMP */ ALTER TABLE `order_times` MODIFY COLUMN `rate_id` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'work' AFTER `time_total`;
/* 6:02:53 PM MAMP */ ALTER TABLE `order_times` CHANGE `rate_id` `rate_id` VARCHAR(10)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL  DEFAULT 'work';

UPDATE order_times SET order_times.rate_id = 1 WHERE order_times.rate = 100.00;
UPDATE order_times SET order_times.rate_id = 2 WHERE order_times.rate = 80.00;

/* 8:21:29 PM MAMP */ ALTER TABLE `locations` ADD `billing` INT(10)  UNSIGNED  NULL  DEFAULT '0'  AFTER `status`;
/* 8:21:40 PM MAMP */ ALTER TABLE `locations` ADD `email-billing` VARCHAR(100)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL  DEFAULT NULL  AFTER `billing`;
/* 8:21:54 PM MAMP */ ALTER TABLE `locations` ADD `phone-billing` VARCHAR(25)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL  DEFAULT NULL  AFTER `email-billing`;

/* 11:24:48 AM MAMP */ ALTER TABLE `application_settings` ADD `company_name` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL  DEFAULT NULL  AFTER `default_reminder_order`;
/* 11:24:51 AM MAMP */ ALTER TABLE `application_settings` MODIFY COLUMN `company_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER `margin`;

/* 5:35:51 PM MAMP */ ALTER TABLE `application_settings` ADD `company_url` VARCHAR(255)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL  DEFAULT NULL  AFTER `default_reminder_order`;
/* 5:35:55 PM MAMP */ ALTER TABLE `application_settings` MODIFY COLUMN `company_url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL AFTER `company_name`;

/* 10:43:38 PM MAMP */ CREATE TABLE `licenses` (   `id` int(10) unsigned NOT NULL AUTO_INCREMENT,   `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,   `rate` decimal(15,4) DEFAULT NULL,   `sort` int(10) DEFAULT NULL,   `status` int(10) DEFAULT '1',   PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/* 10:44:02 PM MAMP */ ALTER TABLE `licenses` CHANGE `rate` `number` VARCHAR(50)  NULL  DEFAULT NULL;

/* 8:34:13 AM MAMP */ ALTER TABLE `application_settings` ADD `timezone` VARCHAR(50)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL  DEFAULT NULL  AFTER `default_reminder_order`;

# Dump of table help_videos
# ------------------------------------------------------------

DROP TABLE IF EXISTS `help_videos`;

CREATE TABLE `help_videos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `help_videos` WRITE;
/*!40000 ALTER TABLE `help_videos` DISABLE KEYS */;

INSERT INTO `help_videos` (`id`, `link`, `name`, `sort`, `status`)
VALUES
	(1,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/37pty-CMPKY?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','Manage Jobs Part 1',1,1),
	(2,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/6cBmAnC1MOk?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','Scheduling Training ',3,1),
	(3,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/5ceh21nIFko?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','Manage Jobs Part 2',2,1),
	(4,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/kY9KaewQJ40?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','Quote To Order Conversion',4,1),
	(5,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/tjGij8AMANU?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','Quotes Copies NRevisions ',5,1),
	(6,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/0edZwEoU2_k?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','Manage Quotes ',6,1),
	(7,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/chRP-pChtIc?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','Add a Job ',7,1),
	(8,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/_c80jF-4SK0?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','Create A Quote',8,1),
	(9,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/Ky2x37oE9F0?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','Convert Quote To Job ',9,1),
	(10,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/nA6de7Mxesw?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','Quote Revisions Copies ',10,1),
	(11,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/KyKLAkVhEb0?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','How To Manage Quotes',11,1),
	(12,'<iframe width=\"640\" height=\"480\" src=\"https://www.youtube.com/embed/7IzKIDxLHPQ?rel=0&amp;showinfo=0\" frameborder=\"0\" allowfullscreen></iframe>','How To Create A Quote',12,1);

/*!40000 ALTER TABLE `help_videos` ENABLE KEYS */;
UNLOCK TABLES;


/* 7:15:30 PM squires.business360 */ ALTER TABLE `locations` CHANGE `email-billing` `email_billing` VARCHAR(100)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL  DEFAULT NULL;
/* 7:15:34 PM squires.business360 */ ALTER TABLE `locations` CHANGE `phone-billing` `phone_billing` VARCHAR(25)  CHARACTER SET utf8  COLLATE utf8_unicode_ci  NULL  DEFAULT NULL;

