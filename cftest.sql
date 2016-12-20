SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(2) NOT NULL,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `country` (`id`, `code`, `name`) VALUES
(1,	'CZ',	'Czech Republic'),
(2,	'IE',	'Ireland'),
(3,	'DE',	'Deutschland'),
(4,	'FR',	'France');

DROP TABLE IF EXISTS `currency`;
CREATE TABLE `currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(3) NOT NULL,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `currency` (`id`, `code`, `name`) VALUES
(1,	'GBP',	''),
(2,	'CZK',	''),
(3,	'EUR',	''),
(4,	'PHP',	'');

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `time_placed` datetime NOT NULL,
  `id_currency_from` int(11) NOT NULL,
  `id_currency_to` int(11) NOT NULL,
  `id_originating_country` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount_sell` double NOT NULL,
  `amount_buy` double NOT NULL,
  `rate` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_currency_from` (`id_currency_from`),
  KEY `id_currency_to` (`id_currency_to`),
  KEY `id_originating_country` (`id_originating_country`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`id_currency_from`) REFERENCES `currency` (`id`),
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`id_currency_to`) REFERENCES `currency` (`id`),
  CONSTRAINT `message_ibfk_3` FOREIGN KEY (`id_originating_country`) REFERENCES `country` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

