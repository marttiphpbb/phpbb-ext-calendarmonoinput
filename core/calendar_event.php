<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\core;

use marttiphpbb\calendarinput\core\timespan;

class calendarinput_event
{

	/**
	 * @var int
	 */

	protected $id;

	/**
	 * @var timespan
	 */

	protected $timespan;

	/**
	 * @var int
	 */

	protected $topic_id;

	/**
	 * @var int
	 */

	protected $forum_id;

	/**
	 * @var boolean
	 */

	protected $topic_reported;

	/**
	 * @var string
	 */

	protected $topic_title;


	public function __construct()
	{
	}

	/*
	 * @param int 	$id
	 * @return calendarinput_event
	*/
	public function set_id($id)
	{
		$this->id = $id;
		return $this;
	}

	/*
	 * @return int
	 */
	public function get_id()
	{
		return $this->id;
	}

	/*
	 * @param timespan 	$timespan
	 * @return calendarinput_event
	*/
	public function set_timespan(timespan $timespan)
	{
		$this->timespan = $timespan;
		return $this;
	}

	/*
	 * @return timespan
	 */
	public function get_timespan()
	{
		return $this->timespan;
	}

	/*
	 * @param timespan
	 * @return bool
	 */
	public function overlaps(timespan $timespan)
	{
		return $this->timespan->overlaps($timespan);
	}

	/*
	 * @param int 	$topic_id
	 * @return calendarinput_event
	*/
	public function set_topic_id($topic_id)
	{
		$this->topic_id = $topic_id;
		return $this;
	}

	/*
	 * @return int
	 */
	public function get_topic_id()
	{
		return $this->topic_id;
	}

	/*
	 * @param int 	$forum_id
	 * @return calendarinput_event
	*/
	public function set_forum_id($forum_id)
	{
		$this->forum_id = $forum_id;
		return $this;
	}

	/*
	 * @return int
	 */
	public function get_forum_id()
	{
		return $this->forum_id;
	}

	/*
	 * @param boolean 	$topic_reported
	 * @return calendarinput_event
	*/
	public function set_topic_reported($topic_reported)
	{
		$this->topic_reported = $topic_reported;
		return $this;
	}

	/*
	 * @return boolean
	 */
	public function get_topic_reported()
	{
		return $this->topic_reported;
	}
}
