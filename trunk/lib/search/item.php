<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.0
 * @package    WoWRoster
 * @subpackage Search
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}


/**
 * QuestList Search
 *
 * @package    QuestList
 * @subpackage Search
 */
class roster_itemSearch
{
	var $options;
	var $result = array();
	var $result_count = 0;
	var $start_search;
	var $stop_search;
	var $time_search;
	var $link_next;
	var $link_prev;
	var $open_table;
	var $close_table;
	var $data = array();    // Addon data

	// class constructor
	function roster_itemSearch()
	{
		global $roster;

		require_once (ROSTER_LIB . 'item.php');

		$this->open_table = '<tr><th class="membersHeader ts_string">' . $roster->locale->act['item'] . '</th>'
						  . '<th class="membersHeader ts_string">Lv</th>'
						  . '<th class="membersHeader ts_string">' . $roster->locale->act['name'] . '</th>'
						  . '<th class="membersHeaderRight ts_string">' . $roster->locale->act['character'] . '</th></tr>';

		//advanced options for searching zones
		$this->options = '
<label for="item_minle">' . $roster->locale->act['level'] . ':</label>
	<input type="text" name="item_minle" id="item_minle" size="3" maxlength="3"/> -
	<input type="text" name="item_maxle" id="item_maxle" size="3" maxlength="3" /><br />
	<label for="item_quality">Quality:</label><br />
	<select name="item_quality[]" id="item_quality" size="6" multiple="multiple">
		<option value="9d9d9d" style="color:#9d9d9d;">Poor</option>
		<option value="ffffff" style="color:#ffffff;">Common</option>
		<option value="1eff00" style="color:#1eff00;">Uncommon</option>
		<option value="0070dd" style="color:#0070dd;">Rare</option>
		<option value="a335ee" style="color:#a335ee;">Epic</option>
		<option value="ff8800" style="color:#ff8800;">Legendary</option>
	</select>';
	}

	function search( $search , $url_search , $limit=10 , $page=0 )
	{
		global $roster;

		$first = $page*$limit;

		$minlvl = isset($_POST['item_minle']) ? intval($_POST['item_minle']) : '';
		$maxlvl = isset($_POST['item_maxle']) ? intval($_POST['item_maxle']) : '';
		$quality = isset($_POST['item_quality']) ? $_POST['item_quality'] : '';

		$q  = "SELECT `players`.`name`, `players`.`member_id`, `players`.`server`, `players`.`region`, `items`.*"
			. " FROM `" . $roster->db->table('items') . "` items,`" . $roster->db->table('players') . "` AS players"
			. " WHERE `items`.`member_id` = `players`.`member_id`"
				. " AND (`items`.`item_name` LIKE '%$search%' OR `items`.`item_tooltip` LIKE '%$search%')"
				. ( $minlvl != '' ? " AND `items`.`level` >= '$minlvl'" : '' )
				. ( $maxlvl != '' ? " AND `items`.`level` <= '$maxlvl'" : '' );

		if( $quality != '' )
		{
			foreach( $quality as $level )
			{
				$q .= " AND `items`.`item_color` = '$level'";
			}
		}

		$q .= " ORDER BY `players`.`name` ASC"
			. ( $limit > 0 ? " LIMIT $first," . ($limit+1) : '' ) . ';';

		//calculating the search time
		$this->start_search = format_microtime();

		$result = $roster->db->query($q);

		$this->stop_search = format_microtime();
		$this->time_search = round($this->stop_search - $this->start_search,3);

		$nrows = $roster->db->num_rows($result);

		$x = ($limit > $nrows) ? $nrows : ($limit > 0 ? $limit : $nrows);
		if( $nrows > 0 )
		{
			while( $row = $roster->db->fetch($result) )
			{
				$icon = new item($row);

				$item['html'] = '<td class="SearchRowCell">' . $icon->out() . '</td>'
							  . '<td class="SearchRowCell">' . $icon->requires_level . '</td>'
							  . '<td class="SearchRowCell"><span style="color:#' . $icon->color . '">[' . $icon->name . ']</span></td>'
							  . '<td class="SearchRowCellRight"><a href="' . makelink('char-info&amp;a=c:' . $row['member_id']) . '"><strong>' . $row['name'] . '</strong></a></td>';

				$this->add_result($item);
				unset($item);
				$x--;
			}
		}

		if( $page > 0 )
		{
			$this->link_prev = '<a href="' . makelink('search&amp;page=' . ($page-1) . '&amp;search=' . $url_search . '&amp;s_addon=' . $this->data['basename']) . '"><strong>' . $roster->locale->act['search_previous_matches'] . $this->data['fullname'] . '</strong></a>';
		}
		if( $nrows > $limit )
		{
			$this->link_next = '<a href="' . makelink('search&amp;page=' . ($page+1) . '&amp;search=' . $url_search . '&amp;s_addon=' . $this->data['basename']) . '"><strong> ' . $roster->locale->act['search_next_matches'] . $this->data['fullname'] . '</strong></a>';
		}
	}

	function add_result( $resultarray )
	{
		$this->result[$this->result_count++] = $resultarray;
	}
}
