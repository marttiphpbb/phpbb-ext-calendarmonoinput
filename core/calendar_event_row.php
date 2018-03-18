<?php

/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\core;

use marttiphpbb\calendarinput\core\timespan;
use marttiphpbb\calendarinput\core\calendarinput_event;

class calendarinput_event_row
{
	/* @var timespan  */
	protected $timespan;

	/* @var array */
	protected $free_timespans = [];

	/* @var array */
	protected $calendarinput_events = [];

	/**
	 * @param timespan $timespan
	 */

	public function __construct(
		timespan $timespan
	)
	{
		$this->timespan = $timespan;
		$this->free_timespans = [$timespan];
	}

	/*
	*/
	public function insert_calendarinput_event(calendarinput_event $calendarinput_event)
	{
		$timespan = $calendarinput_event->get_timespan();

		foreach ($this->calendarinput_events as $ev)
		{
			if ($ev->overlaps($timespan))
			{
				return false;
			}
		}

		$this->calendarinput_events[] = $calendarinput_event;

		return true;
	}
}
