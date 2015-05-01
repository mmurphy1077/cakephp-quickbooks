/* 8:34:46 AM MAMP */ ALTER TABLE `order_line_items` ADD `invoice_id` INT(10)  UNSIGNED  NULL  DEFAULT NULL  AFTER `quote_line_item_id`;
/* 8:34:49 AM MAMP */ ALTER TABLE `order_line_items` MODIFY COLUMN `invoice_id` INT(10) UNSIGNED DEFAULT NULL AFTER `order_line_item_type_id`;
/* 8:34:52 AM MAMP */ ALTER TABLE `order_line_items` MODIFY COLUMN `invoice_id` INT(10) UNSIGNED DEFAULT NULL AFTER `order_id`;
/* 8:35:22 AM MAMP */ ALTER TABLE `order_line_items` ADD `cost` DECIMAL(15,2)  NULL  DEFAULT NULL  AFTER `quote_line_item_id`;
/* 8:35:24 AM MAMP */ ALTER TABLE `order_line_items` MODIFY COLUMN `cost` DECIMAL(15,2) DEFAULT NULL AFTER `options`;
/* 8:35:35 AM MAMP */ ALTER TABLE `order_line_items` MODIFY COLUMN `cost` DECIMAL(15,2) DEFAULT NULL AFTER `total`;

/* 3:08:36 PM creationsite.business360 */ ALTER TABLE `orders` ADD `location_id` TINYINT(4)  NULL  DEFAULT '0'  AFTER `order_customer_id`;
