#
# MySQL UniAdmin DB Structure
#
# $Id$
#
# --------------------------------------------------------
### Table structure for addons

DROP TABLE IF EXISTS `uniadmin_addons`;
CREATE TABLE `uniadmin_addons` (
  `id` int(11) NOT NULL auto_increment,
  `time_uploaded` int(11) NOT NULL default '0',
  `version` varchar(250) NOT NULL default '',
  `enabled` varchar(5) NOT NULL default '',
  `name` varchar(250) NOT NULL default '',
  `dl_url` varchar(250) NOT NULL default '',
  `homepage` varchar(250) NOT NULL default '',
  `toc` mediumint(9) NOT NULL default '0',
  `required` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);


# --------------------------------------------------------
### Table structure for config

DROP TABLE IF EXISTS `uniadmin_config`;
CREATE TABLE `uniadmin_config` (
  `config_name` varchar(255) NOT NULL,
  `config_value` varchar(255) default NULL,
  PRIMARY KEY  (`config_name`)
);


# --------------------------------------------------------
### Table structure for files

DROP TABLE IF EXISTS `uniadmin_files`;
CREATE TABLE `uniadmin_files` (
  `id` int(11) NOT NULL auto_increment,
  `addon_id` int(11) NOT NULL,
  `addon_name` varchar(250) NOT NULL default '',
  `filename` varchar(250) NOT NULL default '',
  `md5sum` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `addon_id` (`addon_id`)
);


# --------------------------------------------------------
### Table structure for logos


DROP TABLE IF EXISTS `uniadmin_logos`;
CREATE TABLE `uniadmin_logos` (
  `id` int(11) NOT NULL auto_increment,
  `filename` varchar(250) NOT NULL default '',
  `updated` int(11) NOT NULL default '0',
  `logo_num` varchar(11) NOT NULL default '',
  `active` int(1) NOT NULL default '0',
  `download_url` varchar(250) NOT NULL default '',
  `md5` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`)
);


# --------------------------------------------------------
### Table structure for settings


DROP TABLE IF EXISTS `uniadmin_settings`;
CREATE TABLE `uniadmin_settings` (
  `id` int(11) NOT NULL auto_increment,
  `set_name` varchar(250) NOT NULL default '',
  `set_value` varchar(250) NOT NULL default '',
  `enabled` varchar(11) NOT NULL default '',
  `section` varchar(64) NOT NULL,
  `form_type` mediumtext,
  PRIMARY KEY  (`id`)
);


# --------------------------------------------------------
### Table structure for stats


DROP TABLE IF EXISTS `uniadmin_stats`;
CREATE TABLE `uniadmin_stats` (
  `id` int(11) NOT NULL auto_increment,
  `ip_addr` varchar(30) NOT NULL default '',
  `host_name` varchar(250) NOT NULL default '',
  `action` varchar(250) NOT NULL default '',
  `time` varchar(15) NOT NULL default '',
  `user_agent` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`)
);


# --------------------------------------------------------
### Table structure for svlist


DROP TABLE IF EXISTS `uniadmin_svlist`;
CREATE TABLE `uniadmin_svlist` (
  `id` int(11) NOT NULL auto_increment,
  `sv_name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
);


# --------------------------------------------------------
### Table structure for users


DROP TABLE IF EXISTS `uniadmin_users`;
CREATE TABLE `uniadmin_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `level` char(3) NOT NULL default '',
  `language` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`id`)
);
