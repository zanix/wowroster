<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Displays character information
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: index.php 867 2007-04-29 07:41:43Z Zanix $
 * @link       http://www.wowroster.net
 * @package    Character Info
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

include( $addon['dir'] . 'inc/header.php' );

ob_start();
	$char->out();
$char_page .= ob_get_clean();


if( $addon['config']['show_item_bonuses'])
{
	$char_page .= "</td><td align=\"left\">\n";

	require_once ($addon['dir'] . 'inc/charbonus.lib.php');
	$char_bonus = new CharBonus($char);
	$char_page .= $char_bonus->dumpBonus();
	unset($char_bonus);
}

include( $addon['dir'] . 'inc/footer.php' );
