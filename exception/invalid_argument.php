<?php

/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\exception;

/**
* InvalidArgument exception
*/

class invalid_argument extends base
{
	/**
	* Translate this exception
	*
	* @param \phpbb\user $user
	* @return string
	* @access public
	*/
	public function get_message(\phpbb\user $user)
	{
		return $this->translate_portions($user, $this->message_full, 'EXCEPTION_INVALID_ARGUMENT');
	}
}
