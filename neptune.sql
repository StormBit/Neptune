-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 06, 2012 at 10:43 PM
-- Server version: 5.5.19
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `stormdev_neptune`
--

-- --------------------------------------------------------

--
-- Table structure for table `neptune_menu`
--

CREATE TABLE IF NOT EXISTS `neptune_menu` (
  `position` int(11) NOT NULL,
  `path` text NOT NULL,
  `name` text NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `neptune_pages`
--

CREATE TABLE IF NOT EXISTS `neptune_pages` (
  `pid` text NOT NULL,
  `name` text NOT NULL,
  `content` longtext NOT NULL,
  `author` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `editor` text NOT NULL,
  `edited` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `bbcode` int(11) NOT NULL,
  `commenting` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `neptune_users`
--

CREATE TABLE IF NOT EXISTS `neptune_users` (
  `username` varchar(255) COLLATE utf8_bin NOT NULL PRIMARY KEY,
  `displayname` mediumtext COLLATE utf8_bin NOT NULL,
  `password` mediumtext COLLATE utf8_bin NOT NULL,
  `email` mediumtext COLLATE utf8_bin,
  `email_public` tinyint(1) NOT NULL DEFAULT '0',
  `permissions` int(11) NOT NULL DEFAULT '1',
  `joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `postcount` int(11) NOT NULL DEFAULT '0',
  `avatar_type` int(11) NOT NULL DEFAULT '0',
  `avatar` longtext COLLATE utf8_bin,
  `signature` longtext COLLATE utf8_bin
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
