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
// --[ Translate GET/POST data ]--
if (isset($_GET['section']))
{
	$section = $_GET['section'];
}
else
{
	$section = 'main';
}
if( isset($_POST['doglobal']) && $_POST['doglobal'] )
{
	if( $roster_login->getAuthorized($roster_conf['auth_editglobalmenu']))
	{
		$account = 0;
	}
	else
	{
		$body .= messagebox('You are not authorized to edit the global menu configuration<br /><a href="'.$script_filename.'?page=menu&amp;section='.$section.'">Personal config</a>');
		return;
	}
}
else
{
	$account = $roster_login->getAccount;
	if( $account < 0 )
	{
		$body .= messagebox('This config section is not relevant for guests. Please log in.','Roster','sred');
		return;
	}
}


// --[ Write submitted menu configuration to DB if applicable ]--
if (isset($_POST['process']) && $_POST['process'] == 'process')
{

}

// --[ Fetch button list from DB ]--
$query = "SELECT * FROM ".$wowdb->table('menu_button');

$result = $wowdb->query($query);

if (!$result)
{
	die_quietly('Could not fetch buttons from database. MySQL said: <br />'.$wowdb->error(),'Roster');
}

while ($row = $wowdb->fetch_assoc($result))
{
	$palet['b'.$row['button_id']] = $row;
	$dhtml_reg .= ', "b'.$row['button_id'].'"';
}

$wowdb->free_result($result);

// --[ Fetch menu configuration from DB ]--
$query = "SELECT * FROM ".$wowdb->table('menu')." WHERE `account_id` = '".$account."' AND `section` = '".$section."'";

$result = $wowdb->query($query);

if (!$result)
{
	die_quietly('Could not fetch menu configuration from database. MySQL said: <br />'.$wowdb->error(),'Roster');
}

$row = $wowdb->fetch_assoc($result);

$wowdb->free_result($result);

$arrayHeight = 0;
foreach(explode('|',$row['config']) AS $posX=>$column)
{
	$config[$posX] = explode(':',$column);
	foreach($config[$posX] as $posY=>$button)
	{
		$arrayButtons[$posX][$posY] = $palet[$button];
		unset($palet[$button]);
	}
	$arrayHeight = max($arrayHeight, count($arrayButtons[$posX]));
}
$arrayWidth = count($arrayButtons);

$paletHeight = count($palet);
$paletWidth = 1;

// --[ Render configuration screen. ]--
if (!isset($html_head))
{
	$html_head = '';
}
$html_head .= '    <script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/wz_dragdrop.js"></script>'."\n";
$html_head .= '    <script type="text/javascript" src="'.$roster_conf['roster_dir'].'/css/js/menuconf.js"></script>'."\n";

/* Insert select box for which menu to configure in $menu. Remember a checkbox for global/personal if current user can edit global */
$menu .= messagebox('<div id="palet" style="width:'.(105*$paletWidth+5).'px;height:'.(30*$paletHeight+5).'px;"></div>','Unused buttons','sblue');
foreach($palet as $id=>$button)
{
	$menu .= '<div id="'.$id.'" style="position:absolute;width:100px;height:25px;">'.$button['title'].'</div>'."\n";
}
$body .= border('sgreen','start',$section);
$body .= '<div id="array" style="width:'.(105*$arrayWidth+5).'px;height:'.(30*$arrayHeight+5).'px;"></div>'."\n";
$body .= border('sgreen','end',$section);

foreach($arrayButtons as $posX=>$column)
{
	foreach($column as $posY=>$button)
	{
		$body .= '<div id="b'.$button['button_id'].'" style="position:absolute;width:100px;height:25px;">'.$button['title'].'</div>'."\n";
	}
}

$body .=
'<script type="text/javascript">
<!--

SET_DHTML(CURSOR_MOVE, "palet"+NO_DRAG, "array"+NO_DRAG'.$dhtml_reg.');

var dy		= 30;
var margTop	= 5;
var dx		= 105;
var margLef	= 5;

var arrayWidth = '.$arrayWidth.';
var arrayHeight = '.$arrayHeight.';
var paletHeight = '.$paletHeight.';

// Array intended to reflect the order of the draggable items
var aElts = Array();
var palet = Array();'."\n";

$i = 0;

foreach ($palet as $id => $button)
{
	$body .= 'palet['.$i++.'] = dd.elements.'.$id.';';
}

foreach ($arrayButtons as $posX => $column)
{
	$body .= 'aElts['.$posX.'] = Array();'."\n";
	foreach ($column as $posY => $button)
	{
		$body .= 'aElts['.$posX.']['.$posY.'] = dd.elements.b'.$button['button_id'].';'."\n";
	}
}

$body .= '
updatePositions();
//-->
</script>';

?>
