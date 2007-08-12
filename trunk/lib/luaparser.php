<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LUA parsing and creation class
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 * @package    WoWRoster
 * @subpackage Lua
*/

if( !defined('IN_ROSTER') )
{
    exit('Detected invalid access to this file!');
}

/**
 * WoWRoster LUA handling class
 *
 * @package    WoWRoster
 * @subpackage Lua
 */
class lua
{
	var $file_location = '';
	var $errormessage = '';

	/**
	 * Set the location of the lua file to handle
	 *
	 * @param string	$file_location
	 * @return bool		If file exists and is readable
	 */
	function setfile( $file_location )
	{
		if( file_exists($file_location) && is_readable($file_location) )
		{
			$this->file_location = $file_location;
			return true;
		}
		else
		{
			$this->seterror('[' . $file_location . '] does not exist or is not readable by PHP');
			return false;
		}
	}

	/**
	 * Set the error when this fails for some reason
	 * NEVER OUR FAULT! LOL
	 *
	 * @param string	$message
	 */
	function seterror( $message )
	{
		$this->errormessage = $message;
	}

	/**
	 * Get the error that happend
	 * P.E.B.C.A.K.
	 *
	 * @return unknown
	 */
	function error( )
	{
		return $this->errormessage;
	}

	/**
	 * Set the location of the lua file
	 * Convert to a PHP array
	 * And return the array
	 *
	 * @param string	$file_location
	 * @return array	Lua file converted to PHP array
	 */
	function luatophp( $file_location )
	{
		if( $this->setfile($file_location) )
		{
			return $this->tophp();
		}
		else
		{
			return false;
		}
	}

	/**
	 * Main LUA to PHP array converting function
	 *
	 * @author six, originally mordon
	 *
	 * @return array	Lua file converted to PHP array
	 */
	function tophp( )
	{
		if( $this->file_location != '' )
		{
			$stack = array( array( '',  array() ) );
			$stack_pos = 0;

			$file = gzopen($this->file_location,'r');

			while( !gzeof($file) )
			{
				$line = gzgets($file);
				$line = trim($line);

				if( empty($line) )
				{
					continue;
				}
				// Look for end of an array
				if( isset($line[0]) && $line[0] == '}' )
				{
					$hash = $stack[$stack_pos];
					unset($stack[$stack_pos]);
					$stack_pos--;
					$stack[$stack_pos][1][$hash[0]] = $hash[1];
					unset($hash);
				}
				// Handle other cases
				else
				{
					// Check if the key is given
					if( strpos($line,'=') )
					{
						list($name, $value) = explode( '=', $line, 2 );
						$name = trim($name);
						$value = trim($value,', ');
						if($name[0]=='[')
						{
							$name = trim($name, '[]"');
						}
					}
					// If we have nothing, then this isn't a lua data file
					elseif( $stack_pos == 0 )
					{
						$this->seterror('LUA parsing error. Are you sure this is a SavedVariables file?');
						return false;
					}
					// Otherwise we'll have to make one up for ourselves
					else
					{
						$value = $line;
						if( empty($stack[$stack_pos][1]) )
						{
							$name = 1;
						}
						else
						{
							$name = max(array_keys($stack[$stack_pos][1]))+1;
						}
						if( strpos($line,'-- [') )
						{
							$value = explode('-- [',$value);
							array_pop($value);
							$value = implode('-- [',$value);
						}
						$value = trim($value,', ');
					}
					if( isset($value) && $value == '{' )
					{
						$stack_pos++;
						$stack[$stack_pos] = array($name, array());
					}
					else
					{
						if( isset($value[0]) && $value[0]=='"' )
						{
							$value = substr($value,1,-1);
						}
						elseif( $value == 'true' )
						{
							$value = true;
						}
						elseif( $value == 'false' )
						{
							$value = false;
						}
						elseif( $value == 'nil' )
						{
							$value = NULL;
						}
						$stack[$stack_pos][1][$name] = $value;
					}
				}
			}

			gzclose($file);
			return($stack[0][1]);
		}
		else
		{
			$this->seterror('File location was not set. Are you trying to access this function directly?');
			return false;
		}
	}

	/**
	 * LUA creator
	 * Converts a PHP array into a lua file
	 * When calling from outside, only the first parameter should be passed
	 *
	 * I'm sure this can be sped up if anyone knows how to do it nicely
	 * without recursion.
	 *
	 * @param array		$phparray	The PHP array to write
	 * @param string	$indent		Tells how far to indent
	 * @param bool		$top		True for the initial call, bool for others
	 *
	 * @return string The lua result
	 * bool	False on error
	 */
	function phptolua( $phparray , $indent='' , $top=false )
	{
		if( $indent == '' )
		{
			$top = true;
		}
		foreach( $phparray as $name => $value )
		{
			$out .= $indent;
			if( $top )
			{
				if( is_numeric($name) )
				{
					$this->seterror('No numeric top-level keys allowed');
					return false;
				}
				$out .= $name;
			}
			elseif( is_numeric($name) )
			{
				$out .= '['.$name.']';
			}
			else
			{
				$out .= '["'.$name.'"]';
			}

			$out .= ' = ';

			if( is_array($value) )
			{
				$out .= '{' . "\n";
				$out .= $this->phptolua($value, $indent . "\t");
				$out .= $indent . '}';
			}
			elseif( is_string($value) )
			{
				$out .= '"' . addcslashes($value,"\n\"\\") . '"';
			}
			elseif( is_numeric($value) )
			{
				$out .= $value;
			}
			elseif( is_null($value) )
			{
				$out .= 'nil';
			}
			elseif( is_bool($value) && $value )
			{
				$out .= 'true';
			}
			elseif( is_bool($value) && !$value )
			{
				$out .= 'false';
			}

			if( $top )
			{
				$out .= "\n";
			}
			else
			{
				$out .= ",\n";
			}
		}
		return $out;
	}
}
