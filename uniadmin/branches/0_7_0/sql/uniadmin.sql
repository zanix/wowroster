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

### Configuration values
INSERT INTO `uniadmin_config` VALUES ('addon_folder', 'addon_zips');
INSERT INTO `uniadmin_config` VALUES ('default_lang', 'english');
INSERT INTO `uniadmin_config` VALUES ('interface_url', '%url%?p=interface');
INSERT INTO `uniadmin_config` VALUES ('logo_folder', 'logos');
INSERT INTO `uniadmin_config` VALUES ('temp_analyze_folder', 'addon_temp');
INSERT INTO `uniadmin_config` VALUES ('UAVer', '0.7.0-beta');
INSERT INTO `uniadmin_config` VALUES ('uniadmin_start', '1158644074');
INSERT INTO `uniadmin_config` VALUES ('ziplibsupport', 'false');


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

### Settings
INSERT INTO `uniadmin_settings` VALUES (1, 'LANGUAGE', 'English', '0', 'settings', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (2, 'PRIMARYURL', 'http://yourdomain.com/yourinterface.php', '0', 'settings', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (3, 'PROGRAMMODE', 'Basic', '0', 'settings', 'select{Basic^Basic|Advanced^Advanced');
INSERT INTO `uniadmin_settings` VALUES (4, 'AUTODETECTWOW', '1', '1', 'settings', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (5, 'OPENGL', '0', '0', 'settings', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (6, 'WINDOWMODE', '0', '0', 'settings', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (7, 'UUUPDATERCHECK', '1', '1', 'updater', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (8, 'SYNCHROURL', 'http://yourdomain.com/UniAdmin/interface.php', '0', 'updater', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (9, 'ADDONAUTOUPDATE', '1', '0', 'updater', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (10, 'UUSETTINGSUPDATER', '1', '0', 'updater', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (11, 'AUTOUPLOADONFILECHANGES', '1', '1', 'options', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (12, 'ALWAYSONTOP', '1', '0', 'options', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (13, 'SYSTRAY', '0', '0', 'options', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (14, 'USERAGENT', 'UniUploader 2.0 (UU 2.5.0; English)', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (15, 'ADDVAR1CH', '0', '0', 'options', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (16, 'ADDVARNAME1', 'username', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (17, 'ADDVARVAL1', '', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (18, 'ADDVAR2CH', '0', '0', 'options', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (19, 'ADDVARNAME2', 'password', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (20, 'ADDVARVAL2', '', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (21, 'ADDVAR3CH', '0', '0', 'options', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (22, 'ADDVARNAME3', '', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (23, 'ADDVARVAL3', '', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (24, 'ADDVAR4CH', '0', '0', 'options', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (25, 'ADDVARNAME4', '', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (26, 'ADDVARVAL4', '', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (27, 'ADDURL1CH', '0', '0', 'options', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (28, 'ADDURL1', '', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (29, 'ADDURL2CH', '0', '0', 'options', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (30, 'ADDURL2', '', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (31, 'ADDURL3CH', '0', '0', 'options', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (32, 'ADDURL3', '', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (33, 'ADDURL4CH', '0', '0', 'options', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (34, 'ADDURL4', '', '0', 'options', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (35, 'AUTOLAUNCHWOW', '0', '0', 'advanced', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (36, 'WOWARGS', '0', '0', 'advanced', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (37, 'STARTWITHWINDOWS', '0', '0', 'advanced', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (38, 'USELAUNCHER', '0', '0', 'advanced', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (39, 'STARTMINI', '1', '0', 'advanced', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (40, 'SENDPWSECURE', '1', '0', 'advanced', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (41, 'GZIP', '1', '0', 'advanced', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (42, 'DELAYUPLOAD', '0', '0', 'advanced', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (43, 'DELAYSECONDS', '5', '0', 'advanced', 'text{50|10');
INSERT INTO `uniadmin_settings` VALUES (44, 'RETRDATAFROMSITE', '1', '0', 'advanced', 'radio{yes^1|no^0');
INSERT INTO `uniadmin_settings` VALUES (45, 'RETRDATAURL', 'http://somewhere.com/something.php', '0', 'advanced', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (46, 'WEBWOWSVFILE', 'SavedVariables.lua', '0', 'advanced', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (47, 'DOWNLOADBEFOREWOWL', '0', '0', 'advanced', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (48, 'DOWNLOADBEFOREUPLOAD', '0', '0', 'advanced', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (49, 'DOWNLOADAFTERUPLOAD', '1', '0', 'advanced', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (50, 'SYNCHROAUTOURL', '1', '0', '', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (51, 'AUTOPATH', '1', '1', '', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (52, 'PREPARSE', '1', '0', '', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (53, 'PARSEVAR2CH', '0', '0', '', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (54, 'PARSEVAR3CH', '0', '0', '', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (55, 'PARSEVAR4CH', '0', '0', '', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (56, 'PARSEVAR5CH', '0', '0', '', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (57, 'PARSEVAR1', 'myProfile', '0', '', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (58, 'PARSEVAR2', '', '0', '', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (59, 'PARSEVAR3', '', '0', '', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (60, 'PARSEVAR4', '', '0', '', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (61, 'PARSEVAR5', '', '0', '', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (62, 'RETRDATA', '0', '0', '', 'radio{on^1|off^0');
INSERT INTO `uniadmin_settings` VALUES (63, 'ADDURLFFNAME1', '', '0', '', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (64, 'ADDURLFFNAME2', '', '0', '', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (65, 'ADDURLFFNAME3', '', '0', '', 'text{50|50');
INSERT INTO `uniadmin_settings` VALUES (66, 'ADDURLFFNAME4', '', '0', '', 'text{50|50');


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

### SV List
INSERT INTO `uniadmin_svlist` VALUES (1, 'CharacterProfiler');
INSERT INTO `uniadmin_svlist` VALUES (2, 'PvPLog');


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

### User List
INSERT INTO `uniadmin_users` VALUES (1, 'Default', '4cb9c8a8048fd02294477fcb1a41191a', '3', 'English');