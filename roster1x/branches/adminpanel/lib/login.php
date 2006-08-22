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

/**
 * Note: None of the variables in this class are required. Most functions are,
 * it is noted in the function comment if a function is not required.
 *
 * Also note the getAuthorized and login functions have to support the roster
 * admin account. The user for that is always Roster_Admin, the pass is defined
 * in $roster_conf['roster_upd_pw'].
 * Other functions should not support the admin account.
 */

class RosterLogin
{
	var $user;	// User name. Roster_Admin for roster admin.
	var $level;	// -1 roster admin, 0 GM, 0-9 guild ranks, 10 anonymous
	var $message;	// Login result message
	var $loginform;	// Login form
	var $script_filename;

	/**
	 * Constructor for Roster Login class
	 * Parameter is the file any results should be sent to.
	 * THIS IS A REQUIRED FUNCTION
	 *
	 * @param string $script_filename
	 * @return RosterLogin
	 */
	function RosterLogin($script_filename)
	{
		$this->script_filename = $script_filename;
		$this->level = 10;

		$this->checkLogin();
		$this->checkLogout();
	}

	/**
	 * Try to verify supplied login creds. Not a required function.
	 */
	function checkLogin()
	{
		global $roster_conf, $wowdb;
		
		if( isset($_COOKIE['roster_pass']) )
		{
			if (get_magic_quotes_gpc())
			{
				$supplied = unserialize(stripslashes($_COOKIE['roster_pass']));
			}
			else
			{
				$supplied = unserialize($_COOKIE['roster_pass']);
			}
		}
		elseif( isset($_POST['user_name']) && isset($_POST['pass_word']) )
		{
			$supplied = array('user' => $_POST['user_name'], 'hash' => md5($_POST['pass_word']));
		}
		else
		{
			if( isset($_COOKIE['roster_pass']) )
				setcookie( 'roster_pass','',time()-86400,'/' );
			$this->message = '<span style="font-size:11px;color:red;">Not logged in</span><br />';
			$this->user = 'Guest';
			$this->level = 10;
			return;
		}
		
		if ($supplied['user'] == 'Roster_Admin')
		{
			$proper['name'] = 'Roster_Admin';
			$proper['hash'] = $roster_conf['roster_upd_pw'];
			$proper['level'] = -1;
		}
		else
		{
			$query = 'SELECT `name`, `hash`, `level` FROM '.$wowdb->table('account').' WHERE `name` = "'.$supplied['user'].'"';

			$result = $wowdb->query($query);

			if (!$result)
			{
				if( isset($_COOKIE['roster_pass']) )
					setcookie( 'roster_pass','',time()-86400,'/' );
				$this->message = '<span style="font-size:11px;color:red;">Database problem: Unable to verify supplied credentials. MySQL said: '.$wowdb->error().'</span><br />';
				$this->user = 'Guest';
				$this->level = 10;
				return;
			}

			$proper = $wowdb->fetch_assoc($result);
			
			if (!$proper)
			{
				if( isset($_COOKIE['roster_pass']) )
					setcookie( 'roster_pass','',time()-86400,'/' );
				$this->message = '<span style="font-size:11px;color:red;">Incorrect user name or password</span><br />';
				$this->user = 'Guest';
				$this->level = 10;
				return;			
			}
			
			$wowdb->free_result($result);
		}

		if( $supplied['hash'] == $proper['hash'] )
		{
			setcookie( 'roster_pass',serialize($supplied),0,'/' );
			$this->message = '<span style="font-size:10px;color:red;">Logged in:</span><form style="display:inline;" name="roster_logout" action="'.$this->script_filename.'" method="post"><span style="font-size:10px;color:#FFFFFF"><input type="hidden" name="logout" value="1" />[<a href="javascript:document.roster_logout.submit();">Logout</a>]</span></form><br />';
			$this->user = $row['name'];
			$this->level = $row['level'];
		}
		else
		{
			if( isset($_COOKIE['roster_pass']) )
				setcookie( 'roster_pass','',time()-86400,'/' );
			$this->message = '<span style="font-size:11px;color:red;">Incorrect user name or password</span><br />';
			$this->user = 'Guest';
			$this->level = 10;
		}
	}

	/**
	 * Logout if requested. Not a required function.
	 */
	function checkLogout()
	{
		if( isset( $_POST['logout'] ) && $_POST['logout'] == '1' )
		{
			if( isset($_COOKIE['roster_pass']) )
				setcookie( 'roster_pass','',time()-86400,'/' );
			$this->message = '<span style="font-size:10px;color:red;">Logged out</span><br />';
			$this->user = 'Guest';
			$this->level = 10;
		}
	}

	/**
	 * Check if the user is authorized to do this. There are 3 ways to call
	 * this function.
	 *
	 * @return boolean $creds
	 *	If the parameter is left out: True for roster admin, false
	 *	otherwise.
	 *
	 * @param string $creds
	 *	The credentials needed to perform this action.
	 *	For RosterAuth, this is a number, and lower numbers are better
	 *	credentials.
	 * @return boolean
	 *	True for allowed, false for disallowed.	 
	 *
	 * @param array $creds
	 *	Simple array($key => $creds,...) where $creds are required
	 *	credentials
	 * @return array
	 *	Simple array($key => $allowed,...) with the same keys as $creds,
	 *	$allowed is true if $creds[$key] is met.
	 */
	function getAuthorized($creds = '')
	{
		if ($creds = '')
		{
			return $this->level == -1;
		}

		if (!is_array($creds))
		{
			return $this->level <= (int)$creds;
		}
		
		foreach ($creds as $key => $level)
		{
			$perms[$key] = ($this->level <= (int)$level);
		}
		return $perms;
	}
	
	/**
	 * Return user name
	 *
	 * @return string $user
	 *	The user name
	 */
	function getUserName()
	{
		return $this->user;
	}

	/**
	 * Return the result message for other class functions.
	 *
	 * @return string $html
	 *	The message.
	 */
	function getMessage()
	{
		return $this->message;
	}

	/**
	 * Return the login box
	 *
	 * @return string $html
	 *	The login box.
	 */
	function getLoginForm()
	{
		return '
			<!-- Begin Password Input Box -->
			<form action="'.$this->script_filename.'" method="post" enctype="multipart/form-data" onsubmit="submitonce(this)">
			'.border('sred','start','Authorization Required').'
			  <table class="bodyline" cellspacing="0" cellpadding="0">
			    <tr>
			      <td class="membersRow2">User Name: </td>
			      <td class="membersRowRight2"><input name="user_name" type="text" size="30" maxlength="30" /></td>
			    </tr>
			    <tr>
			      <td class="membersRow1">Password: </td>
			      <td class="membersRowRight1"><input name="pass_word" type="password" size="30" maxlength="30" /></td>
			    </tr>
			    <tr>
			      <td class="membersRowRight2" valign="bottom" colspan="2">
			        <div align="right"><input type="submit" value="Go" /></div></td>
			    </tr>
			  </table>
			'.border('sred','end').'
			</form>
			<!-- End Password Input Box -->';
	}
	
	/**
	 * Try to create an account
	 *
	 * @param string $user
	 *	Username
	 * @param string $pass1
	 *	The password
	 * @param string $pass2
	 *	The confirmed password
	 * @param int $level
	 *	The user level for the account to be created
	 * @return boolean $success
	 *	True for success, false for failure
	 *
	 * A descriptive result message can be got from the getMessage function.
	 */
	function createAccount($user, $pass1, $pass2)
	{
	 	if ( $newpass1 != $newpass2 )
	 	{
	 		$this->message = 'Passwords do not match. Please type the exact same password in both password fields.';
	 		return false;
	 	}
	 	
	 	if ( $newpass1 === '' || $newpass2 === '')
	 	{
	 		$this->message = 'No blank passwords. Please enter a password in both fields. Blank passwords are not allowed.';
	 		return false;
	 	}
	 	
	 	if ( md5($newpass1) == md5('') )
	 	{
	 		$this->message = 'No blank passwords. You did not enter a blank password but it does have the same hash. Blank passwords are not allowed.';
	 		return false;
	 	}
	 	
	 	// valid password
		$query = 'INSERT INTO `'.$wowdb->table('account').'` VALUES (0, "'.$user.'", "'.md5($newpass1).'", 10)';

		$result = $wowdb->query($query);

		if (!$result)
		{
			$this->message = 'There was a database error while trying to create the account. MySQL said: <br />'.$wowdb->error();
			return false;
		}

		$this->message = 'Account created. Your password is <span style="font-size:11px;color:red;">'.$_POST['newpass1'].'</span>.<br /> Do not forget this password, it is stored encrypted only.';
		return true;
	}
	
	/**
	 * Try to change the account password. This function does not and should
	 * never change the admin pass.
	 *
	 * @param string $user
	 *	Username
	 * @param string $oldpass
	 *	The old password
	 * @param string $newpass1
	 *	The password
	 * @param string $newpass2
	 *	The confirmed password
	 * @return boolean $success
	 *	True for success, false for failure
	 *
	 * A descriptive result message can be got from the getMessage function.
	 */
	function changePass($user, $oldpass, $newpass1, $newpass2)
	{
		$query = 'SELECT `hash` FROM '.$wowdb->table('account').' WHERE `name` = "'.$user.'"';

		$result = $wowdb->query($query);

		if (!$result)
		{
			$this->message = 'Database problem: Unable to verify old password. MySQL said: <br />'.$wowdb->error();
			return false;
		}

		$row = $wowdb->fetch_assoc($result);

		if (!$row)
		{
			$this->message = 'Invalid old user name';
			return false;			
		}

		$wowdb->free_result($result);

	 	if ( md5($oldpass) != $row['hash'] )
	 	{
	 		$this->message = 'Wrong password. Please enter the correct old password.';
	 		return false;
	 	}
	 	
	 	if ( $newpass1 != $newpass2 )
	 	{
	 		$this->message = 'Passwords do not match. Please type the exact same password in both new password fields.';
	 		return false;
	 	}
	 	
	 	if ( $newpass1 === '' || $newpass2 === '')
	 	{
	 		$this->message = 'No blank passwords. Please enter a password in both fields. Blank passwords are not allowed.';
	 		return false;
	 	}
	 	
	 	if ( md5($newpass1) == md5('') )
	 	{
	 		$this->message = 'No blank passwords. You did not enter a blank password but it does have the same hash. Blank passwords are not allowed.';
	 		return false;
	 	}
	 	
	 	if ( md5($newpass1) == $row['hash'] )
	 	{
	 		$this->message = 'Password not changed. The new password was the same as the old one.';
	 		return false;
	 	}
	 	
	 	// valid password
		$query = 'UPDATE `'.$wowdb->table('account').'` SET `hash` = "'.md5($newpass1).'"  WHERE `name` = "'.$user.'"';

		$result = $wowdb->query($query);

		if (!$result)
		{
			$this->message = 'There was a database error while trying to change the password. MySQL said: <br />'.$wowdb->error();
			return false;
		}

		$this->message = 'Password changed. Your new password is <span style="font-size:11px;color:red;">'.$_POST['newpass1'].'</span>.<br /> Do not forget this password, it is stored encrypted only.';
		return true;
	}
	
	/**
	 * Create a options control for setting an access level in Roster config.
	 *
	 * @param array $values
	 *	The form field name for this option
	 *
	 * @return string $html
	 *	The HTML for the option field
	 */
	function accessConfig($values)
	{
		$input_field = '<input name="config_'.$values['name'].'" type="text" value="'.$values['value'].'" size="4" maxlength="2" />';
	}
}

?>