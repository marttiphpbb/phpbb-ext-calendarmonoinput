<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2016 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\core;

class timespan
{
	/**
	 * @var int
	 */

	protected $start;

	/**
	 * @var int
	 */

	protected $end;

	/**
	 * @param int $start 	start of timespan in unix time
	 * @param int $end		end of timespan in unix time
	 * @return timespan
	 */

	public function __construct(
		$start,
		$end
	)
	{
		$this->start = $start;
		$this->end = $end;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function fits_in(timespan $timespan)
	{
		return ($this->start >= $timespan->getStart() && $this->end <= $timespan->getEnd()) ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function contains(timespan $timespan)
	{
		return ($this->start <= $timespan->getStart() && $this->end >= $timespan->getEnd()) ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function overlaps(timespan $timespan)
	{
		return ($this->start <= $timespan->getEnd() && $this->end >= $timespan->getStart()) ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function is_after(timespan $timespan)
	{
		return ($this->start > $timespan->getEnd()) ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */

	public function is_before(timespan $timespan)
	{
		return ($this->end < $timespan->getStart()) ? true : false;
	}

	/*
	 * @return int
	 */

	public function get_duration()
	{
		return $this->end - $this->start;
	}

	/*
	 * @param int $start
	 * @return timespan
	 */
	public function set_start($start)
	{
		$this->start = $start;
		return $this;
	}

	/*
	 * @return int $start
	 */

	public function get_start()
	{
		return $this->start;
	}

	/*
	 * @param int $end
	 * @return timespan
	 */
	public function set_end($end)
	{
		$this->end = $end;
		return $this;
	}

	/*
	 * @return int $start
	 */

	public function get_end()
	{
		return $this->end;
	}
}
