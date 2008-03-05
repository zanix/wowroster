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
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

if( $addon['config']['show_item_bonuses'] )
{
	$roster->output['html_head'] .= '<script type="text/javascript" src="' . $addon['url_path'] . 'js/overlib_overtwo.js"></script>';
	$roster->output['html_head'] .= '<script type="text/javascript" src="' . ROSTER_PATH . 'js/overlib.js"></script>';
}
require($addon['dir'] . 'inc/header.php');

$char_page .= $char->out();

if( $addon['config']['show_item_bonuses'] )
{
	$char_page .= "</td><td align=\"left\">\n";
	require($addon['dir'] . 'inc/charbonus.lib.php');
	$char_bonus = new CharBonus($char);
	$char_page .= $char_bonus->dumpBonus();
	unset($char_bonus);
}

require($addon['dir'] . 'inc/footer.php');
