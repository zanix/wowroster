<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: pvp3.php 897 2007-05-06 00:35:11Z Zanix $
 * @link       http://www.wowroster.net
*/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

define('IN_SORTMEMBER',true);

include_once ($addon['dir'] . 'inc/memberslist.php');

$memberlist = new memberslist;

$mainQuery =
	'SELECT '.
	'`guild`.`guild_name`, '.
	'`guild`.`faction`, '.
	'`guild`.`guild_num_members`, '.
	'`guild`.`guild_num_accounts`, '.
	'`guild`.`guild_motd` '.

	'FROM `'.$roster->db->table('guild').'` AS guild '.
	'WHERE `guild`.`server` = "'.$roster->data['server'].'" '.
	'ORDER BY ';

$FIELD['guild_name'] = array (
	'lang_field' => 'guild',
	'order' => array( '`guild`.`guild_name` ASC' ),
	'order_d' => array( '`guild`.`guild_name` DESC' ),
	'js_type' => 'ts_string',
	'display' => 3,
);

$FIELD['faction'] = array (
	'lang_field' => 'faction',
	'order' => array( '`guild`.`faction` ASC' ),
	'order_d' => array( '`guild`.`faction` DESC' ),
	'js_type' => 'ts_string',
	'display' => 2,
);

$FIELD['guild_num_members'] = array (
	'lang_field' => 'members',
	'order' => array( '`guild`.`guild_num_members` ASC' ),
	'order_d' => array( '`guild`.`guild_num_members` DESC' ),
	'js_type' => 'ts_number',
	'display' => 2,
);

$FIELD['guild_num_accounts'] = array (
	'lang_field' => 'accounts',
	'order' => array( '`guild`.`guild_num_accounts` ASC' ),
	'order_d' => array( '`guild`.`guild_num_accounts` DESC' ),
	'js_type' => 'ts_number',
	'display' => 2,
);

$FIELD['guild_motd'] = array (
	'lang_field' => 'MOTD',
	'order' => array( '`guild`.`guild_motd` ASC' ),
	'order_d' => array( '`guild`.`guild_motd` DESC' ),
	'value' => 'note_value',
	'js_type' => 'ts_string',
	'display' => 2,
);


$memberlist->prepareData($mainQuery, $FIELD, 'memberslist');

$roster->output['html_head'] .= '<script type="text/javascript" src="addons/'.$addon['basename'].'/js/sorttable.js"></script>';

// Start output
if( $addon['config']['member_update_inst'] )
{
	print '            <a href="' . makelink('#update') . '"><span style="font-size:20px;">'.$roster->locale->act['update_link'].'</span></a><br /><br />';
}


$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');
$roster->output['show_menu'] = false;

if( $addon['config']['member_hslist'] == 1 || $addon['config']['member_pvplist'] == 1 )
{
	echo "<table>\n  <tr>\n";

	if ( $addon['config']['member_hslist'] == 1 )
	{
		echo '    <td valign="top">';
		include_once( ROSTER_LIB.'hslist.php');
		echo generateHsList();
		echo "    </td>\n";
	}

	if ( $addon['config']['honor_pvplist'] == 1 && active_addon('pvplog') )
	{
		echo '    <td valign="top">';
		include_once( ROSTER_ADDONS.'pvplog'.DIR_SEP.'inc'.DIR_SEP.'pvplist.php');
		echo generatePvpList();
		echo "    </td>\n";
	}

	echo "  </tr>\n</table>\n";
}

echo $memberlist->makeFilterBox();

echo $memberlist->makeToolBar('horizontal');

echo "<br />\n".border('syellow','start')."\n";
echo $memberlist->makeMembersList();
echo border('syellow','end');

// Print the update instructions
if( $addon['config']['member_update_inst'] )
{
	print "<br />\n\n<a name=\"update\"></a>\n";

	echo border('sgray','start',$roster->locale->act['update_instructions']);
	echo '<div align="left" style="font-size:10px;background-color:#1F1E1D;">'.sprintf($roster->locale->act['update_instruct'], $roster->config['uploadapp'], $roster->locale->act['index_text_uniloader'], $roster->config['profiler'], makelink('update'), $roster->locale->act['lualocation']);
	echo '</div>'.border('sgray','end');
}

/**
 * Controls Output of a Note Column
 *
 * @param array $row - of character data
 * @return string - Formatted output
 */
function note_value ( $row, $field )
{
	global $roster, $addon;

	if( !empty($row[$field]) )
	{
		$note = htmlspecialchars(nl2br($row[$field]));

		if( $addon['config']['compress_note'] )
		{
			$note = '<img src="'.$roster->config['img_url'].'note.gif" style="cursor:help;" '.makeOverlib($note,$roster->locale->act['note'],'',1,'',',WRAP').' alt="[]" />';
		}
	}
	else
	{
		$note = '&nbsp;';
		if( $addon['config']['compress_note'] )
		{
			$note = '<img src="'.$roster->config['img_url'].'no_note.gif" alt="[]" />';
		}
	}

	return '<div style="display:none; ">'.htmlentities($row[$field]).'</div>'.$note;
}
