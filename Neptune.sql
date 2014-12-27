-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';

DROP TABLE IF EXISTS `neptune_blog`;
CREATE TABLE `neptune_blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` longtext NOT NULL,
  `author` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `editor` text NOT NULL,
  `edited` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bbcode` int(11) NOT NULL,
  `commenting` int(11) NOT NULL,
  `sticky` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `neptune_comments`;
CREATE TABLE `neptune_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author` mediumtext NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `resource` mediumtext NOT NULL,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `neptune_menu`;
CREATE TABLE `neptune_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL,
  `path` text NOT NULL,
  `name` text NOT NULL,
  `parent` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `neptune_pages`;
CREATE TABLE `neptune_pages` (
  `pid` text NOT NULL,
  `name` text NOT NULL,
  `content` longtext NOT NULL,
  `author` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `editor` text NOT NULL,
  `edited` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bbcode` int(11) NOT NULL,
  `commenting` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `neptune_users`;
CREATE TABLE `neptune_users` (
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `displayname` mediumtext COLLATE utf8_bin NOT NULL,
  `password` mediumtext COLLATE utf8_bin NOT NULL,
  `email` mediumtext COLLATE utf8_bin,
  `email_public` tinyint(1) NOT NULL DEFAULT '0',
  `permissions` int(11) NOT NULL DEFAULT '1',
  `joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `meta` longtext COLLATE utf8_bin,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


-- 2014-12-27 06:51:55

