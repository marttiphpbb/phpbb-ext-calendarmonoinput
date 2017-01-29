<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2017 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\core;

use marttiphpbb\calendar\core\timespan;

class calendar_event_row
{
	/**
	 * @var timespan
	 */

	protected $timespan;

	/**
	 * @var array
	 */

	protected $free_periods;

	/**
	 * @param timespan $timespan
	 */

	public function __construct(
		timespan $timespan
	)
	{
		$this->timespan = $timespan;
	}

	/*
	*/
	public function insert_calendar_event(calendar_event )
	{

	}
}
