#
# MySQL Roster Upgrade File
#
# * $Id$
#
# --------------------------------------------------------
### The roster version and db version MUST be last

# --------------------------------------------------------
### Account table
INSERT INTO `renprefix_account` (`account_id`, `name`) VALUES
	(1, 'Guild'),
	(2, 'Officer'),
	(3, 'Admin');

UPDATE `renprefix_account` account, `renprefix_config` config
	SET `account`.`hash` = `config`.`config_value`
	WHERE `config`.`id` = 2;

# --------------------------------------------------------
### Config table: drop unused settings
DELETE FROM `renprefix_config` WHERE `id` =    1 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` =    2 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 1070 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 1080 LIMIT 1;

DELETE FROM `renprefix_config` WHERE `id` = 3000 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3010 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3015 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3020 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3030 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3040 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3050 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3060 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3070 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3080 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3090 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3100 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3110 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3120 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3130 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3140 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3150 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3160 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3170 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3180 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3190 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 3200 LIMIT 1;

DELETE FROM `renprefix_config` WHERE `id` = 4000 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4010 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4020 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4040 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4050 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4055 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4060 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4070 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4080 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4090 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4100 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4110 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 4120 LIMIT 1;

DELETE FROM `renprefix_config` WHERE `id` = 5000 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 5005 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 5008 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 5010 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 5015 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 5035 LIMIT 1;

DELETE FROM `renprefix_config` WHERE `id` = 7130 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 7140 LIMIT 1;

DELETE FROM `renprefix_config` WHERE `id` = 8000 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 8010 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 8020 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 8030 LIMIT 1;

DELETE FROM `renprefix_config` WHERE `id` = 9000 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 9010 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 9020 LIMIT 1;
DELETE FROM `renprefix_config` WHERE `id` = 9030 LIMIT 1;

# --------------------------------------------------------
### New master entry: Startpage
INSERT INTO `renprefix_config` VALUES (5, 'startpage', 'main_conf', 'display', 'master');

# --------------------------------------------------------
### Config Menu Entries
INSERT INTO `renprefix_config` VALUES (110, 'main_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (120, 'guild_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (130, 'menu_conf', NULL, 'page{1', 'menu');
INSERT INTO `renprefix_config` VALUES (140, 'display_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (150, 'char_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (160, 'realmstatus_conf', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (170, 'data_links', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (180, 'update_access', NULL, 'blockframe', 'menu');
INSERT INTO `renprefix_config` VALUES (190, 'documentation', 'http://wowroster.net/wiki', 'newlink', 'menu');

INSERT INTO `renprefix_config` VALUES (1050, 'default_page', 'members', 'function{pageNames', 'main_conf');

INSERT INTO `renprefix_config` VALUES (4000, 'menu_conf_top', NULL, 'blockframe', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4001, 'menu_conf_wide', NULL, 'page{2', 'menu_conf');
INSERT INTO `renprefix_config` VALUES (4002, 'menu_conf_left', NULL, 'blockframe', 'menu_conf_wide');
INSERT INTO `renprefix_config` VALUES (4003, 'menu_conf_right', NULL, 'blockframe', 'menu_conf_wide');

INSERT INTO `renprefix_config` VALUES (4020, 'menu_top_pane', '1', 'radio{on^1|off^0', 'menu_conf_top');

INSERT INTO `renprefix_config` VALUES (4200, 'menu_left_type', 'level', 'select{Hide^|Levels^level|Class^class|Realmstatus^realm', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4210, 'menu_left_level', '30', 'text{2|10', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4220, 'menu_left_style', 'list', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4230, 'menu_left_barcolor', '#3e0000', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4240, 'menu_left_bar2color', '#003e00', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4250, 'menu_left_textcolor', '#ffffff', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4260, 'menu_left_outlinecolor', '#000000', 'color', 'menu_conf_left');
INSERT INTO `renprefix_config` VALUES (4270, 'menu_left_text', 'VERANDA.TTF', 'function{fontFiles', 'menu_conf_left');

INSERT INTO `renprefix_config` VALUES (4400, 'menu_right_type', 'realm', 'select{Hide^|Levels^level|Class^class|Realmstatus^realm', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4410, 'menu_right_level', '60', 'text{2|10', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4420, 'menu_right_style', 'list', 'select{List^list|Bar graph^bar|Logarithmic bargraph^barlog', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4430, 'menu_right_barcolor', '#3e0000', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4440, 'menu_right_bar2color', '#003e00', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4450, 'menu_right_textcolor', '#ffffff', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4460, 'menu_right_outlinecolor', '#000000', 'color', 'menu_conf_right');
INSERT INTO `renprefix_config` VALUES (4470, 'menu_right_text', 'VERANDA.TTF', 'function{fontFiles', 'menu_conf_right');

INSERT INTO `renprefix_config` VALUES (7005, 'recipe_disp', '0', 'radio{show^1|collapse^0', 'char_conf');

UPDATE `renprefix_config` SET `form_type` = 'select{Default Sort^|Name^name|Class^class|Level^level|Guild Title^guild_title|Highest Rank^lifetimeHighestRank|Note^note|Hearthstone Location^hearth|Zone Location^zone|Last Online^last_online_f|Last Updated^last_update' WHERE `id` =3040;

# --------------------------------------------------------
### Realmstatus Settings

INSERT INTO `renprefix_config` VALUES (8010, 'rs_top', NULL, 'blockframe', 'realmstatus_conf');
INSERT INTO `renprefix_config` VALUES (8020, 'rs_wide', NULL, 'page{3', 'realmstatus_conf');
INSERT INTO `renprefix_config` VALUES (8030, 'rs_left', NULL, 'blockframe', 'rs_wide');
INSERT INTO `renprefix_config` VALUES (8040, 'rs_middle', NULL, 'blockframe', 'rs_wide');
INSERT INTO `renprefix_config` VALUES (8050, 'rs_right', NULL, 'blockframe', 'rs_wide');

INSERT INTO `renprefix_config` VALUES (8100, 'realmstatus_url', 'http://www.worldofwarcraft.com/realmstatus/status.xml', 'select{US Servers^http://www.worldofwarcraft.com/realmstatus/status.xml|EU Servers^http://www.wow-europe.com/en/serverstatus/index.xml', 'rs_top');
INSERT INTO `renprefix_config` VALUES (8110, 'rs_display', 'full', 'select{full^full|half^half', 'rs_top');
INSERT INTO `renprefix_config` VALUES (8120, 'rs_mode', '1', 'radio{Image^1|DIV Container^0', 'rs_top');
INSERT INTO `renprefix_config` VALUES (8130, 'realmstatus', '', 'text{50|30', 'rs_top');
INSERT INTO `renprefix_config` VALUES (8140, 'rs_timer', '10', 'text{5|5', 'rs_top');

INSERT INTO `renprefix_config` VALUES (8200, 'rs_font_server', 'VERANDA.TTF', 'function{fontFiles', 'rs_left');
INSERT INTO `renprefix_config` VALUES (8210, 'rs_size_server', '7', 'text{5|5', 'rs_left');
INSERT INTO `renprefix_config` VALUES (8220, 'rs_color_server', '#000000', 'color', 'rs_left');
INSERT INTO `renprefix_config` VALUES (8230, 'rs_color_shadow', '#95824e', 'color', 'rs_left');

INSERT INTO `renprefix_config` VALUES (8300, 'rs_font_type', 'silkscreenb.ttf', 'function{fontFiles', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8310, 'rs_size_type', '6', 'text{5|5', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8320, 'rs_color_rppvp', '#535600', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8330, 'rs_color_pve', '#234303', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8340, 'rs_color_pvp', '#660D02', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8350, 'rs_color_rp', '#535600', 'color', 'rs_middle');
INSERT INTO `renprefix_config` VALUES (8360, 'rs_color_no', '#860D02', 'color', 'rs_middle');

INSERT INTO `renprefix_config` VALUES (8400, 'rs_font_pop', 'GREY.TTF', 'function{fontFiles', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8410, 'rs_size_pop', '11', 'text{5|5', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8420, 'rs_color_low', '#234303', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8430, 'rs_color_medium', '#535600', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8440, 'rs_color_high', '#660D02', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8450, 'rs_color_max', '#860D02', 'color', 'rs_right');
INSERT INTO `renprefix_config` VALUES (8460, 'rs_color_offline', '#860D02', 'color', 'rs_right');


ALTER TABLE `renprefix_realmstatus`
  DROP `servertypecolor`,
  DROP `serverpopcolor`;

# --------------------------------------------------------
### Fix Item Icons
UPDATE `renprefix_items` SET `item_texture` = REPLACE(`item_texture`,'Interface/Icons/','');
UPDATE `renprefix_buffs` SET `icon` = REPLACE(`icon`,'Interface/Icons/','');
UPDATE `renprefix_mailbox` SET `mailbox_coin_icon` = REPLACE(`mailbox_coin_icon`,'Interface/Icons/','');
UPDATE `renprefix_mailbox` SET `item_icon` = REPLACE(`item_icon`,'Interface/Icons/','');
UPDATE `renprefix_recipes` SET `recipe_texture` = REPLACE(`recipe_texture`,'Interface/Icons/','');
UPDATE `renprefix_spellbook` SET `spell_texture` = REPLACE(`spell_texture`,'Interface/Icons/','');
UPDATE `renprefix_spellbooktree` SET `spell_texture` = REPLACE(`spell_texture`,'Interface/Icons/','');
UPDATE `renprefix_talents` SET `texture` = REPLACE(`texture`,'Interface/Icons/','');
UPDATE `renprefix_talenttree` SET `background` = REPLACE(`background`,'Interface/TalentFrame/','');

UPDATE `renprefix_items` SET `item_texture` = LOWER(REPLACE(`item_texture`,' ','_'));
UPDATE `renprefix_buffs` SET `icon` = LOWER(REPLACE(`icon`,' ','_'));
UPDATE `renprefix_mailbox` SET `mailbox_coin_icon` = LOWER(REPLACE(`mailbox_coin_icon`,' ','_'));
UPDATE `renprefix_mailbox` SET `item_icon` = LOWER(REPLACE(`item_icon`,' ','_'));
UPDATE `renprefix_recipes` SET `recipe_texture` = LOWER(REPLACE(`recipe_texture`,' ','_'));
UPDATE `renprefix_spellbook` SET `spell_texture` = LOWER(REPLACE(`spell_texture`,' ','_'));
UPDATE `renprefix_spellbooktree` SET `spell_texture` = LOWER(REPLACE(`spell_texture`,' ','_'));
UPDATE `renprefix_talents` SET `texture` = LOWER(REPLACE(`texture`,' ','_'));
UPDATE `renprefix_talenttree` SET `background` = LOWER(REPLACE(`background`,' ','_'));

# --------------------------------------------------------
### Alter Players Table
ALTER TABLE `renprefix_players`
  ADD `stat_block` int(11) NOT NULL default '0',
  ADD `stat_block_c` int(11) NOT NULL default '0',
  ADD `stat_block_b` int(11) NOT NULL default '0',
  ADD `stat_block_d` int(11) NOT NULL default '0',
  ADD `stat_parry` int(11) NOT NULL default '0',
  ADD `stat_parry_c` int(11) NOT NULL default '0',
  ADD `stat_parry_b` int(11) NOT NULL default '0',
  ADD `stat_parry_d` int(11) NOT NULL default '0',
  ADD `stat_defr` int(11) NOT NULL default '0',
  ADD `stat_defr_c` int(11) NOT NULL default '0',
  ADD `stat_defr_b` int(11) NOT NULL default '0',
  ADD `stat_defr_d` int(11) NOT NULL default '0',
  ADD `stat_dodge` int(11) NOT NULL default '0',
  ADD `stat_dodge_c` int(11) NOT NULL default '0',
  ADD `stat_dodge_b` int(11) NOT NULL default '0',
  ADD `stat_dodge_d` int(11) NOT NULL default '0',
  ADD `stat_res_ranged` int(11) NOT NULL default '0',
  ADD `stat_res_spell` int(11) NOT NULL default '0',
  ADD `stat_res_melee` int(11) NOT NULL default '0',
  ADD `res_holy` int(11) NOT NULL default '0',
  ADD `res_holy_c` int(11) NOT NULL default '0',
  ADD `res_holy_b` int(11) NOT NULL default '0',
  ADD `res_holy_d` int(11) NOT NULL default '0',
  ADD `melee_power_c` int(11) NOT NULL default '0',
  ADD `melee_power_b` int(11) NOT NULL default '0',
  ADD `melee_power_d` int(11) NOT NULL default '0',
  ADD `melee_hit` int(11) NOT NULL default '0',
  ADD `melee_hit_c` int(11) NOT NULL default '0',
  ADD `melee_hit_b` int(11) NOT NULL default '0',
  ADD `melee_hit_d` int(11) NOT NULL default '0',
  ADD `melee_crit` int(11) NOT NULL default '0',
  ADD `melee_crit_c` int(11) NOT NULL default '0',
  ADD `melee_crit_b` int(11) NOT NULL default '0',
  ADD `melee_crit_d` int(11) NOT NULL default '0',
  ADD `melee_haste` int(11) NOT NULL default '0',
  ADD `melee_haste_c` int(11) NOT NULL default '0',
  ADD `melee_haste_b` int(11) NOT NULL default '0',
  ADD `melee_haste_d` int(11) NOT NULL default '0',
  ADD `melee_crit_chance` float NOT NULL default '0',
  ADD `melee_power_dps` float NOT NULL default '0',
  ADD `melee_mhand_speed` float NOT NULL default '0',
  ADD `melee_mhand_dps` float NOT NULL default '0',
  ADD `melee_mhand_skill` int(11) NOT NULL default '0',
  ADD `melee_mhand_mindam` int(11) NOT NULL default '0',
  ADD `melee_mhand_maxdam` int(11) NOT NULL default '0',
  ADD `melee_mhand_rating` int(11) NOT NULL default '0',
  ADD `melee_mhand_rating_c` int(11) NOT NULL default '0',
  ADD `melee_mhand_rating_b` int(11) NOT NULL default '0',
  ADD `melee_mhand_rating_d` int(11) NOT NULL default '0',
  ADD `melee_ohand_speed` float NOT NULL default '0',
  ADD `melee_ohand_dps` float NOT NULL default '0',
  ADD `melee_ohand_skill` int(11) NOT NULL default '0',
  ADD `melee_ohand_mindam` int(11) NOT NULL default '0',
  ADD `melee_ohand_maxdam` int(11) NOT NULL default '0',
  ADD `melee_ohand_rating` int(11) NOT NULL default '0',
  ADD `melee_ohand_rating_c` int(11) NOT NULL default '0',
  ADD `melee_ohand_rating_b` int(11) NOT NULL default '0',
  ADD `melee_ohand_rating_d` int(11) NOT NULL default '0',
  DROP `melee_rating`,
  DROP `melee_range`,
  ADD `ranged_power_c` int(11) NOT NULL default '0',
  ADD `ranged_power_b` int(11) NOT NULL default '0',
  ADD `ranged_power_d` int(11) NOT NULL default '0',
  ADD `ranged_hit` int(11) NOT NULL default '0',
  ADD `ranged_hit_c` int(11) NOT NULL default '0',
  ADD `ranged_hit_b` int(11) NOT NULL default '0',
  ADD `ranged_hit_d` int(11) NOT NULL default '0',
  ADD `ranged_crit` int(11) NOT NULL default '0',
  ADD `ranged_crit_c` int(11) NOT NULL default '0',
  ADD `ranged_crit_b` int(11) NOT NULL default '0',
  ADD `ranged_crit_d` int(11) NOT NULL default '0',
  ADD `ranged_haste` int(11) NOT NULL default '0',
  ADD `ranged_haste_c` int(11) NOT NULL default '0',
  ADD `ranged_haste_b` int(11) NOT NULL default '0',
  ADD `ranged_haste_d` int(11) NOT NULL default '0',
  ADD `ranged_crit_chance` float NOT NULL default '0',
  ADD `ranged_power_dps` float NOT NULL default '0',
  ADD `ranged_speed` float NOT NULL default '0',
  ADD `ranged_dps` float NOT NULL default '0',
  ADD `ranged_skill` int(11) NOT NULL default '0',
  ADD `ranged_mindam` int(11) NOT NULL default '0',
  ADD `ranged_maxdam` int(11) NOT NULL default '0',
  ADD `ranged_rating_c` int(11) NOT NULL default '0',
  ADD `ranged_rating_b` int(11) NOT NULL default '0',
  ADD `ranged_rating_d` int(11) NOT NULL default '0',
  DROP `ranged_range`,
  ADD `spell_hit` int(11) NOT NULL default '0',
  ADD `spell_hit_c` int(11) NOT NULL default '0',
  ADD `spell_hit_b` int(11) NOT NULL default '0',
  ADD `spell_hit_d` int(11) NOT NULL default '0',
  ADD `spell_crit` int(11) NOT NULL default '0',
  ADD `spell_crit_c` int(11) NOT NULL default '0',
  ADD `spell_crit_b` int(11) NOT NULL default '0',
  ADD `spell_crit_d` int(11) NOT NULL default '0',
  ADD `spell_haste` int(11) NOT NULL default '0',
  ADD `spell_haste_c` int(11) NOT NULL default '0',
  ADD `spell_haste_b` int(11) NOT NULL default '0',
  ADD `spell_haste_d` int(11) NOT NULL default '0',
  ADD `spell_crit_chance` float NOT NULL default '0',
  ADD `mana_regen_value` int(11) NOT NULL default '0',
  ADD `mana_regen_time` int(11) NOT NULL default '0',
  ADD `spell_penetration` int(11) NOT NULL default '0',
  ADD `spell_damage` int(11) NOT NULL default '0',
  ADD `spell_healing` int(11) NOT NULL default '0',
  ADD `spell_damage_frost` int(11) NOT NULL default '0',
  ADD `spell_damage_arcane` int(11) NOT NULL default '0',
  ADD `spell_damage_fire` int(11) NOT NULL default '0',
  ADD `spell_damage_shadow` int(11) NOT NULL default '0',
  ADD `spell_damage_nature` int(11) NOT NULL default '0',
  ADD `raceEn` varchar(32) NOT NULL default '' AFTER `race`,
  ADD `classEn` varchar(32) NOT NULL default '' AFTER `class`,
  ADD `sexid` tinyint(1) NOT NULL default '0' AFTER `sex`,
  ADD `power` varchar(32) NOT NULL default '' AFTER `mana`;


# --------------------------------------------------------
### Alter Pets Table

DROP TABLE IF EXISTS `renprefix_pets`;
CREATE TABLE `renprefix_pets` (
  `pet_id` int(11) unsigned NOT NULL auto_increment,
  `member_id` int(10) unsigned NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `slot` int(11) NOT NULL default '0',
  `level` int(11) NOT NULL default '0',
  `health` int(11) NOT NULL default '0',
  `mana` int(11) NOT NULL default '0',
  `power` varchar(32) NOT NULL default '',
  `xp` varchar(32) NOT NULL default '0',
  `usedtp` int(11) NOT NULL default '0',
  `totaltp` int(11) NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `loyalty` varchar(32) NOT NULL default '',
  `icon` varchar(64) NOT NULL default '',
  `melee_power` int(11) NOT NULL default '0',
  `melee_power_c` int(11) NOT NULL default '0',
  `melee_power_b` int(11) NOT NULL default '0',
  `melee_power_d` int(11) NOT NULL default '0',
  `melee_hit` int(11) NOT NULL default '0',
  `melee_hit_c` int(11) NOT NULL default '0',
  `melee_hit_b` int(11) NOT NULL default '0',
  `melee_hit_d` int(11) NOT NULL default '0',
  `melee_crit` int(11) NOT NULL default '0',
  `melee_crit_c` int(11) NOT NULL default '0',
  `melee_crit_b` int(11) NOT NULL default '0',
  `melee_crit_d` int(11) NOT NULL default '0',
  `melee_haste` int(11) NOT NULL default '0',
  `melee_haste_c` int(11) NOT NULL default '0',
  `melee_haste_b` int(11) NOT NULL default '0',
  `melee_haste_d` int(11) NOT NULL default '0',
  `melee_crit_chance` float NOT NULL default '0',
  `melee_power_dps` float NOT NULL default '0',
  `melee_mhand_speed` float NOT NULL default '0',
  `melee_mhand_dps` float NOT NULL default '0',
  `melee_mhand_skill` int(11) NOT NULL default '0',
  `melee_mhand_mindam` int(11) NOT NULL default '0',
  `melee_mhand_maxdam` int(11) NOT NULL default '0',
  `melee_mhand_rating` int(11) NOT NULL default '0',
  `melee_mhand_rating_c` int(11) NOT NULL default '0',
  `melee_mhand_rating_b` int(11) NOT NULL default '0',
  `melee_mhand_rating_d` int(11) NOT NULL default '0',
  `melee_range_tooltip` tinytext,
  `melee_power_tooltip` tinytext,
  `stat_int` int(11) NOT NULL default '0',
  `stat_int_c` int(11) NOT NULL default '0',
  `stat_int_b` int(11) NOT NULL default '0',
  `stat_int_d` int(11) NOT NULL default '0',
  `stat_agl` int(11) NOT NULL default '0',
  `stat_agl_c` int(11) NOT NULL default '0',
  `stat_agl_b` int(11) NOT NULL default '0',
  `stat_agl_d` int(11) NOT NULL default '0',
  `stat_sta` int(11) NOT NULL default '0',
  `stat_sta_c` int(11) NOT NULL default '0',
  `stat_sta_b` int(11) NOT NULL default '0',
  `stat_sta_d` int(11) NOT NULL default '0',
  `stat_str` int(11) NOT NULL default '0',
  `stat_str_c` int(11) NOT NULL default '0',
  `stat_str_b` int(11) NOT NULL default '0',
  `stat_str_d` int(11) NOT NULL default '0',
  `stat_spr` int(11) NOT NULL default '0',
  `stat_spr_c` int(11) NOT NULL default '0',
  `stat_spr_b` int(11) NOT NULL default '0',
  `stat_spr_d` int(11) NOT NULL default '0',
  `stat_def` int(11) NOT NULL default '0',
  `stat_def_c` int(11) NOT NULL default '0',
  `stat_def_b` int(11) NOT NULL default '0',
  `stat_def_d` int(11) NOT NULL default '0',
  `stat_armor` int(11) NOT NULL default '0',
  `stat_armor_c` int(11) NOT NULL default '0',
  `stat_armor_b` int(11) NOT NULL default '0',
  `stat_armor_d` int(11) NOT NULL default '0',
  `stat_block` int(11) NOT NULL default '0',
  `stat_block_c` int(11) NOT NULL default '0',
  `stat_block_b` int(11) NOT NULL default '0',
  `stat_block_d` int(11) NOT NULL default '0',
  `stat_parry` int(11) NOT NULL default '0',
  `stat_parry_c` int(11) NOT NULL default '0',
  `stat_parry_b` int(11) NOT NULL default '0',
  `stat_parry_d` int(11) NOT NULL default '0',
  `stat_defr` int(11) NOT NULL default '0',
  `stat_defr_c` int(11) NOT NULL default '0',
  `stat_defr_b` int(11) NOT NULL default '0',
  `stat_defr_d` int(11) NOT NULL default '0',
  `stat_dodge` int(11) NOT NULL default '0',
  `stat_dodge_c` int(11) NOT NULL default '0',
  `stat_dodge_b` int(11) NOT NULL default '0',
  `stat_dodge_d` int(11) NOT NULL default '0',
  `stat_res_ranged` int(11) NOT NULL default '0',
  `stat_res_spell` int(11) NOT NULL default '0',
  `stat_res_melee` int(11) NOT NULL default '0',
  `res_holy` int(11) NOT NULL default '0',
  `res_holy_c` int(11) NOT NULL default '0',
  `res_holy_b` int(11) NOT NULL default '0',
  `res_holy_d` int(11) NOT NULL default '0',
  `res_frost` int(11) NOT NULL default '0',
  `res_frost_c` int(11) NOT NULL default '0',
  `res_frost_b` int(11) NOT NULL default '0',
  `res_frost_d` int(11) NOT NULL default '0',
  `res_arcane` int(11) NOT NULL default '0',
  `res_arcane_c` int(11) NOT NULL default '0',
  `res_arcane_b` int(11) NOT NULL default '0',
  `res_arcane_d` int(11) NOT NULL default '0',
  `res_fire` int(11) NOT NULL default '0',
  `res_fire_c` int(11) NOT NULL default '0',
  `res_fire_b` int(11) NOT NULL default '0',
  `res_fire_d` int(11) NOT NULL default '0',
  `res_shadow` int(11) NOT NULL default '0',
  `res_shadow_c` int(11) NOT NULL default '0',
  `res_shadow_b` int(11) NOT NULL default '0',
  `res_shadow_d` int(11) NOT NULL default '0',
  `res_nature` int(11) NOT NULL default '0',
  `res_nature_c` int(11) NOT NULL default '0',
  `res_nature_b` int(11) NOT NULL default '0',
  `res_nature_d` int(11) NOT NULL default '0',
  `dodge` float NOT NULL default '0',
  `parry` float NOT NULL default '0',
  `block` float NOT NULL default '0',
  `mitigation` float NOT NULL default '0',
  PRIMARY KEY  (`pet_id`,`member_id`,`name`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Pet Spellbook

DROP TABLE IF EXISTS `renprefix_spellbook_pet`;
CREATE TABLE `renprefix_spellbook_pet` (
  `member_id` int(11) unsigned NOT NULL default '0',
  `pet_id` int(11) unsigned NOT NULL default '0',
  `spell_name` varchar(64) NOT NULL default '',
  `spell_texture` varchar(64) NOT NULL default '',
  `spell_rank` varchar(64) NOT NULL default '',
  `spell_tooltip` mediumtext NOT NULL,
  PRIMARY KEY (`member_id`,`pet_id`,`spell_name`,`spell_rank`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Alter Members Table
ALTER TABLE `renprefix_members`
  ADD `active` tinyint(1) NOT NULL default '0',
  DROP `update_time`;


# --------------------------------------------------------
### Alter Guild Table
ALTER TABLE `renprefix_guild`
  CHANGE `faction` `faction` varchar(32) NOT NULL default '0',
  ADD `factionEn` varchar(32) NOT NULL default '' AFTER `faction`;

# --------------------------------------------------------
### Addon table

DROP TABLE IF EXISTS `renprefix_addon`;
CREATE TABLE `renprefix_addon` (
	`addon_id` int(11) NOT NULL AUTO_INCREMENT,
	`basename` varchar(16) NOT NULL DEFAULT '',
	`version` varchar(16) NOT NULL DEFAULT '0',
	`active` int(1) NOT NULL DEFAULT 1,
	`fullname` tinytext NOT NULL,
	`description` mediumtext NOT NULL,
	`credits` mediumtext NOT NULL,
	PRIMARY KEY (`addon_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Menu config table

DROP TABLE IF EXISTS `renprefix_menu`;
CREATE TABLE `renprefix_menu` (
	`config_id` int(11) AUTO_INCREMENT,
	`section` varchar(16),
	`config` mediumtext,
	PRIMARY KEY (`config_id`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Menu button table

DROP TABLE IF EXISTS `renprefix_menu_button`;
CREATE TABLE `renprefix_menu_button` (
	`button_id` int(11) AUTO_INCREMENT,
	`addon_id` int(11) NOT NULL COMMENT '0 for main roster',
	`title` varchar(32),
	`url` varchar(128),
	PRIMARY KEY (`button_id`),
	KEY `idtitle` (`addon_id`,`title`)
) TYPE=MyISAM;

# --------------------------------------------------------
### Addon Config

DROP TABLE IF EXISTS `renprefix_addon_config`;
CREATE TABLE `renprefix_addon_config` (
  `addon_id` int(11),
  `id` int(11) unsigned NOT NULL,
  `config_name` varchar(255) default NULL,
  `config_value` tinytext,
  `form_type` mediumtext,
  `config_type` varchar(255) default NULL,
  PRIMARY KEY `addon` (`id`,`addon_id`)
) TYPE=MyISAM;


### Menu table entries
INSERT INTO `renprefix_menu` VALUES (1, 'main', 'b1:b2:b3|b4:b5:b6');

# --------------------------------------------------------
### Menu Button entries
INSERT INTO `renprefix_menu_button` VALUES (1, 0, 'pvplist', 'guildpvp');
INSERT INTO `renprefix_menu_button` VALUES (2, 0, 'memberlog', 'memberlog');
INSERT INTO `renprefix_menu_button` VALUES (3, 0, 'professions', 'tradeskills');
INSERT INTO `renprefix_menu_button` VALUES (4, 0, 'upprofile', 'update');
INSERT INTO `renprefix_menu_button` VALUES (5, 0, 'roster_cp_ab', 'rostercp');
INSERT INTO `renprefix_menu_button` VALUES (6, 0, 'credit', 'credits');

# --------------------------------------------------------
### The roster version and db version MUST be last

UPDATE `renprefix_config` SET `config_value` = '1.8.0' WHERE `id` = '4' LIMIT 1;
UPDATE `renprefix_config` SET `config_value` = '6' WHERE `id` = '3' LIMIT 1;

ALTER TABLE `renprefix_config` ORDER BY `id`;