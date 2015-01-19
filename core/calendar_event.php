<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\core;

use marttiphpbb\calendar\core\timespan;

class calendar_event
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

	/**
	 * @var string
	 */

	protected $color;

	public function __construct(
	)
	{
	}

	/*
	 * @param int 	$id
	 * @return calendar_event
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
	 * @return calendar_event
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
	 * @param int 	$topic_id
	 * @return calendar_event
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
	 * @return calendar_event
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
	 * @return calendar_event
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

	/*
	 * @param string 	$color
	 * @return calendar_event
	*/
	public function set_color($color)
	{
		$this->color = $color;
		return $this;
	}

	/*
	 * @return string
	 */
	public function get_color()
	{
		return $this->color;
	}

}
