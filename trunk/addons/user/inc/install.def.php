<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license	http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version	SVN: $Id$
 * @link	   http://www.wowroster.net
 * @package	News
 * @subpackage Installer
*/

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * News Addon Installer
 * @package News
 * @subpackage Installer
 */
class userInstall
{
	var $active = true;
	var $icon = 'inv_misc_bag_26_spellfire';

	var $version = '0.1';
	var $wrnet_id = '0';

	var $fullname = 'Guild User pages';
	var $description = 'user registration, profile pages and more';
	var $credits = array(
		array(	"name"=>	"Ulminia",
				"info"=>	"Original author")
	);


	/**
	 * Install function
	 *
	 * @return bool
	 */
	function install()
	{
		global $installer;

		//begin the magic of user settings
		
		/**
		*	Tables
		**/
		
		$installer->create_table($installer->table('profile'),"
			`uid` INT(11) NOT NULL,
			`signature` varchar(255) NOT NULL,
			`avatar` varchar(255) NOT NULL,
			`avsig_src` varchar(32) NOT NULL,
			`show_fname` INT(11) NOT NULL default '0',
			`show_lname` INT(11) NOT NULL default '0',
			`show_email` INT(11) NOT NULL default '0',
			`show_city` INT(11) NOT NULL default '0',
			`show_country` INT(11) NOT NULL default '0',
			`show_homepage` INT(11) NOT NULL default '0',
			`show_notes` INT(11) NOT NULL default '0',
			`show_joined` INT(11) NOT NULL default '0',
			`show_lastlogin` INT(11) NOT NULL default '0',
			`show_chars` INT(11) NOT NULL default '0',
			`show_guilds` INT(11) NOT NULL default '0',
			`show_realms` INT(11) NOT NULL default '0',
			PRIMARY KEY (`uid`)"
			);

		$installer->create_table($installer->table('messaging'),"
			`msgid` int(11) NOT NULL auto_increment,
			`uid` smallint(6) NOT NULL,
			`title` varchar(255) NOT NULL default '',
			`body` text NOT NULL,
			`sender` int(11) NOT NULL,
			`senderLevel` int(11) NOT NULL,
			`read` int(11) NOT NULL default 0,
			`date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
			PRIMARY KEY  (`msgid`),
			KEY (`uid`)"
			);
		$installer->create_table($installer->table('user_link'),"
			`uid` INT(11) unsigned NOT NULL default '0',
			`member_id` INT(11) unsigned NOT NULL default '0',
			`guild_id` INT(11) unsigned NOT NULL default '0',
			`group_id` smallint(6) NOT NULL default '1',
			`is_main` smallint(6) NOT NULL default '0',
			`realm` varchar(32) NOT NULL default '',
			`region` varchar(32) NOT NULL default '',
			PRIMARY KEY (`member_id`),
			KEY `uid` (`uid`)"
			);
		/**
		* admin section settings
		**/


		/**
		* Master and menu entries 
		**/
		$installer->add_menu_button('menu_register','user','register','inv_misc_bag_26_spellfire');
		$installer->add_menu_button('user_menu_chars','user','chars','spell_holy_divinespirit');
		$installer->add_menu_button('user_menu_guilds','user','guilds','inv_misc_tabardpvp_02'); 
		$installer->add_menu_button('user_menu_realms','user','realms','spell_holy_lightsgrace');
		$installer->add_menu_button('user_menu_mail','user','mail','inv_letter_11');
		$installer->add_menu_button('user_menu_settings','user','settings','inv_misc_wrench_02');
		return true;
	}

	/**
	 * Upgrade functoin
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade($oldversion)
	{
		global $installer;

		
		return true;
	}

	/**
	 * Un-Install function
	 *
	 * @return bool
	 */
	function uninstall()
	{
		global $installer;
		$installer->remove_all_config();
		$installer->remove_all_menu_button();
		return true;
	}
}
