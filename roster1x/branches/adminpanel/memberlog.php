<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

require_once( 'settings.php' );

//---[ Check for Guild Info ]------------
if( empty($guild_info) )
{
	die_quietly( $wordings[$roster_conf['roster_lang']]['nodata'] );
}
// Get guild info from guild info check above
$guildId = $guild_info['guild_id'];

if( !isset($_REQUEST['d']) || empty($_REQUEST['d']) )
	$get_d = 0;
else
	$get_d = intval($_REQUEST['d']);

if( !isset($_REQUEST['s']) || empty($_REQUEST['s']) )
	$get_s = '';
else
	$get_s = $_REQUEST['s'];


require( ROSTER_BASE.'roster_header.tpl' );
require( ROSTER_LIB.'menu.php' );

$query =
"SELECT *,
 DATE_FORMAT( `update_time`, '".$timeformat[$roster_conf['roster_lang']]."' ) AS 'date'
 FROM `".ROSTER_MEMBERLOGTABLE."`";


$chkd['n'] = 1;
$chkd['c'] = 1;
$chkd['l'] = 1;
$chkd['r'] = 1;
$chkd['t'] = 1;
$chkd['d'] = 1;

switch($get_s)
{
	case 'name':
		if( $get_d )
		{
			$query .= ' ORDER BY `name` ASC';
			$chkd['n'] = 0;
		}
		else
		{
			$query .= ' ORDER BY `name` DESC';
		}
	break;

	case 'class':
		if( $get_d )
		{
			$query .= ' ORDER BY `class` ASC';
			$chkd['c'] = 0;
		}
		else
		{
			$query .= ' ORDER BY `class` DESC';
		}
	break;

	case 'level':
		if( $get_d )
		{
			$query .= ' ORDER BY `level` DESC';
			$chkd['l'] = 0;
		}
		else
		{
			$query .= ' ORDER BY `level` ASC';
		}
	break;

	case 'title':
		if( $get_d )
		{
			$query .= ' ORDER BY `guild_rank` ASC';
			$chkd['r'] = 0;
		}
		else
		{
			$query .= ' ORDER BY `guild_rank` DESC';
		}
	break;

	case 'type':
		if( $get_d )
		{
			$query .= ' ORDER BY `type` DESC';
			$chkd['t'] = 0;
		}
		else
		{
			$query .= ' ORDER BY `type` ASC';
		}
	break;

	case 'date':
		if( $get_d )
		{
			$query .= ' ORDER BY `update_time` DESC, `type` ASC';
		}
		else
		{
			$query .= ' ORDER BY `update_time` ASC, `type` ASC';
		}
	break;

	default:
		$query .= ' ORDER BY `update_time` DESC, `type` ASC';
		break;

}

$content = '';

if ($roster_conf['sqldebug'])
	$content .= ("<!--$query-->\n");


$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

$borderTop = border('sgreen', 'start', $wordings[$roster_conf['roster_lang']]['memberlog']);
$tableHeader = '<table width="100%" cellspacing="0" class="bodyline">'."\n";

$borderBottom = border('sgreen', 'end');
$tableFooter = '</table>'."\n";


$striping_counter = 0;
if( $wowdb->num_rows($result) > 0 )
{
	$tableHeaderRow = '	<tr>
	<th class="membersHeader"><a href="?s=name&amp;d='.$chkd['n'].'">'.$wordings[$roster_conf['roster_lang']]['name'].'</a></th>
	<th class="membersHeader"><a href="?s=class&amp;d='.$chkd['c'].'">'.$wordings[$roster_conf['roster_lang']]['class'].'</a></th>
	<th class="membersHeader"><a href="?s=level&amp;d='.$chkd['l'].'">'.$wordings[$roster_conf['roster_lang']]['level'].'</a></th>
	<th class="membersHeader"><a href="?s=title&amp;d='.$chkd['r'].'">'.$wordings[$roster_conf['roster_lang']]['title'].'</a></th>
	<th class="membersHeader"><a href="?s=type&amp;d='.$chkd['t'].'">'.$wordings[$roster_conf['roster_lang']]['type'].'</a></th>
	<th class="membersHeader"><a href="?s=date&amp;d='.$chkd['d'].'">'.$wordings[$roster_conf['roster_lang']]['date'].'</a></th>
	<th class="membersHeaderRight">'.$wordings[$roster_conf['roster_lang']]['note'].'</th>
	</tr>'."\n";

	while ( $row = $wowdb->fetch_assoc( $result ) )
	{
		foreach( $row as $key => $value )
		{
			$row[$key] = $wowdb->escape($value);
		}

		if( $row['type'] == 0 )
			$row['type'] = '<span class="red">'.$wordings[$roster_conf['roster_lang']]['removed'].'</span>';
		else
			$row['type'] = '<span class="green">'.$wordings[$roster_conf['roster_lang']]['added'].'</span>';

		if( !empty($row['note']) )
			$row['note'] = '<img src="'.$roster_conf['img_url'].'note.gif" class="membersRowimg" alt="'.$wordings[$roster_conf['roster_lang']]['note'].'" '.makeOverlib(stripslashes($row['note']),$wordings[$roster_conf['roster_lang']]['note'],'',1).'>';
		else
			$row['note'] = '<img src="'.$roster_conf['img_url'].'no_note.gif" class="membersRowimg" alt="'.$wordings[$roster_conf['roster_lang']]['note'].'">';


		$body .= '<tr>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['name'].'</td>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['class'].'</td>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['level'].'</td>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['guild_title'].'</td>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['type'].'</td>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['date'].'</td>'."\n";
		$body .= '	<td class="membersRowRight'. (($striping_counter % 2) +1) .'">'.$row['note'].'</td>'."\n";
		$body .= '</tr>'."\n";

		$striping_counter++;
	}
}
else
{
	$body = $wordings[$roster_conf['roster_lang']]['no_memberlog'];
}

$content .= $borderTop;
$content .= $tableHeader;
$content .= $tableHeaderRow;
$content .= $body;
$content .= $tableFooter;
$content .= $borderBottom;


$wowdb->closeQuery($result);
$wowdb->free_result($result);


print $content;

require( ROSTER_BASE.'roster_footer.tpl' );

?>