<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2007
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

$header_title = $wordings[$roster_conf['roster_lang']]['search'];
include_once (ROSTER_BASE.'roster_header.tpl');

require_once ROSTER_LIB.'item.php';
require_once ROSTER_LIB.'recipes.php';


include_once(ROSTER_LIB.'menu.php');
print "<br />\n";

if (isset($_POST['s']))
{
	$inputbox_value = $_POST['s'];
}
?>

<form action="<?php echo makelink('search') ?>" method="post">
  <?php print $wordings[$roster_conf['roster_lang']]['find'] ?>:<br />
  <input type="text" class="wowinput192" name="s" value="<?php print $inputbox_value; ?>" size="30" maxlength="30" />
  <input type="submit" value="search" />
</form>

<?php
if (isset($_POST['s']))
{
	// Set a ank for link to top of page
	echo '<a name="top">&nbsp;</a>
<div style="color:white;text-align;center">
  <a href="#items">'.$wordings[$roster_conf['roster_lang']]['items'].'</a>
  - <a href="#recipes">'.$wordings[$roster_conf['roster_lang']]['recipes'].'</a>
</div><br /><br />';

	$search = $_POST['s'];
	print '<a name="items"></a><a href="#top">'.$wordings[$roster_conf['roster_lang']]['items'].'</a>';

	$query="SELECT players.name,players.server,items.* FROM `".ROSTER_ITEMSTABLE."` items,`".ROSTER_PLAYERSTABLE."` players WHERE items.member_id = players.member_id AND items.item_name LIKE '%$search%' ORDER BY players.name ASC";
	$result = $wowdb->query( $query );

	if (!$result)
	{
		die_quietly('There was a database error trying to fetch matching items. MySQL said: <br />'.$wowdb->error(),'Search',basename(__FILE__),__LINE__,$query);
	}

	if( $wowdb->num_rows($result) != 0 )
	{
		$cid = '';
		$rc = 0;
		while ($data = $wowdb->fetch_assoc( $result ))
		{
			$row_st = (($rc%2)+1);
			$char_url = makelink('char&amp;member='.$data['member_id']);

			if ( $cid != $data['member_id'] )
			{
				if ( $cid != '' )
				{
					print "</table>\n".border('sblue','end')."<br />\n";
				}
				print border('sblue','start','<a href="'.$char_url.'">'.$data['name'].'</a>').'<table cellpadding="0" cellspacing="0" width="600">';
			}

			print '  <tr>
    <td width="45" valign="top" class="membersRow'.$row_st.'">';
			$item = new item($data);
			echo $item->out();
			print "</td>\n";
			print '    <td valign="middle" class="membersRowRight'.$row_st.'" style="white-space:normal;">';

			$first_line = true;
			$tooltip_out = '';
			$data['item_tooltip'] = stripslashes($data['item_tooltip']);

			print colorTooltip($data['item_tooltip'],$data['item_color']);

			print "</td>\n  </tr>\n";
			$cid = $data['member_id'];
			$rc++;
		}

		if ( $cid != '' )
		{
			print "</table>\n".border('sblue','end')."<br />\n";
		}
	}
	else
	{
		print '<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="membersRowRight1">No '.$wordings[$roster_conf['roster_lang']]['items'].'</td>
  </tr>'."</table>\n";
	}


	print "<br /><hr />\n";

	print '<a name="recipes"></a><a href="#top">'.$wordings[$roster_conf['roster_lang']]['recipes'].'</a>';

	//$query="SELECT players.name,players.server,recipes.* FROM recipes,players WHERE recipes.member_id = players.member_id AND recipes.recipe_name LIKE '%$search%' OR recipes.recipe_tooltip LIKE '%$search%' OR recipes.reagents LIKE '%$search%' ORDER BY players.name ASC, recipes.recipe_name ASC";
	$query="SELECT players.name,players.server,recipes.* FROM `".ROSTER_RECIPESTABLE."` recipes,`".ROSTER_PLAYERSTABLE."` players WHERE recipes.member_id = players.member_id AND recipes.recipe_name LIKE '%$search%' ORDER BY players.name ASC, recipes.recipe_name ASC";
	$result = $wowdb->query( $query );

	if (!$result)
	{
		die_quietly('There was a database error trying to fetch matching recipes. MySQL said: <br />'.$wowdb->error(),'Search',basename(__FILE__),__LINE__,$query);
	}

	if( $wowdb->num_rows($result) != 0 )
	{
		$cid = '';
	//name | server | member_id | recipe_name | skill_name | difficulty | reagents | recipe_texture | recipe_tooltip
		$rc = 0;
		while ($data = $wowdb->fetch_assoc( $result ))
		{
			$row_st = (($rc%2)+1);

			$char_url = makelink('char&amp;member='.$data['member_id'].'&amp;action=recipes');
			if ( $cid != $data['member_id'] )
			{
				if ( $cid != '' )
				{
					print "</table>\n".border('syellow','end')."<br />\n";
				}
				print border('syellow','start','<a href="'.$char_url.'">'.$data['name'].'</a>').'<table border="0" cellpadding="0" cellspacing="0" width="600">
  <tr>
    <th colspan="2" class="membersHeader">'.$wordings[$roster_conf['roster_lang']]['item'].'</th>
    <th class="membersHeaderRight">'.$wordings[$roster_conf['roster_lang']]['reagents'].'</th>';
			}

			print '<tr><td width="45" valign="top" align="center" class="membersRow'.$row_st.'">';

			$recipe = new recipe($data);
			echo $recipe->out();
				print '</td>'."\n";
			print '<td valign="top" class="membersRow'.$row_st.'" style="white-space:normal;">';

			$first_line = true;
			$tooltip_out = '';
			$data['item_tooltip'] = stripslashes($data['recipe_tooltip']);

			print colorTooltip($data['item_tooltip'],$data['item_color']);

			print '</td>'."\n".'<td class="membersRowRight'.$row_st.'" width="50%" valign="top">';
			echo "<span class=\"tooltipline\" style=\"color:#ffffff\">".str_replace('<br>','<br />',$data['reagents'])."</span><br /><br />";
			print "</td></tr>\n";
			$cid = $data['member_id'];
			$rc++;
		}

		if ( $cid != '' )
		{
			print "</table>\n".border('syellow','end')."<br />\n";
		}
	}
	else
	{
		print border('sblue','start').'<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="membersRowRight1">No '.$wordings[$roster_conf['roster_lang']]['recipes'].'</td>
  </tr>'."</table>\n".border('sblue','end');
	}
}

include_once (ROSTER_BASE.'roster_footer.tpl');
