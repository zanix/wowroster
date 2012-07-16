<?php
/**
 * WoWRoster.net WoWRoster
 *
 * MySQL Interface
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.8.0
 * @package    WoWRoster
 * @subpackage MySQL
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

define('SQL_ASSOC',MYSQL_ASSOC);
define('SQL_NUM',MYSQL_NUM);
define('SQL_BOTH',MYSQL_BOTH);

/**
 * SQL_DB class, MySQL version
 * Abstracts MySQL database functions
 *
 * @package    WoWRoster
 * @subpackage MySQL
 */
class roster_db
{
	var $link_id     = 0;                   // Connection link ID       @var link_id
	var $query_id    = 0;                   // Query ID                 @var query_id
	var $record      = array();             // Record                   @var record
	var $record_set  = array();             // Record set               @var record_set
	var $query_count = 0;                   // Query count              @var query_count
	var $queries     = array();             // Queries                  @var queries
	var $error_die   = true;                // Die on errors?           @var error_die
	var $log_level   = 0;                   // Log SQL transactions     @var log_level

	var $prefix      = '';
	var $dbname      = '';

	var $querytime   = 0;
	var $file;
	var $line;

	/**
	 * Log the query
	 *
	 * @param string $query
	 */
	function _log( $query )
	{
		$this->_backtrace();

		$this->queries[$this->file][$this->query_count]['query'] = $query;
		$this->queries[$this->file][$this->query_count]['time'] = round((format_microtime()-$this->querytime), 4);
		$this->queries[$this->file][$this->query_count]['line'] = $this->line;

		// Error message in case of failed query
		$this->queries[$this->file][$this->query_count]['error'] = empty($this->query_id) ? $this->error() : '';

		// Describe
		$this->queries[$this->file][$this->query_count]['describe'] = array();

		if( $this->log_level == 2 )
		{
			// Only SELECT queries can be DESCRIBEd. If this isn't a SELECT query, this will properly extract the SELECT part of an INSERT ... SELECT or CREATE TABLE ... SELECT statement, which may be interesting to get describe info for.
			if( ($pos = strpos( $query, "SELECT" )) === false )
			{
				return;
			}

			$result = mysql_query("DESCRIBE " . substr($query, $pos));
			if( $result )
			{
				while( $this->queries[$this->file][$this->query_count]['describe'][] = mysql_fetch_assoc( $result ) ) {};
				mysql_free_result( $result );
			}
		}
	}

	/**
	 * Backtrace the query, to get the calling file name
	 */
	function _backtrace()
	{
		$this->file = 'unknown';
		$this->line = 0;
		if( version_compare(phpversion(), '4.3.0','>=') )
		{
			$tmp = debug_backtrace();
			for ($i=0; $i<count($tmp); ++$i)
			{
				if (!preg_match('#[\\\/]{1}lib[\\\/]{1}dbal[\\\/]{1}[a-z_]+.php$#', $tmp[$i]['file']))
				{
					$this->file = $tmp[$i]['file'];
					$this->line = $tmp[$i]['line'];
					break;
				}
			}
		}
	}

	/**
	 * Constructor
	 *
	 * Connects to a MySQL database
	 *
	 * @param $dbhost Database server
	 * @param $dbname Database name
	 * @param $dbuser Database username
	 * @param $dbpass Database password
	 * @param $prefix Database prefix
	 * @return mixed Link ID / false
	 */
	function roster_db( $dbhost, $dbname, $dbuser, $dbpass, $prefix='' )
	{
		$this->prefix = $prefix;
		$this->dbname = $dbname;

		if( empty($dbpass) )
		{
			$this->link_id = @mysql_connect($dbhost, $dbuser);
		}
		else
		{
			$this->link_id = @mysql_connect($dbhost, $dbuser, $dbpass);
		}

		@mysql_query("SET NAMES 'utf8'");
		@mysql_query("SET GLOBAL general_log = 'ON'");

		if( (is_resource($this->link_id)) && (!is_null($this->link_id)) && ($dbname != '') )
		{
			if( !@mysql_select_db($dbname, $this->link_id) )
			{
				@mysql_close($this->link_id);
				$this->link_id = false;
			}
			return $this->link_id;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Closes MySQL connection
	 *
	 * @return bool
	 */
	function close_db( )
	{
		if( $this->link_id )
		{
			if( $this->query_id && is_resource($this->query_id) )
			{
				@mysql_free_result($this->query_id);
			}
			return @mysql_close($this->link_id);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get last SQL error
	 *
	 * @return string last SQL error
	 */
	function error()
	{
		$result = @mysql_errno($this->link_id) . ': ' . mysql_error($this->link_id);
		return $result;
	}

	/**
	 * Get last SQL errno
	 *
	 * @return string last SQL errno
	 */
	function errno()
	{
		$result = @mysql_errno($this->link_id);
		return $result;
	}

	/**
	 * Get connection error
	 */
	function connect_error()
	{
		return @mysql_errno() . ': ' . mysql_error();
	}

	/**
	 * Basic query function
	 *
	 * @param $query Query string
	 * @return mixed Query ID / Error string / Bool
	 */
	function query( $query )
	{
		// Remove pre-existing query resources
		unset($this->query_id);

		//$query = preg_replace('/;.*$/', '', $query);

		$this->querytime = format_microtime();

		if( $query != '' )
		{
			$this->query_count++;
			$this->query_id = @mysql_query($query, $this->link_id);
		}

		if( !empty($this->query_id) )
		{
			if( $this->log_level > 0 )
			{
				$this->_log($query);
			}
			unset($this->record[$this->query_id]);
			unset($this->record_set[$this->query_id]);
			return $this->query_id;
		}
		elseif( $this->error_die )
		{
			// I think we should use this method for dying
			die(__FILE__ . ': line[' . __LINE__ . ']<br />Database Error "' . $query . '"<br />MySQL said:<br />' . $this->error());
			//die_quietly($this->error(), 'Database Error',__FILE__,__LINE__,$query);
		}
		else
		{
			$this->_log($query);
			trigger_error('Database error. See query log for details', E_USER_NOTICE);

			return false;
		}
	}

	/**
	 * Return the first record (single column) in a query result
	 *
	 * @param $query Query string
	 */
	function query_first( $query )
	{
		$this->query($query);
		$record = $this->fetch($this->query_id);
		$this->free_result($this->query_id);

		return $record?$record[0]:false;
	}

	/**
	 * Build query
	 *
	 * @param $query
	 * @param $array Array of field => value pairs
	 */
	function build_query( $query , $array = false )
	{
		if( !is_array($array) )
		{
			return false;
		}

		$fields = array();
		$values = array();

		if( $query == 'INSERT' )
		{
			foreach( $array as $field => $value )
			{
				$fields[] = "`$field`";

				if( is_null($value) )
				{
					$values[] = 'NULL';
				}
				elseif( is_string($value) )
				{
					$values[] = "'" . $this->escape($value) . "'";
				}
				else
				{
					$values[] = ( is_bool($value) ) ? intval($value) : $value;
				}
			}

			$query = ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
		}
		elseif( $query == 'UPDATE' )
		{
			foreach( $array as $field => $value )
			{
				if( is_null($value) )
				{
					$values[] = "`$field` = NULL";
				}
				elseif( is_string($value) )
				{
					$values[] = "`$field` = '" . $this->escape($value) . "'";
				}
				else
				{
					$values[] = ( is_bool($value) ) ? "`$field` = " . intval($value) : "`$field` = $value";
				}
			}

			$query = implode(', ', $values);
		}

		return $query;
	}

	/**
	 * Fetch one record
	 *
	 * @param $query_id Query ID
	 * @param $result_type SQL_ASSOC SQL_NUM or SQL_BOTH
	 * @return mixed Record / false
	 */
	function fetch( $query_id = 0, $result_type = SQL_BOTH)
	{
		if( !$query_id )
		{
			$query_id = $this->query_id;
		}

		if( $query_id )
		{
			$this->record[$query_id] = @mysql_fetch_array($query_id, $result_type);
			return $this->record[$query_id];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Fetch all records
	 *
	 * @param $query_id Query ID
	 * @param $result_type SQL_ASSOC, SQL_NUM, or SQL_BOTH
	 * @return mixed Record Set / false
	 */
	function fetch_all( $query_id = 0, $result_type = SQL_BOTH )
	{
		if( !$query_id )
		{
			$query_id = $this->query_id;
		}
		if( $query_id )
		{
			$result = array();
			unset($this->record_set[$query_id]);
			unset($this->record[$query_id]);
			while( $this->record_set[$query_id] = @mysql_fetch_array($query_id, $result_type) )
			{
				$result[] = $this->record_set[$query_id];
			}
			return $result;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get result data
	 *
	 * @param $query_id Query ID
	 * @param $row The row number from the result that's being retrieved. Row numbers start at 0
	 * @param $field The name or offset of the field being retrieved
	 * @return mixed Record / false
	 */
	function result( $query_id = 0, $row = 0, $field = '' )
	{
		if( !$query_id )
		{
			$query_id = $this->query_id;
		}

		if( $query_id )
		{
			$this->record[$query_id] = @mysql_result($query_id, $row, $field);
			return $this->record[$query_id];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Find the number of returned rows
	 *
	 * @param $query_id Query ID
	 * @return mixed Number of rows / false
	 */
	function num_rows( $query_id = 0 )
	{
		if( !$query_id )
		{
			$query_id = $this->query_id;
		}

		if( $query_id )
		{
			$result = @mysql_num_rows($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Finds out the number of rows affected by a query
	 *
	 * @return mixed Affected Rows / false
	 */
	function affected_rows( )
	{
		return ( $this->link_id ) ? @mysql_affected_rows($this->link_id) : false;
	}

	/**
	 * Find the ID of the row that was just inserted
	 *
	 * @return mixed Last ID / false
	 */
	function insert_id( )
	{
		if( $this->link_id )
		{
			$result = @mysql_insert_id($this->link_id);
			return $result;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Free result data
	 *
	 * @param $query_id Query ID
	 * @return bool
	 */
	function free_result( $query_id = 0 )
	{
		if( !$query_id )
		{
			$query_id = $this->query_id;
		}

		if( $query_id )
		{
			unset($this->record[$query_id]);
			unset($this->record_set[$query_id]);

			@mysql_free_result($query_id);

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Remove quote escape
	 *
	 * @param $string
	 * @return string
	 */
	function escape( $string )
	{
		if( version_compare( phpversion(), '4.3.0', '>' ) )
		{
			return mysql_real_escape_string( $string );
		}
		else
		{
			return mysql_escape_string( $string );
		}
	}

	/**
	 * Set the error_die var
	 *
	 * @param $setting
	 */
	function error_die( $setting = true )
	{
		$oldval = $this->error_die;
		$this->error_die = $setting;
		return $oldval;
	}

	/**
	 * Set the log_level var
	 *
	 * @param $setting
	 */
	function log_level( $setting = 1 )
	{
		$this->log_level = $setting;
	}

	/**
	 * Expand base table name to a full table name
	 *
	 * @param string $table the base table name
	 * @param string $addon the name of the addon, empty for a base roster table
	 * @return string tablename as fit for MySQL queries
	 */
	function table($table, $addon='', $type='addons')
	{
		if( $addon )
		{
			return $this->prefix . $type . '_' . $addon . ($table != '' ? '_' . $table : '');
		}
		else
		{
			return $this->prefix . $table;
		}
	}

	/**
	 * Retrieves mysql server information
	 * @return string mysql server info
	 */
	function server_info()
	{
		if( is_resource($this->link_id) )
		{
			return mysql_get_server_info($this->link_id);
		}
		else
		{
			return false;
		}
	}

	/**
	 * Retrieves mysql client information
	 * @return string mysql client info
	 */
	function client_info()
	{
		return mysql_get_client_info();
	}
}
