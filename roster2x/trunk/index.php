<?php

/**
 * Project: cpFramework - scalable object based modular framework
 * File: /index.php
 *
 * This file is available publicly, it runs and controls all
 * methods.
 *
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Legal Information:
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 *
 * Full License:
 *  license.txt (Included within this library)
 *
 * You should have recieved a FULL copy of this license in license.txt
 * along with this library, if you did not and you are unable to find
 * and agree to the license you may not use this library.
 *
 * For questions, comments, information and documentation please visit
 * the official website at cpframework.org
 *
 * @link http://cpframework.org
 * @license http://creativecommons.org/licenses/by-nc-sa/2.5/
 * @author Chris Stockton
 * @version 1.5.0
 * @copyright 2000-2006 Chris Stockton
 * @package cpFramework
 * @filesource
 *
 * Roster versioning tag
 * $Id$
 */

/**
 * DIR_SEP, since I'm lazy
 */
define("DIR_SEP", DIRECTORY_SEPARATOR);

/**
 * Security define. Used elsewhere to check if we're in the framework
 */
define("SECURITY", true);

/**
 * Site pathing and settings with trailing slash
 */
define("PATH_LOCAL", dirname(__FILE__).DIR_SEP );

if (!empty($_SERVER['SERVER_NAME']) || !empty($_ENV['SERVER_NAME']))
{
	define("PATH_REMOTE", "http://".((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME']) );
	define("PATH_REMOTE_S", "https://".((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME']) );
}
else if (!empty($_SERVER['HTTP_HOST']) || !empty($_ENV['HTTP_HOST']))
{
	define("PATH_REMOTE", "http://".((!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : $_ENV['HTTP_HOST']) );
	define("PATH_REMOTE_S", "https://".((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : $_ENV['SERVER_NAME']) );
}


/**
 * Turn on ALL errors during development, we keep our code crisp.. and clean
 * however turn them off after development for security reasons. Make sure to
 * actively controll this configuration setting.
 */
error_reporting(E_ALL);

/**
 * Require our common header, to initialize our class objects and
 * common shared activity.
 */
require(PATH_LOCAL . "library".DIR_SEP."common".DIR_SEP."common.header.php");

/**
 * We set our publicly available variables before doing anything these are
 * contained within our main class in a static variable.
 */
cpMain::$system['method_name'] = NULL;
cpMain::$system['method_mode'] = NULL;
cpMain::$system['method_type'] = NULL;
cpMain::$system['method_path'] = NULL;
cpMain::$system['template_path'] = NULL;

/**
 * Redirect handling based off the SYSTEM_REDIRECT_REQUEST.
 */
if(cpMain::$instance['cpconfig']->cpconf['redirect_www'] !== 'off')
{
	if(preg_match('/(^www\.+)/', $_SERVER['HTTP_HOST']) && cpMain::$instance['cpconfig']->cpconf['redirect_www'] === 'http')
	{
		header("Location: " . PATH_REMOTE);
	}
	elseif(!preg_match('/(www\.+)/', $_SERVER['HTTP_HOST']) && cpMain::$instance['cpconfig']->cpconf['redirect_www'] === 'www')
	{
		header("Location: " . preg_replace('/(http:\/\/|www\.)+/', 'http://www.', PATH_REMOTE));
	}
}

/**
 * Perform our autoloading
 */
foreach(file(PATH_LOCAL . "autoload.php") as $key => $value)
{
	(preg_match('/^[a-zA-Z0-9\.\-]+$/', $value = trim($value))) ? ((is_file($file = PATH_LOCAL . "library".DIR_SEP."autoload".DIR_SEP . $value . ".php")) ? include($file) : cpMain::cpErrorFatal("Autoload Error, Please consult the manual to see the proper directory hiearchy and system functionality. Remember, you can delete files out of the autoload list easily by opening autoload.php in your base directory. The path the system was looking for (or at least 1 of the paths we checked) is: " . $file, __LINE__, __FILE__)) : NULL;
}

/**
 * Shall we use a search friendly urls?
 */
if(cpMain::$instance['cpconfig']->cpconf['hide_param'])
{
	/**
	 * Get our get vars from the seo friendly URL, simple regex is very powerfull. Assuming
	 * you are using htaccess and have mod_rewrite running on your server. This feature can
	 * be disabled all together.
	 *
	 * Matches:
	 * foo1-bar1/2foo-bar2/something-else.html
	 * foo1-bar1/2foo-bar2/something-else/a.html
	 * foo1-bar1/2foo-bar2/something-else/
	 *  -- And more, you get the picture... --
	 *
	 * All result in:
	 *   ($_GET = Array ( [foo1] => bar1 [2foo] => bar2 [something] => else))
	 *
	 *
	 */
	preg_match_all("/([^\/\-\.?]+)\-([^\/\-\.?]+)/i", $_SERVER['REQUEST_URI'], $matches);

	/**
	 * Inject our variables directly into the _GET super global, we do this to prevent bad practice
	 * as placing them into the global scope with variable variables, or utilizing our system
	 * array. The _GET scope it is. Please don't argue me this practice as no logic will defeat
	 * me in my own mind : )
	 */
	foreach($matches[1] as $key => $value)
	{
		$_GET[$value] = $matches[2][$key];
	}
}

/**
 * Determine the users module/plugin request within switch function.
 */
switch(((isset($_GET['plugin']) Xor isset($_GET['module']))) ? (isset($_GET['module'])) ? "module" : "plugin" : "undefined")
{

	/**
	 * The users request is for module usage, we must set variables defining
	 * the library path and the mode in which the module shall be ran. We
	 * then define the method type - being module.
	 */
	case 'module':
		cpMain::$system['method_name'] = (isset($_GET['module'])) ? $_GET['module'] : NULL;
		cpMain::$system['method_mode'] = (isset($_GET['mode'])) ? $_GET['mode'] : $_GET['module'];
		cpMain::$system['method_path'] = cpMain::$system['method_name'] . DIR_SEP . cpMain::$system['method_mode'];
		cpMain::$system['method_type'] = "modules";
		break;

	/**
	 * The users request is for plugin usage, we must set the variables defining
	 * the library path, mode and name. As well as the type - being plugin.
	 */
	case 'plugin':
		cpMain::$system['method_name'] = (isset($_GET['plugin'])) ? $_GET['plugin'] : NULL;
		cpMain::$system['method_mode'] = (isset($_GET['plugin'])) ? $_GET['plugin'] : NULL;
		cpMain::$system['method_path'] = (isset($_GET['plugin'])) ? $_GET['plugin'] : NULL;
		cpMain::$system['method_type'] = "plugins";
		break;

	/**
	 * The users request is invalid or perhaps simply undefined. Theirfore we
	 * direct them to the default method. Setting variables accordingly.
	 */
	case 'undefined':
		cpMain::$system['method_name'] = (cpMain::$instance['cpconfig']->cpconf['def_method'] == "modules") ? cpMain::$instance['cpconfig']->cpframework['def_module'] : cpMain::$instance['cpconfig']->cpframework['def_plugin'];
		cpMain::$system['method_mode'] = (cpMain::$instance['cpconfig']->cpconf['def_method'] == "modules") ? cpMain::$instance['cpconfig']->cpframework['def_mode'] : cpMain::$instance['cpconfig']->cpframework['def_plugin'];
		cpMain::$system['method_path'] = (cpMain::$instance['cpconfig']->cpconf['def_method'] == "modules") ? cpMain::$system['method_name'] . DIR_SEP . cpMain::$system['method_mode'] : cpMain::$system['method_name'];
		cpMain::$system['method_type'] = cpMain::$instance['cpconfig']->cpconf['def_method'];
		break;

}

/**
 * Include the module/plugin core based on the method type and set path.
 */
((is_file($var = PATH_LOCAL . cpMain::$system['method_type'] . DIR_SEP . cpMain::$system['method_path'] . ".php")) ? require($var) : cpMain::cpErrorFatal("Error Loading Requested Method, the path the system was looking for (or at least 1 of the paths we checked) is: " . $var, __LINE__, __FILE__));

/**
 * We only initialize our template system only if the method chosen requires its
 * usage as I want the capability of non-template driven implimentations
 * of this system to remain possible. As its general purpose is to be a
 * invaluable tool across ALL development enviroments.
 */
if(is_object((isset(cpMain::$instance['smarty']) ? cpMain::$instance['smarty'] : NULL)))
{

	/**
	 * Make sure the specified module/plugin has a available theme (tempalte file)
	 */
	if(is_object((isset(cpMain::$instance['cpusers']) ? cpMain::$instance['cpusers'] : NULL)))
	{
		cpMain::$instance['cpusers']->data['user_theme'] = ((is_file("themes".DIR_SEP . cpMain::$instance['cpusers']->data['user_theme']. DIR_SEP . cpMain::$system['method_path'] . ".tpl")) ? cpMain::$instance['cpusers']->data['user_theme'] : cpMain::$instance['cpconfig']->cpconf['def_theme']);
	}

	/**
	 * Configure smarty
	 */
	cpMain::$instance['smarty']->template_dir = PATH_LOCAL . 'themes'.DIR_SEP.'default'.DIR_SEP;
	cpMain::$instance['smarty']->compile_dir = PATH_LOCAL . 'cache'.DIR_SEP;
	cpMain::$instance['smarty']->plugins_dir = array(SMARTY_DIR . 'plugins', 'resources'.DIR_SEP.'plugins');

	/**
	 * Set our CONSTANTS provided by our system
	 */
	cpMain::$instance['smarty']->assign("TEMPLATE_PATH", PATH_REMOTE);

	/**
	 * We only inject our language into the template if the users specifies.
	 * Notice the location of this as it will only work if the users requires
	 * the template class to be called upon, module authors make sure you realize
	 * that this option only injects the language into the template, it's not
	 * required for multi lingual functionality, as the language is injected
	 * automaticaly if the lang_(method).php file exists for the users. So, one
	 * important practice is to include the default language (english) with every
	 * module in case your module relies on the multi lingual template variables
	 * to be present.
	 */
	if(is_object((isset(cpMain::$instance['cplang']) ? cpMain::$instance['cplang'] : NULL)))
	{

		/**
		 * Load the language to our template class
		 */
		foreach(cpMain::$instance['cplang']->lang as $key => $value)
		{
			cpMain::$instance['smarty']->assign($key, $value);
		}
	}

	/**
	 * Build the template for the specified block
	 */
	((is_file((cpMain::$system['template_path'] !== "") ? $var = cpMain::$system['template_path'] : $var = PATH_LOCAL . "themes".DIR_SEP . cpMain::$instance['cpusers']->data['system_theme'] . DIR_SEP . cpMain::$system['method_type'] . DIR_SEP . cpMain::$system['method_path'] . ".tpl")) ? cpMain::$instance['smarty']->display($var) : ((is_file($var = PATH_LOCAL . "themes".DIR_SEP . cpMain::$instance['cpconfig']->cpconf['def_theme'] . DIR_SEP . cpMain::$system['method_type'] . DIR_SEP . cpMain::$system['method_path'] . ".tpl"))
	? cpMain::$instance['smarty']->display($var) : cpMain::cpErrorFatal("Error Loading Requested Template, the path the system was looking for (or at least 1 of the paths we checked) is: " . $var, __LINE__, __FILE__)));

}

/**
 * Require our common footer, to denitialize the system and carry
 * out end routines and procedure.
 */
require(PATH_LOCAL . "library".DIR_SEP."common".DIR_SEP."common.footer.php");
