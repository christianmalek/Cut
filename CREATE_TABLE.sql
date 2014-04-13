SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+02:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `cut_url` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(2500) COLLATE utf8_unicode_ci NOT NULL,
  `generated` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;