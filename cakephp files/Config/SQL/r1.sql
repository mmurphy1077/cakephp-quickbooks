/* 11:43:26 PM squires.business360 */ ALTER TABLE `addresses` ADD `notes` TEXT  NULL  AFTER `status`;
/* 12:49:22 PM MAMP */ ALTER TABLE `invoices` ADD `date_billed` DATE  NULL  DEFAULT NULL  AFTER `display_subtotals`;
/* 12:49:28 PM MAMP */ ALTER TABLE `invoices` MODIFY COLUMN `date_billed` DATE DEFAULT NULL AFTER `labor_amount_est`;
/* 12:49:33 PM MAMP */ ALTER TABLE `invoices` MODIFY COLUMN `date_billed` DATE DEFAULT NULL AFTER `date_approved`;
/* 10:14:03 AM MAMP */ ALTER TABLE `quote_tasks` ADD `quote_task_default_id` INT(10)  UNSIGNED  NULL  DEFAULT NULL  AFTER `status`;
/* 10:14:06 AM MAMP */ ALTER TABLE `quote_tasks` MODIFY COLUMN `quote_task_default_id` INT(10) UNSIGNED DEFAULT NULL AFTER `quote_id`;
/* 11:07:15 AM MAMP */ ALTER TABLE `quote_tasks` ADD `date_start` DATE  NULL  DEFAULT NULL  AFTER `status`;
/* 11:07:18 AM MAMP */ ALTER TABLE `quote_tasks` MODIFY COLUMN `date_start` DATE DEFAULT NULL AFTER `date_created`;
/* 11:07:42 AM MAMP */ ALTER TABLE `quote_tasks` DROP `date_request_approved`;
/* 11:07:46 AM MAMP */ ALTER TABLE `quote_tasks` DROP `alert_date_request_require_approval`;
/* 6:21:09 PM MAMP */ ALTER TABLE `order_tasks` ADD `date_request_completed` DATE  NULL  DEFAULT NULL  AFTER `status`;
/* 6:21:12 PM MAMP */ ALTER TABLE `order_tasks` MODIFY COLUMN `date_request_completed` DATE DEFAULT NULL AFTER `date_request`;
/* 6:21:19 PM MAMP */ ALTER TABLE `quote_tasks` ADD `date_request_completed` DATE  NULL  DEFAULT NULL  AFTER `status`;
/* 6:21:24 PM MAMP */ ALTER TABLE `quote_tasks` MODIFY COLUMN `date_request_completed` DATE DEFAULT NULL AFTER `date_request`;