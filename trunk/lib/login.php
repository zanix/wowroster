<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Login and authorization
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.7.1
 * @package    WoWRoster
 * @subpackage User
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

define('ROSTERLOGIN_ADMIN',11);

/**
 * Login and authorization
 *
 * @package    WoWRoster
 * @subpackage User
 */
class RosterLogin
{
	var $allow_login;
	var $message;
	var $action;
	var $logout;
	var $levels = array();
	var $valid;
	var $radid = 30;
	var $approved;
	var $access;
	
	/**
	 * Constructor for Roster Login class
	 * Accepts an action for the form
	 * And an array of additional fields
	 *
	 * @param $script_filename
	 * @return RosterLogin
	 */
	function RosterLogin( $script_filename='' )
	{
		global $roster;

		$this->setAction($script_filename);

		if( isset( $_POST['logout'] ) && $_POST['logout'] == '1' )
		{
			setcookie('roster_user','',time()-(60*60*24*30*100) );
			setcookie('roster_pass','',time()-(60*60*24*30*100) );
			setcookie('roster_remember','',time()-(60*60*24*30*100) );
			$this->allow_login = false;
			$this->valid = 0;
			$this->message = $roster->locale->act['logged_out'];
		}
		elseif( isset($_POST['password']) && $_POST['password'] != '' && isset($_POST['username']) && $_POST['username'] != '')
		{
			$this->checkPass(md5($_POST['password']),$_POST['username']);
		}
		elseif( isset($_COOKIE['roster_pass']) && isset($_COOKIE['roster_user']) )
		{
			$this->checkPass($_COOKIE['roster_pass'],$_COOKIE['roster_user']);
		}
		else
		{
			$this->allow_login = false;
			$this->message = '';
		}
	}

	function checkPass( $pass, $user )
	{
		global $roster;

		$query = "SELECT * FROM `" . $roster->db->table('user_members') . "` WHERE usr='".$user."' AND pass='".$pass."' ;";
		$result = $roster->db->query($query);

		if( !$result )
		{
			setcookie('roster_user','',time()-(60*60*24*30*100) );
			setcookie('roster_pass','',time()-(60*60*24*30*100) );
			setcookie('roster_remember','',time()-(60*60*24*30*100) );
			$this->allow_login = false;
			$this->valid = 0;
			$this->message = $roster->locale->act['login_fail'];
			return;
		}

		if( $result )
		{
			$remember = (isset($_POST['rememberMe']) ? (int)$_POST['rememberMe'] : (int)$_COOKIE['roster_remember'] );
			$row = $roster->db->fetch($result);
			
			setcookie('roster_user',$user,(time()+60*60*24*30) );
			setcookie('roster_pass',$pass,(time()+60*60*24*30) );
			setcookie('roster_remember',$remember,(time()+60*60*24*30) );
			$this->valid = 1;
			$this->allow_login = true;
			$this->access = $row['access'];
			$this->logout = '<form class="inline slim" name="roster_logout" action="' . $this->action . '" method="post" enctype="multipart/form-data"><input type="hidden" name="logout" value="1" /> <button type="submit">' . $roster->locale->act['logout'] . '</button></form>';
			$this->message = '<span class="login-message">Welcome, '.$user.' '.$this->logout.'</span>';
			$roster->db->free_result($result);
			return;

		}
		$roster->db->free_result($result);

		setcookie('roster_user','',time()-(60*60*24*30*100) );
		setcookie('roster_pass','',time()-(60*60*24*30*100) );
		setcookie('roster_remember','',time()-(60*60*24*30*100) );
		$this->allow_login = false;
		$this->message = $roster->locale->act['login_invalid'];
		return;
	}

	function getAuthorized( $access )
	{
		
		//echo $this->allow_login.' - '.$access;
		///* user acceess checking this is kinda cool and really new to roster
		$lvl = array();
		$lvl = explode(":",$this->access);
		//print_r($lvl);
		$x = 0;
		//echo '-'.$x.'-';
		foreach ($lvl as $acc => $a)
		{
			//echo $acc.' - '.$a.'<br>';
			if ($a == $access)
			{
				$x = 1;
			}
			
		}
		if (is_array($lvl))
		{
			if (in_array(ROSTERLOGIN_ADMIN, $lvl))
			{
				$x = 1;
			}
		}

		if ($this->access == ROSTERLOGIN_ADMIN)
		{
			$x = 1;
		}
		//echo '<font color=white>+'.$x.'+</font>';
		if ($x == 1)
		{
			$this->approved = 1;
			return true;
		}
		else
		{
			$this->approved = 0;
			if ($this->allow_login)
			{
				roster_die('Invalid access lvl to access admin section logout and login as admin or ask for admin access from admin');
				return false;
			}
			else
			{
				echo $this->getLoginForm(ROSTERLOGIN_ADMIN);
			}
		}
		
		//*/
	}

	function getMessage()
	{
		return $this->message;
	}

	function getLoginForm( $level = ROSTERLOGIN_ADMIN )
	{
		global $roster;

		/*
		if( $this->approved != 1 )
		{
			roster_die('Invalid access lvl to access admin section logout and login as admin or ask for admin access from admin');
		}
		*/
		if( !$this->allow_login && !isset($_POST['logout']) ) // && $this->approved==1 )
		{
			//echo 'check login';
			$query = "SELECT * FROM `" . $roster->db->table('user_members') . "` WHERE `access` = '" . $level . "';";
			$result = $roster->db->query($query);

			if( !$result )
			{
				die_quietly($roster->db->error, 'Roster Auth', __FILE__,__LINE__,$query);
			}

			if( $roster->db->num_rows($result) != 1 )
			{
				//die_quietly('Invalid required login level specified', 'Roster Auth');
			}

			$row = $roster->db->fetch($result);
			$roster->db->free_result($result);
			//$this->ValadateUser ();
			$login_message = $this->getMessage();

			$roster->tpl->assign_block_vars('login', array(
				'U_LOGIN_ACTION'  	=> (isset($this->action) ? $this->action : $action),
				'L_LOGIN_WORD'    	=> $row['usr'],
				'S_LOGIN_MESSAGE' 	=> (bool)$login_message,
				'L_LOGIN_MESSAGE' 	=> $login_message,
				'L_REGISTER'		=> '<a href="'.makelink("register", true).'"><br>Register Here!</a>',
				'U_LOGIN' 			=>  0
			));

			$roster->tpl->set_handle('roster_login', 'login_new.html');
			return $roster->tpl->fetch('roster_login');
		}
		else
		{
			return $this->getMessage();
		}
	}

	function getMenuLoginForm()
	{
		global $roster;

		if( !$this->allow_login )
		{
			$login_message = $this->getMessage();

			$roster->tpl->assign_vars(array(
				'U_LOGIN_ACTION'  => $this->action,
				'S_LOGIN_MESSAGE' => (bool)$login_message,
				'L_LOGIN_MESSAGE' => $login_message,
				'U_LOGIN' 			=>  $this->valid
			));

			$roster->tpl->set_handle('roster_menu_login', 'menu_login.html');
			return $roster->tpl->fetch('roster_menu_login');
		}
		else
		{
			$logout_message = $this->getMessage();

			$roster->tpl->assign_vars(array(
				'U_LOGOUT_ACTION'  => $this->action,
				'S_LOGOUT_MESSAGE' => (bool)$logout_message,
				'L_LOGOUT_MESSAGE' => $logout_message,
				'U_LOGIN' 			=>  $this->valid
			));

			$roster->tpl->set_handle('roster_menu_logout', 'menu_logout.html');
			return $roster->tpl->fetch('roster_menu_logout');
		}
	}

	function setAction( $action )
	{
		$this->action = makelink($action);
	}

	function makeAccess( $values )
	{
		global $roster;

		if( count($this->levels) == 0 )
		{
			$query = "SELECT DISTINCT (`guild_rank`), `guild_title` FROM `" . $roster->db->table('members') . "` ORDER BY `guild_rank` ASC";
			$result = $roster->db->query($query);
			if( !$result )
			{
				die_quietly($roster->db->error, 'Roster Auth', __FILE__,__LINE__,$query);
			}
			$this->levels[11] = 'CP Admin';
			$this->levels[0] = 'Public';
			$x ='1';
			while( $row = $roster->db->fetch($result) )
			{
				$this->levels[($row['guild_rank']+1)] = $row['guild_title'];
				//$x++;
			}
		}
		$name = $values['name'];
		$x = '';
		$x .= '<div class="radioset">';
		$lvl = explode(":",$values['value']);
		foreach ($this->levels as $acc => $a)
		{
			$this->radid++;
			$x .= '<input type="checkbox" name="'.$name.'['.$acc.']" id="rad_config_'.$this->radid.'" value="'.$acc.'"  '.(in_array($acc, $lvl) ? 'checked="checked"' : '') .' />
			<label for="rad_config_'.$this->radid.'">'.substr($a,0,9).'</label>';
		}
		$x .= '</div>';
			
		return $x;
	}
	
	function GetAccess()
	{
		global $roster;

		if( count($this->levels) == 0 )
		{
			$query = "SELECT DISTINCT (`guild_rank` ), `guild_title` FROM `" . $roster->db->table('members') . "` ORDER BY `guild_rank` ASC";
			$result = $roster->db->query($query);

			if( !$result )
			{
				die_quietly($roster->db->error, 'Roster Auth', __FILE__,__LINE__,$query);
			}

			$this->levels[11] = 'Roster Admin';
			$this->levels[0] = 'Public';
			while( $row = $roster->db->fetch($result) )
			{
				$this->levels[($row['guild_rank']+1)] = $row['guild_title'];
			}
		}
		return $this->levels;
	}
	
	function rosterAccess( $values )
	{
		global $roster;

		if( count($this->levels) == 0 )
		{
			$query = "SELECT DISTINCT (`guild_rank`), `guild_title` FROM `" . $roster->db->table('members') . "` ORDER BY `guild_rank` ASC";
			$result = $roster->db->query($query);
			if( !$result )
			{
				die_quietly($roster->db->error, 'Roster Auth', __FILE__,__LINE__,$query);
			}
			$this->levels[11] = 'CP Admin';
			$this->levels[0] = 'Public';
			$x ='1';
			while( $row = $roster->db->fetch($result) )
			{
				$this->levels[($row['guild_rank']+1)] = $row['guild_title'];
				//$x++;
			}
			//$this->levels[11] = 'Public';
		}
			$name = $values['name'];
			$x = '';
			$x .= '<div class="radioset">';
			$lvl = explode(":",$values['value']);
			foreach ($this->levels as $acc => $a)
			{
				$this->radid++;
				$x .= '<input type="checkbox" name="config_'.$name.'['.$acc.']" id="rad_config_'.$this->radid.'" value="'.$acc.'"  '.(in_array($acc, $lvl) ? 'checked="checked"' : '') .' />
				<label for="rad_config_'.$this->radid.'">'.substr($a,0,9).'</label>';
			}
			$x .= '</div>';
			if ($roster->output['title'] == $roster->locale->act['pagebar_addoninst'])
			{
				//$x = '<div class="config-input">'.$x.'</div>';
			}
			//$x .= '</tr></table>';
		return $x;
	}
	
}
