#
# MySQL Roster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### Addon table

DROP TABLE IF EXISTS `renprefix_addon`;
CREATE TABLE `renprefix_addon` (
	`addon_id` int(11) NOT NULL AUTO_INCREMENT,
	`basename` varchar(16) NOT NULL DEFAULT '',
	`dbname` varchar(16) NOT NULL DEFAULT '',
	`version` varchar(16) NOT NULL DEFAULT '0',
	`hasconfig` varchar(16) NOT NULL DEFAULT '0',
	`active` int(1) NOT NULL DEFAULT 1,
	`fullname` tinytext NOT NULL,
	`description` mediumtext NOT NULL,
	`credits` mediumtext NOT NULL,
	PRIMARY KEY (`addon_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Guild Ranks table

DROP TABLE IF EXISTS `renprefix_guildranks`;
CREATE TABLE `renprefix_guildranks` (
  `index` int(11) NOT NULL,
  `title` varchar(96) NOT NULL,
  `control` varchar(64) NOT NULL,
  `guild_id` int(10) unsigned NOT NULL,
  KEY `index` (`index`,`guild_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Addon Menu table

DROP TABLE IF EXISTS `renprefix_addon_menu`;
CREATE TABLE `renprefix_addon_menu` (
	`menu_item_id` int(11) AUTO_INCREMENT,
	`addon_id` int(11),
	`title` varchar(32),
	`url` varchar(64),
	`active` int(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (`menu_item_id`),
	KEY idtitle (`addon_id`,`title`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Addon Trigger table

DROP TABLE IF EXISTS `renprefix_addon_trigger`;
CREATE TABLE `renprefix_addon_trigger` (
	`trigger_id` int(11) AUTO_INCREMENT,
	`addon_id` int(11),
	`file` varchar(32),
	`active` int(1) NOT NULL default 0,
	PRIMARY KEY (`trigger_id`),
	KEY idfile (`addon_id`,`file`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Menu config table

DROP TABLE IF EXISTS `renprefix_menu`;
CREATE TABLE `renprefix_menu` (
	`config_id` int(11) AUTO_INCREMENT,
	`account_id` smallint(6) NOT NULL COMMENT '0 for default value',
	`section` varchar(16),
	`config` mediumtext,
	PRIMARY KEY (`config_id`),
	UNIQUE KEY `idsect` (`account_id`,`section`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Menu button table

DROP TABLE IF EXISTS `renprefix_menu_button`;
CREATE TABLE `renprefix_menu_button` (
	`button_id` int(11) AUTO_INCREMENT,
	`addon_id` int(11) NOT NULL COMMENT '0 for main roster',
	`title` varchar(32),
	`url` varchar(64),
	`need_creds` tinytext,
	PRIMARY KEY (`button_id`),
	KEY `idtitle` (`addon_id`,`title`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Account

DROP TABLE IF EXISTS `renprefix_account`;
CREATE TABLE `renprefix_account` (
	`account_id` smallint(6) NOT NULL auto_increment,
	`name` varchar(30) NOT NULL default '',
	`hash` varchar(32) NOT NULL default '',
	`level` int(8) NOT NULL default '10',
	PRIMARY KEY  (`account_id`)
) TYPE=MyISAM;

INSERT INTO `renprefix_config` VALUES (5, 'startpage', 'main_conf', 'display', 'master');

# --------------------------------------------------------
### Config Menu Entries
INSERT INTO `renprefix_config` VALUES (110, 'main_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (120, 'guild_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (130, 'menu_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (140, 'display_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (150, 'index_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (160, 'char_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (170, 'realmstatus_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (180, 'data_links', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (190, 'guildbank_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (200, 'update_access', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (210, 'documentation', 'http://wowroster.net/wiki', 'newlink', 'menu');

INSERT INTO `renprefix_config` VALUES (3210, 'members_openfilter', '1', 'radio{open^1|closed^0', 'index_conf');

DELETE FROM `renprefix_config` WHERE `id` = 3080;
DELETE FROM `renprefix_config` WHERE `id` = 3090;
DELETE FROM `renprefix_config` WHERE `id` = 4100;

# --------------------------------------------------------
### Update entries for index column visibility
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3130;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3140;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3150;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3160;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3170;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3180;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3190;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = `config_value` * 10 WHERE `id` = 3200;

# --------------------------------------------------------
### Update entries for character page visibility (global)
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7015;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7020;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7030;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7040;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7050;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7060;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7070;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7080;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7090;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7100;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7110;
UPDATE `renprefix_config` SET `form_type` = 'access', `config_value` = 10 WHERE `id` = 7120;

DELETE FROM `renprefix_config` WHERE `id` = 10000;

INSERT INTO `renprefix_config` VALUES (10000, 'auth_update', '10', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10010, 'auth_updateGP', '1', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10020, 'auth_install_addon', '-1', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10030, 'auth_roster_config', '-1', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10040, 'auth_character_config', '1', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10050, 'auth_change_pass', '10', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10060, 'auth_diag_button', '0', 'access', 'update_access');
INSERT INTO `renprefix_config` VALUES (10070, 'auth_addon_config', '-1', 'access', 'update_access');

# --------------------------------------------------------
### Menu table entries
INSERT INTO `renprefix_menu` VALUES (1, 0, 'main', 'b1:b2:b3:b4:b5|b6:b7:b8:b9|b10:b11:b12:b13');

# --------------------------------------------------------
### Menu Button entries
INSERT INTO `renprefix_menu_button` VALUES (1, 0, 'Roster', 'index.php');
INSERT INTO `renprefix_menu_button` VALUES (2, 0, 'Guild Info', 'guildinfo.php');
INSERT INTO `renprefix_menu_button` VALUES (3, 0, 'Stats', 'stats.php');
INSERT INTO `renprefix_menu_button` VALUES (4, 0, 'Professions', 'tradeskills.php');
INSERT INTO `renprefix_menu_button` VALUES (5, 0, 'GuildBank', 'guildbank.php');
INSERT INTO `renprefix_menu_button` VALUES (6, 0, 'PvP Stats', 'guildpvp.php');
INSERT INTO `renprefix_menu_button` VALUES (7, 0, 'Honor', 'honor.php');
INSERT INTO `renprefix_menu_button` VALUES (8, 0, 'Member Log', 'memberlog.php');
INSERT INTO `renprefix_menu_button` VALUES (9, 0, 'Keys', 'keys.php');
INSERT INTO `renprefix_menu_button` VALUES (10, 0, 'User Control', 'rostercp.php');
INSERT INTO `renprefix_menu_button` VALUES (11, 0, 'Find Team', 'questlist.php');
INSERT INTO `renprefix_menu_button` VALUES (12, 0, 'Search', 'search.php');
INSERT INTO `renprefix_menu_button` VALUES (13, 0, 'Credits', 'credits.php');

# --------------------------------------------------------
### Reconfigure members table
ALTER TABLE `renprefix_members`
	CHANGE `talents` `talents` tinytext,
	CHANGE `spellbook` `spellbook` tinytext,
	CHANGE `mail` `mail` tinytext,
	CHANGE `inv` `inv` tinytext,
	CHANGE `money` `money` tinytext,
	CHANGE `bank` `bank` tinytext,
	CHANGE `recipes` `recipes` tinytext,
	CHANGE `quests` `quests` tinytext,
	CHANGE `bg` `bg` tinytext,
	CHANGE `pvp` `pvp` tinytext,
	CHANGE `duels` `duels` tinytext,
	CHANGE `item_bonuses` `item_bonuses` tinytext,
	ADD `active` tinyint(1) NOT NULL DEFAULT '0' AFTER `update_time`,
	ADD `server` varchar(32) NOT NULL AFTER `name`
	ADD UNIQUE KEY `character` (`server`,`name`);

# Calculate new permission values form the old ones
UPDATE `renprefix_members` SET
	`talents`	= (`talents`-1)*5,
	`spellbook`	= (`spellbook`-1)*5,
	`mail`		= (`mail`-1)*5,
	`inv`		= (`inv`-1)*5,
	`money`		= (`money`-1)*5,
	`bank`		= (`bank`-1)*5,
	`recipes`	= (`recipes`-1)*5,
	`quests`	= (`quests`-1)*5,
	`bg`		= (`bg`-1)*5,
	`pvp`		= (`pvp`-1)*5,
	`duels`		= (`duels`-1)*5,
	`item_bonuses`	= (`item_bonuses`-1)*5;

# Copy realmname from the guild table
UPDATE `renprefix_members`
	LEFT JOIN `renprefix_guild` ON `renprefix_members`.`guild_id` = `renprefix_guild`.`guild_id`
	SET `renprefix_members`.`server` = `renprefix_guild`.`server`;


# --------------------------------------------------------
### Update players table

ALTER TABLE `renprefix_players`
  CHANGE `dateupdatedutc` `dateupdatedutc` datetime default NULL;


# --------------------------------------------------------
### Update guild table

ALTER TABLE `renprefix_guild`
  CHANGE `guild_dateupdatedutc` `guild_dateupdatedutc` datetime default NULL,
  DROP `update_time`;

# --------------------------------------------------------
### The roster version and db version MUST be last

UPDATE `renprefix_config` SET `config_value` = '1.8.0' WHERE `id` = '4' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '3' WHERE `id` = '3' LIMIT 1;

ALTER TABLE `renprefix_config` ORDER BY `id`;
