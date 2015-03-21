<?php

namespace ModHelper;

/**
 * This file deals with some database internals.
 *
 * @package ModHelper
 * @since 1.0
 */
class Database
{
	use Singleton;

	private $db;

	/**
	 * Handler to ElkArte's database functions.
	 *
	 * @example ModHelper\Database::query('', 'SELECT * FROM smf_themes', array());
	 * @param string $name The name (or key) of the $smcFunc you are calling.
	 * @param string $arguments This is an array of all arguments passed to the method.
	 * @return mixed The $db return value or false if not found.
	 * @since 3.0
	 * @version 3.0
	 */
	public static function __callStatic($name, $arguments)
	{
		return call_user_func_array([$db, $name], $arguments);
	}

	public function init()
	{
		$db = database();
	}
}

?>