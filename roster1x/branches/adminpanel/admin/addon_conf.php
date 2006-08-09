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

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

// ----[ Get addon record ]---------------------------------
$query = 'SELECT * FROM '.$wowdb->table('addon').' WHERE `dbname` = "'.$_GET['addon'].'"';
$result = $wowdb->query($query);
if( !$result )
{
	die_quietly('Could not fetch addon record for '.$_GET['addon'],'Roster Admin Panel',__LINE__,basename(__FILE__),$query);
}
$addon = $wowdb->fetch_assoc($result);
$wowdb->free_result($result);

include(ROSTER_ADDONS.$addon['basename'].DIR_SEP.'localization.php');
if (file_exists(ROSTER_ADDONS.$addon['basename'].DIR_SEP.'config.func.php'))
{
	include(ROSTER_ADDONS.$addon['basename'].DIR_SEP.'config.func.php');
}


// ----[ Set the tablename and create the config class ]----
$tablename = $wowdb->table('config',$_GET['addon'],$_GET['profile']);
include(ROSTER_LIB.'config.lib.php');

// ----[ Process data if available ]------------------------
$save_message = $config->processData();

// ----[ Get configuration data ]---------------------------
$config->getConfigData();

// ----[ Build profile select box ]-------------------------
$menu  = border('sblue','start',$act_words['profileselect'])."\n";
$menu .= profilebox();
$menu .= border('sblue','end')."\n";
$menu .= '<br />'."\n";

// ----[ Build the page items using lib functions ]---------
$menu .= $config->buildConfigMenu();

$config->buildConfigPage();

if (isset($addon_hastopbox) && $addon_hastopbox)
{
	$body = topbox();
}
else
{
	$body = '';
}

$body.= $config->form_start.
	$config->submit_button.
	$config->formpages.
	$config->form_end.
	$config->nonformpages.
	$config->jscript;

function profilebox()
{
	global $wowdb;

	$menu = '<form method="get" action="">'."\n";
	$menu .= '<select name="profile">'."\n";

	$query = 'SHOW TABLES LIKE "'.str_replace('_','\_',$wowdb->table('config',$_GET['addon'],'%')).'"';
	if (!$result = $wowdb->query($query))
	{
		return 'Failed to get list of config tables for '.$_GET['addon'].'. MySQL said: '.$wowdb->error();
	}
	while ($row = $wowdb->fetch_row($result))
	{
		preg_match('|'.$wowdb->table('config',$_GET['addon'],'([\w]+)').'|i',$row[0],$prof);
		if ($prof[1] = $_GET['profile'])
		{
			$menu .= '<option value="'.$prof[1].'" selected="selected">&gt;'.$prof[1].'&lt;</option>'."\n";
		}
		else
		{
			$menu .= '<option value="'.$prof[1].'">'.$prof[1].'</option>'."\n";
		}
	}
	$wowdb->free_result($result);

	$menu .= '</select>'."\n";
	$menu .= '<input type="hidden" name="page" value="addon">';
	$menu .= '<input type="hidden" name="addon" value="'.$_GET['addon'].'">';
	$menu .= '<input type="submit" value="'.$act_words['go'].'">';
	$menu .= '</form>'."\n";

	return $menu;
}
?>