/* 10:39:28 AM MAMP */ ALTER TABLE `order_materials` ADD `cost` DECIMAL(15,4)  NULL  DEFAULT NULL  AFTER `status`;
/* 10:39:31 AM MAMP */ ALTER TABLE `order_materials` MODIFY COLUMN `cost` DECIMAL(15,4) DEFAULT NULL AFTER `qty`;
/* 10:36:53 AM MAMP */ ALTER TABLE `invoices` ADD `contact_email` VARCHAR(255)  CHARACTER SET utf8  NULL  DEFAULT NULL  AFTER `display_subtotals`;
/* 10:37:05 AM MAMP */ ALTER TABLE `invoices` MODIFY COLUMN `contact_email` VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL AFTER `contact_phone`;

/* 3:29:09 PM MAMP */ ALTER TABLE `contacts` ADD `assigned_to_id` INT(10)  NULL  DEFAULT NULL  AFTER `from_lead`;
/* 3:29:15 PM MAMP */ ALTER TABLE `contacts` MODIFY COLUMN `assigned_to_id` INT(10) DEFAULT NULL AFTER `account_rep_id`;
