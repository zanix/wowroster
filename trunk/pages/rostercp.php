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

/******************************
 * Call parameters:
 *
 * page
 *		roster		Roster config
 *		character	Per-character preferences
 *		addon		Addon config
 *		install		Addon installation screen
 *
 * addon	If page is addon, this says which addon is being configured
 * profile	If page is addon, this says which addon profile is being configured.
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Check log-in ]-------------------------------------
$roster_login = new RosterLogin();

// Disallow viewing of the page
if( !$roster_login->getAuthorized() )
{
	include_once (ROSTER_BASE.'roster_header.tpl');
	include_once (ROSTER_LIB.'menu.php');

	print
	'<span class="title_text">'.$act_words['roster_config'].'</span><br />'.
	$roster_login->getMessage().
	$roster_login->getLoginForm();

	include_once (ROSTER_BASE.'roster_footer.tpl');

	exit();
}
// ----[ End Check log-in ]---------------------------------

include_once(ROSTER_ADMIN.'pages.php');

$header = $menu = $body = $pagebar = $footer = '';

// Find out what subpage to include, and do so
$page = (isset($roster_pages[1]) && ($roster_pages[1]!='')) ? $roster_pages[1] : 'roster';

if( isset($config_pages[$page]['file']) )
{
	if (file_exists(ROSTER_ADMIN.$config_pages[$page]['file']))
	{
		require_once(ROSTER_ADMIN.$config_pages[$page]['file']);
	}
	else
	{
		$body .= $roster_login->getMessage().'<br />'.messagebox(sprintf($act_words['roster_cp_not_exist'],$page),$act_words['roster_cp'],'sred');
	}
}
else
{
	$body .= $roster_login->getMessage().'<br />'.messagebox($act_words['roster_cp_invalid'],$act_words['roster_cp'],'sred');
}

// Build the pagebar from admin/pages.php
foreach ($config_pages as $pindex => $data)
{
	if (!isset($data['special']))
	{
		$pagebar .= '<li'.($roster_pages[0].'-'.$page == $data['href'] ? ' class="selected"' : '').'><a href="'.makelink($data['href']).'">'.$act_words[$data['title']].'</a></li>'."\n";
	}
	elseif ($data['special'] == 'divider')
	{
		$pagebar .= '<li><hr /></li>';
	}
}

if ($pagebar != '')
{
	$pagebar = "<ul class=\"tab_menu\">\n$pagebar</ul>";
	$pagebar = messagebox($pagebar,$act_words['pagebar_function'])."<br />\n";
}

// Add addon buttons
$query = 'SELECT `basename`,`hasconfig` FROM `'.$wowdb->table('addon').'` WHERE `hasconfig` != "";';
$result = $wowdb->query($query);
if( !$result )
{
	die_quietly('Could not fetch addon records for pagebar','Roster Admin Panel',__LINE__,basename(__FILE__),$query);
}

if ($wowdb->num_rows($result))
{
	$pagebar .= border('sgray','start',$act_words['pagebar_addonconf'])."\n";
	$pagebar .= '<ul class="tab_menu">'."\n";
	while($row = $wowdb->fetch_assoc($result))
	{
		$pagebar .= '<li'.(isset($roster_pages[2]) && $roster_pages[2] == $row['basename'] ? ' class="selected"' : '').'><a href="'.makelink('rostercp-addon-'.$row['basename']).'">'.$row['basename'].'</a></li>'."\n";
	}
	$pagebar .= '</ul>'."\n";
	$pagebar .= border('sgray','end')."\n";
}

// ----[ Render the page ]----------------------------------
include_once( ROSTER_BASE.'roster_header.tpl' );
include_once( ROSTER_LIB.'menu.php' );

echo
	$header."\n".
	'<table width="100%"><tr>'."\n".
	'<td valign="top" align="left" width="15%">'."\n".
	$menu."</td>\n".
	'<td valign="top" align="center" width="70%">'."\n".
	$body."</td>\n".
	'<td valign="top" align="right" width="15%">'."\n".
	$pagebar."</td>\n".
	'</tr></table>'."\n".
	$footer;

include_once( ROSTER_BASE.'roster_footer.tpl' );
