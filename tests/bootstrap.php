<?php
	global $db_persist, $db_server, $db_user, $db_passwd, $db_port;
	global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $db_prefix;

	DEFINE('ELK', 'SSI');
	DEFINE('CACHE_STALE', '?R11');

	// Get the forum's settings for database and file paths.
	require_once(__DIR__ . '/../vendor/elkarte/elkarte/Settings.sample.php');

	// Set our site "variable" constants
	DEFINE('BOARDDIR', $boarddir);
	DEFINE('CACHEDIR', $cachedir);
	DEFINE('EXTDIR', $extdir);
	DEFINE('LANGUAGEDIR', $boarddir . '/themes/default/languages');
	DEFINE('SOURCEDIR', $sourcedir);
	DEFINE('ADMINDIR', $sourcedir . '/admin');
	DEFINE('CONTROLLERDIR', $sourcedir . '/controllers');
	DEFINE('SUBSDIR', $sourcedir . '/subs');
	DEFINE('ADDONSDIR', $sourcedir . '/addons');

// A few files we cannot live without and will not be autoload
require_once(SOURCEDIR . '/QueryString.php');
require_once(SOURCEDIR . '/Session.php');
require_once(SOURCEDIR . '/Subs.php');
require_once(SOURCEDIR . '/Logging.php');
require_once(SOURCEDIR . '/Load.php');
require_once(SOURCEDIR . '/Security.php');
require_once(SUBSDIR . '/Cache.subs.php');

class Errors
{
	public function __call($name,$args)
	{
        global $i;

        $i = 'i';
	}

	public static function instance()
	{
		return new Errors();
	}
}

// Get the autoloader rolling
require(SOURCEDIR . '/Autoloader.class.php');
$autoloder = Elk_Autoloader::getInstance();
$autoloder->setupAutoloader(array(SOURCEDIR, SUBSDIR, CONTROLLERDIR, ADMINDIR, ADDONSDIR));
$autoloder->register(SOURCEDIR, '\\ElkArte');

		$settings['theme_dir'] = $settings['default_theme_dir'] = BOARDDIR . '/themes/default';
		$settings['theme_url'] = $settings['default_theme_url'] = $boardurl . '/themes/default';

$txt=['theme_language_error'=> 'Unable to load the \'%1$s\' language file.'];
$modSettings['enableErrorLogging']=0;
$db_server = '127.0.0.1';
file_put_contents(BOARDDIR . '/db_last_error.txt', time(), LOCK_EX);
 loadDatabase();

function template_mock_edit()
{
}
