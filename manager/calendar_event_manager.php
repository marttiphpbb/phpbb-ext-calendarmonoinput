<?php

/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\manager;

use phpbb\auth\auth;
use phpbb\config\db as config;
use phpbb\content_visibility;
use phpbb\db\driver\factory as db;

use marttiphpbb\calendar\core\timespan;
use marttiphpbb\calendar\core\calendar_event;

class calendar_event_manager
{

	/*
	 * @var auth
	 */
	protected $auth;

	/*
	 * @var config
	 */
	protected $config;

	/*
	 * @var content_visibility
	 */
	protected $content_visibility;

	/*
	 * @var db
	 */

	protected $db;

	/*
	 * @var string
	 */

	protected $calendar_events_table;

	/*
	 * @var string
	 */

	protected $topics_table;

	/**
	* @param auth				$auth
	* @param config				$config
	* @param content_visibility	$content_visibility
	* @param db   				$db
	* @param string 			$calendar_events_table
	* @param string				$topics_table
	*/

	public function __construct(
		auth $auth,
		config $config,
		content_visibility $content_visibility,
		db $db,
		$calendar_events_table,
		$topics_table
	)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->content_visibility = $content_visibility;
		$this->db = $db;
		$this->calendar_events_table = $calendar_events_table;
		$this->topics_table = $topics_table;

	}

	/**
	* @param int $start
	* @param int $end
	* @return array
	*/
	public function find_in_timespan(timespan $timespan)
	{

		$events = $calendar_forum_colors = array();

		$forum_ids = array_keys($this->auth->acl_getf('f_read', true));

		if ($this->config['calendar_color_en'])
		{
			$sql = 'SELECT forum_calendar_color, forum_id
				FROM ' . $this->forums_table . '
				WHERE ' . $this->db->sql_in_set('forum_id', $forum_ids, false, true);
			$result = $this->db->sql_query($sql);
			while ($row = $this->db->sql_fetchrow($result))
			{
				$calendar_forum_colors[$row['forum_id']] = $row['forum_calendar_color'];
			}

			$this->db->sql_freeresult($result);
		}

		$sql = 'SELECT t.topic_id, t.forum_id, t.topic_reported, t.topic_title,
			c.calendar_start, c.calendar_end, c.calendar_event_id
			FROM ' . $this->topics_table . ' t
			LEFT JOIN ' . $this->calendar_events_table . ' c
			ON ( c.calendar_topic_id = t.topic_id )
			WHERE ( c.calendar_start <= ' . $timespan->get_end() . '
				AND c.calendar_end >= ' . $timespan->get_start() . ' )
				AND ' . $this->db->sql_in_set('t.forum_id', $forum_ids, false, true) . '
				AND ' . $this->content_visibility->get_forums_visibility_sql('topic', $forum_ids, 't.') . '
				AND t.topic_type IN (' . POST_NORMAL . ', ' . POST_STICKY . ')
			ORDER BY c.calendar_start';
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$color = ($calendar_forum_colors[$row['forum_id']]) ? $calendar_forum_colors[$row['forum_id']] : '';
			$calendar_event = new calendar_event();
			$calendar_event_timespan = new timespan($row['calendar_start'], $row['calendar_end']);
			$calendar_event->set_id($row['calendar_event_id'])
				->set_timespan($calendar_event_timespan)
				->set_topic_id($row['topic_id'])
				->set_forum_id($row['forum_id'])
				->set_topic_reported(($row['topic_reported']) ? true : false)
				->set_color[$color];
			$events[] = $calendar_event;
		}

		$this->db->sql_freeresult($result);

		return $events;
	}

	/**
	* @return calendar_event
	*/
	public function update()
	{
/*		if (!empty($this->data['page_id']))
			{

				throw new \phpbb\calendar\exception\out_of_bounds('page_id');
			}
			*
			* */

			$sql = 'INSERT INTO ' . $this->calendar_events_table .
				' ' . $this->db->sql_build_array('INSERT', $this->data);
			$this->db->sql_query($sql);
			$this->data['calendar_event_id'] = (int) $this->db->sql_nextid();
		return $this;
	}

	public function insert($calendar_event)
	{
		$timespan = $calendar_event->get_timespan();
		$data = array(
			'calendar_start'	=> $timespan->get_start(),
			'calendar_end'		=> $timespan->get_end(),
			'calendar_topic_id'	=> $calendar_event->get_topic_id(),
		);

		$sql = 'INSERT INTO ' . $this->calendar_events_table .
				' ' . $this->db->sql_build_array('INSERT', $this->data);
		$this->db->sql_query($sql);
//		$this->data['calendar_event_id'] = (int) $this->db->sql_nextid();

		return $this;
	}

}
