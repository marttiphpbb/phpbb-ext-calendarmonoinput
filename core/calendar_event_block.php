<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2017 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\core;

use marttiphpbb\calendar\core\timespan;
use marttiphpbb\calendar\core\calendar_event;
use marttiphpbb\calendar\core\calendar_event_row;

class calendar_event_block
{
	/**
	 * @var timespan
	 */

	protected $timespan;

	/**
	 * @var array
	 */

	protected $calendar_event_rows;

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
	 * @param calendar_event $calendar_event
	*/
	public function insert_calendar_event(calendar_event $calendar_event)
	{
		do
		{

		}
		while ($place_not_found);

	}
}
