-- Adminer 4.7.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` text,
  `vat_reverse_charge` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `customers_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


SET NAMES utf8mb4;

DROP TABLE IF EXISTS `deliveries`;
CREATE TABLE `deliveries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `vat_percentage` decimal(10,2) DEFAULT NULL,
  `comment` text,
  `invoiceline_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `project_id` (`project_id`),
  KEY `invoiceline_id` (`invoiceline_id`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `deliveries_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `deliveries_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  CONSTRAINT `deliveries_ibfk_4` FOREIGN KEY (`invoiceline_id`) REFERENCES `invoicelines` (`id`),
  CONSTRAINT `deliveries_ibfk_5` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `hours`;
CREATE TABLE `hours` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `hours_worked` decimal(5,2) NOT NULL,
  `hourly_fee` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `vat_percentage` decimal(10,2) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `comment` text,
  `invoiceline_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `type` (`type`),
  KEY `datum` (`date`),
  KEY `tenant_id` (`tenant_id`),
  KEY `invoiceline_id` (`invoiceline_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `hours_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  CONSTRAINT `hours_ibfk_3` FOREIGN KEY (`type`) REFERENCES `hourtypes` (`id`),
  CONSTRAINT `hours_ibfk_5` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`),
  CONSTRAINT `hours_ibfk_6` FOREIGN KEY (`invoiceline_id`) REFERENCES `invoicelines` (`id`),
  CONSTRAINT `hours_ibfk_7` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `hourtypes`;
CREATE TABLE `hourtypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `hourtypes_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `invoicelines`;
CREATE TABLE `invoicelines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `vat` decimal(10,2) DEFAULT NULL,
  `vat_percentage` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `invoicelines_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`),
  CONSTRAINT `invoicelines_ibfk_3` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`),
  CONSTRAINT `invoicelines_ibfk_4` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `invoices`;
CREATE TABLE `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `sent` tinyint(1) NOT NULL,
  `paid` tinyint(1) NOT NULL,
  `reminder1` date DEFAULT NULL,
  `reminder2` date DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `vat` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nummer` (`number`),
  KEY `customer_id` (`customer_id`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `invoices_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `offers`;
CREATE TABLE `offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL,
  `signed` tinyint(1) NOT NULL,
  `intro_html` text,
  `planning_html` text,
  `betaling_html` text,
  `line1` varchar(255) DEFAULT NULL,
  `amount1` decimal(10,2) DEFAULT NULL,
  `line2` varchar(255) DEFAULT NULL,
  `amount2` decimal(10,2) DEFAULT NULL,
  `line3` varchar(255) DEFAULT NULL,
  `amount3` decimal(10,2) DEFAULT NULL,
  `line4` varchar(255) DEFAULT NULL,
  `amount4` decimal(10,2) DEFAULT NULL,
  `line5` varchar(255) DEFAULT NULL,
  `amount5` decimal(10,2) DEFAULT NULL,
  `line6` varchar(255) DEFAULT NULL,
  `amount6` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `customer_id` (`customer_id`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `offers_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `offers_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `offers_ibfk_4` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `subscriptionperiods`;
CREATE TABLE `subscriptionperiods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `from` date NOT NULL,
  `until` date NOT NULL,
  `name` varchar(255) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `comment` text,
  `invoiceline_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `subscription_id` (`subscription_id`),
  KEY `invoiceline_id` (`invoiceline_id`),
  CONSTRAINT `subscriptionperiods_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`),
  CONSTRAINT `subscriptionperiods_ibfk_3` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`),
  CONSTRAINT `subscriptionperiods_ibfk_4` FOREIGN KEY (`invoiceline_id`) REFERENCES `invoicelines` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `fee` decimal(10,2) NOT NULL,
  `vat_percentage` decimal(10,2) DEFAULT NULL,
  `months` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `from` date NOT NULL,
  `canceled` date DEFAULT NULL,
  `comment` text,
  `subscriptiontype_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `subscriptiontype_id` (`subscriptiontype_id`),
  KEY `tenant_id` (`tenant_id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `subscriptions_ibfk_4` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
  CONSTRAINT `subscriptions_ibfk_6` FOREIGN KEY (`subscriptiontype_id`) REFERENCES `subscriptiontypes` (`id`),
  CONSTRAINT `subscriptions_ibfk_7` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`),
  CONSTRAINT `subscriptions_ibfk_8` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `subscriptiontypes`;
CREATE TABLE `subscriptiontypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `subscriptiontypes_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `tenants`;
CREATE TABLE `tenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` text,
  `email` varchar(255) NOT NULL,
  `invoice_email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_bic` varchar(255) DEFAULT NULL,
  `bank_city` varchar(255) DEFAULT NULL,
  `coc_number` varchar(255) DEFAULT NULL,
  `vat_number` varchar(255) DEFAULT NULL,
  `default_vat_percentage` decimal(10,2) NOT NULL DEFAULT '21.00',
  `default_hourly_fee` decimal(10,2) NOT NULL DEFAULT '75.00',
  `payment_period` int(11) NOT NULL DEFAULT '30',
  `reminder_period` int(11) NOT NULL DEFAULT '14',
  `logo_image` mediumblob,
  `signature_image` mediumblob,
  `invoice_styles` text,
  `invoice_template` text,
  `invoice_page_number` varchar(255) DEFAULT NULL,
  `hours_active` tinyint(1) NOT NULL DEFAULT '1',
  `subscriptions_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `tenant_id` (`tenant_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;


-- 2019-01-27 21:41:24