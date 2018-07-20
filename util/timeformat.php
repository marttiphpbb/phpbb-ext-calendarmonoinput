<?php
/**
* @package phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\util;

use phpbb\user;

class timeformat
{

	/* @var string */
	protected $format;

	const DEFAULT_FORMAT = 'g:i a';

	/* @var array */
	protected static $format_candidates = [
		'H:i', 'H\hi', 'g:i a', 'g\hi a',
	];

	/* @var user */
	protected $user;

	/*
	 * @param user $user
	 */
	public function __construct(
		user $user
	)
	{
		$this->user = $user;

		$dateformat = $user->data['user_dateformat'];

		$this->format = timeformat::DEFAULT_FORMAT;

		foreach (timeformat::$format_candidates as $format)
		{
			if (strpos($dateformat, $format) === 0 || strpos($dateformat, ' ' . $format))
			{
				$this->format = $format;
				break;
			}
		}
	}

	/*
	 * @return string
	*/
	public function __toString()
	{
		return $this->format;
	}
}
