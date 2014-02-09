CREATE TABLE IF NOT EXISTS `neptune_blog` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `neptune_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `position` int(11) NOT NULL,
  `path` text NOT NULL,
  `name` text NOT NULL,
  `parent` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `neptune_users` (
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

