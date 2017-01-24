<?php

/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2016 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\core;

use phpbb\auth\auth;
use phpbb\config\db as config;
use phpbb\content_visibility;
use phpbb\db\driver\factory as db;

use marttiphpbb\calendar\core\timespan;
use marttiphpbb\calendar\core\calendar_event;

class events_container
{
	/* @var auth */
	protected $auth;

	/* @var config */
	protected $config;

	/* @var content_visibility */
	protected $content_visibility;

	/* @var db */
	protected $db;

	/* @var string */
	protected $topics_table;

	/* @var array */
	protected $events;

	/**
	* @param auth				$auth
	* @param config				$config
	* @param content_visibility	$content_visibility
	* @param db   				$db
	* @param string				$topics_table
	*/
	public function __construct(
		auth $auth,
		config $config,
		content_visibility $content_visibility,
		db $db,
		$topics_table
	)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->content_visibility = $content_visibility;
		$this->db = $db;
		$this->topics_table = $topics_table;
	}

	/**
	* @param int $start
	* @param int $end
	*/
	public function find(timespan $timespan)
	{
		$events = array();

		$forum_ids = array_keys($this->auth->acl_getf('f_read', true));

		$sql = 'SELECT t.topic_id, t.forum_id, t.topic_reported, t.topic_title,
			t.topic_calendar_start, t.topic_calendar_end
			FROM ' . $this->topics_table . ' t
			WHERE ( t.topic_calendar_start <= ' . $timespan->get_end() . '
				AND t.topic_calendar_end >= ' . $timespan->get_start() . ' )
				AND ' . $this->db->sql_in_set('t.forum_id', $forum_ids, false, true) . '
				AND ' . $this->content_visibility->get_forums_visibility_sql('topic', $forum_ids, 't.') . '
				AND t.topic_type IN (' . POST_NORMAL . ', ' . POST_STICKY . ')
			ORDER BY t.topic_calendar_start';
		$result = $this->db->sql_query($sql);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$calendar_event = new calendar_event();
			$calendar_event_timespan = new timespan($row['topic_calendar_start'], $row['topic_calendar_end']);
			$calendar_event->set_timespan($calendar_event_timespan)
				->set_topic_id($row['topic_id'])
				->set_forum_id($row['forum_id'])
				->set_topic_reported(($row['topic_reported']) ? true : false);
			$this->events[] = $calendar_event;
		}

		$this->db->sql_freeresult($result);

		return $this;
	}

	public function get_all()
	{
		return $this->events;
	}
}
