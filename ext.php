<?php
/**
* phpBB Extension - marttiphpbb calendarmonoinput
* @copyright (c) 2014 - 2019 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarmonoinput;

class ext extends \phpbb\extension\base
{
	/**
	 * phpBB 3.2.1+ and PHP 7.1+
	 */
	public function is_enableable()
	{
		$config = $this->container->get('config');
		return phpbb_version_compare($config['version'], '3.2.1', '>=') && version_compare(PHP_VERSION, '7.1', '>=');
	}
}
