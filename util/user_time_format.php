<?php

/**
* @package phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2017 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\util;

use phpbb\user;

class user_time_format
{
	/* @var string */
	private $format;

	/* @var user */
	private $user;

	/**
	* @param user   $user
	*/

	public function __construct(
		user $user
	)
	{
		$this->user = $user;
		$this->format = $this->getCalculatedFormat();
	}

	private function getCalculatedFormat()
	{
		// 12h or 24h timeformat
		$dateformat = $this->user->data['user_dateformat'];
		$am_pm = '';
		$g = is_int(strpos($dateformat, 'g'));

		if ($g || is_int(strpos($dateformat, 'h')))
		{
			$hours = ($g) ? 'g' : 'h';
			$am_pm = (is_int(strpos($dateformat, 'A'))) ? ' A' : ' a';
		}
		else
		{
			$hours = (is_int(strpos($dateformat, 'G'))) ? 'G' : 'H';
		}

		return $hours . ':i' . $am_pm;		
	}

	/*
	* @return 	string
	*/
	public function getFormat()
	{
		return $this->format;
	}
}
