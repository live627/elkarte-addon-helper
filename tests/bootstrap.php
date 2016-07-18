<?php

if (!defined('ELK'))
{
	DEFINE('ELK', '1');
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
}
else
	require_once(__DIR__ . '/../vendor/elkarte/elkarte/tests/travis-ci/Settings.php');

// A few files we cannot live without and will not be autoload
require_once(SOURCEDIR . '/QueryString.php');
require_once(SOURCEDIR . '/Session.php');
require_once(SOURCEDIR . '/Subs.php');
require_once(SOURCEDIR . '/Logging.php');
require_once(SOURCEDIR . '/Load.php');
require_once(SOURCEDIR . '/Security.php');
require_once(SUBSDIR . '/Cache.subs.php');

// Get the autoloader rolling
require(SOURCEDIR . '/Autoloader.class.php');
$autoloder = Elk_Autoloader::getInstance();
$autoloder->setupAutoloader(array(SOURCEDIR, SUBSDIR, CONTROLLERDIR, ADMINDIR, ADDONSDIR));
$autoloder->register(SOURCEDIR, '\\ElkArte');
