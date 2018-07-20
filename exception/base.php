<?php

/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\exception;

class base extends \Exception
{
	protected $message_full;
	protected $previous;

	public function __construct($message = null, $code = 0, \Exception $previous = null)
	{
		if (is_array($message))
		{
			$this->message = (string) $message[0];
		}
		else
		{
			$this->message = $message;
		}
		$this->message_full = $message;

		$this->code = $code;
		$this->previous = $previous;
	}

	public function get_message(\phpbb\user $user)
	{
		$this->add_lang($user);

		if (is_array($this->message_full))
		{
			return call_user_func_array(array($user, 'lang'), $this->message_full);
		}

		return $user->lang($this->message_full);
	}

	protected function translate_portions(\phpbb\user $user, $message_portions, $parent_message = null)
	{
		$this->add_lang($user);

		if (!is_array($message_portions))
		{
			$message_portions = array($message_portions);
		}

		foreach ($message_portions as &$message)
		{
			// Attempt to translate each portion
			$translated_message = $user->lang('EXCEPTION_' . $message);

			// Check if translating did anything
			if ($translated_message !== 'EXCEPTION_' . $message)
			{
				// It did, so replace message with the translated version
				$message = $translated_message;
			}
		}
		// Always unset a variable passed by reference in a foreach loop
		unset($message);

		if ($parent_message !== null)
		{
			// Prepend the parent message to the message portions
			array_unshift($message_portions, (string) $parent_message);

			// We return a string
			return call_user_func_array(array($user, 'lang'), $message_portions);
		}

		// We return an array
		return $message_portions;
	}

	public function add_lang(\phpbb\user $user)
	{
		static $is_loaded = false;

		// We only need to load the language file once
		if ($is_loaded)
		{
			return;
		}

		// Add our language file
		$user->add_lang_ext('marttiphpbb/calendarinput', 'exceptions');

		// So the language file is only loaded once
		$is_loaded = true;
	}

	public function __toString()
	{
		return (is_array($this->message_full)) ? var_export($this->message_full, true) : (string) $this->message_full;
	}
}
