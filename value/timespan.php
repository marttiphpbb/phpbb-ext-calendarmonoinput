<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2017 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\value;

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

	public function __construct(int $start, int $end)
	{
		$this->start = $start;
		$this->end = $end;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function fits_in(timespan $timespan):bool
	{
		return $this->start >= $timespan->get_start() && $this->end <= $timespan->get_end() ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function contains(timespan $timespan):bool
	{
		return $this->start <= $timespan->get_start() && $this->end >= $timespan->get_end() ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function overlaps(timespan $timespan):bool
	{
		return $this->start <= $timespan->get_end() && $this->end >= $timespan->get_start() ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function fits_after_start(timespan $timespan):bool
	{
		return $this->start <= $timespan->get_start() ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function fits_before_end(timespan $timespan):bool
	{
		return $this->end >= $timespan->get_end() ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function starts_before(timespan $timespan):bool
	{
		return $this->start > $timespan->get_start() ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function ends_after(timespan $timespan):bool
	{
		return $this->end < $timespan->get_end() ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function is_after(timespan $timespan):bool
	{
		return $this->start > $timespan->get_end() ? true : false;
	}

	/*
	 * @param timespan
	 * @return bool
	 */

	public function is_before(timespan $timespan):bool
	{
		return $this->end < $timespan->get_start() ? true : false;
	}

	/**
	 * @param timespan
	 * @param bool
	 */

	public function has_same_start(timespan $timespan):bool
	{
		return $timespan->get_start() === $this->start ? true : false;
	}

	/**
	 * @param timespan
	 * @param bool
	 */

	public function has_same_end(timespan $timespan):bool
	{
		return $timespan->get_end() === $this->end ? true : false;
	}

	/*
	 * @return int
	 */

	public function get_duration():int
	{
		return $this->end - $this->start;
	}

	/*
	 * @return int $start
	 */
	public function get_start():int
	{
		return $this->start;
	}


	/*
	 * @return int $end
	 */
	public function get_end():int
	{
		return $this->end;
	}
}
