<<<<<<< HEAD
<<<<<<< HEAD
<?php

class calendar
{
	private static $post_data = array();

	private static $days_num = 112;
	private static $tables_num = 4;
	private static $table_days_num = 28;
	private static $nav_num = 28;

	private static $start;
	private static $end;
	private static $today_jd;
	private static $today;
	private static $show_today = 1;

	private static $start_week = 0;
	private static $first_monday = 0;

	private static $text_char_per_col = 4.7;
	private static $text_limit_one_col = 2;
	private static $text_min_span_ratio = 0.25;
	private static $text_min_span = 7;

	private static $max_evt_rows = 20;

	private static $props = array(
		'type'				=> self::DAYS_TYPE,
		'min_evt_rows'		=> 0,
		'max_evt_rows'		=> 30
		);

	private static $row_props = array();
	private static $props_keymap = array(
		'class'				=> 0,
		'today_class'		=> 0,
		'text'				=> 0,
		'title'				=> 0,
		'onclick'			=> 0,
		'colspan'			=> 0,
		'rowspan'			=> 0,
		'type'				=> 0,
		'min_evt_rows'		=> 0,
		'max_evt_rows'		=> 0,
		);

	//
	private static $row = -1;
	private static $col = 0;

	private static $ch_row = 'r';
	private static $ch_col = 'c';
	private static $ch_table = 't';

	// classes for every col
	private static $col_class_ary = array();

	// array that keep track of grouped calendar evt properties
	private static $msvr_evt_ary = array();

	// arrays to javascript.
	private static $mouseover_evt_id_ary = array();
	private static $evt_topic_id_ary = array();

	// types of rows

	const DAYS_TYPE = 10;
	const MONTH_DAYS_TYPE = 20;
	const WEEK_DAYS_TYPE = 30;
	const MOONISO_TYPE = 40;
	const MONTH_YEAR_TYPE = 50;
	const EVENTS_TYPE = 60;
	const BIRTHDAYS_TYPE = 70;

	//
	private static $row_type_ary = array();
	private static $today_class_ary = array();
	private static $month_year_class;

	private static $cells = array();

	// moon
	private static $moon_phase_ary = array();
	private static $moon_unix_ary = array();
	private static $mooniso_ary = array();
	private static $moon_class_ary = array('m0', 'm1', 'm2', 'm3');
	private static $moon_text_ary = array(
		'CALENDAR_NEW_MOON',
		'CALENDAR_FIRST_QUARTER_MOON',
		'CALENDAR_FULL_MOON',
		'CALENDAR_THIRD_QUARTER_MOON');
	private static $moon_timeformat = 'H:i';

	// events
	private static $event_ary = array();
	private static $cal_events = array();
	private static $prefix_list = array();

	// birthdays
	private static $birthdays = array();

	// topic color mod
	private static $topic_color;

	// topic filters mod
	private static $topic_filters;
	private static $topic_locations;

	//lang
	private static $week_days_names = array();
	private static $month_names = array();
	private static $month_names_short = array();

/**
**
**/

	public static function init($today_jd, $post_data)
	{
		global $db, $template, $user, $phpEx, $config;

		foreach ($post_data as $key => $val)
		{
			if (property_exists(calendar, $key))
			{
				self::$$key = $val;
			}
		}

		if (!isset(self::$start_jd))
		{
			return false;
		}

		self::$post_data = $post_data;
		self::$today_jd = $today_jd;
		self::$table_days_num = (int) floor(self::$days_num / self::$tables_num);
		self::$days_num = self::$table_days_num * self::$tables_num;
		self::$start_week = self::$start_jd % 7;
		self::$first_monday = 7 - (self::$start_week);
		self::$today = self::$today_jd - self::$start_jd;
		self::$end_jd = self::$start_jd + self::$days_num;
		self::$text_min_span = floor(self::$table_days_num * self::$text_min_span_ratio);
		self::$topic_color = new topic_color();
		self::$topic_locations = new topic_locations();
		self::$topic_filters = new topic_filters($user->lang['ALL_TOPICS']);
		self::$week_days_names = array(
			$user->lang['SUN'],
			$user->lang['MON'],
			$user->lang['TUE'],
			$user->lang['WED'],
			$user->lang['THU'],
			$user->lang['FRI'],
			$user->lang['SAT'],
			);
		self::$month_names = array(
			$user->lang['JANUARY'],
			$user->lang['FEBRUARY'],
			$user->lang['MARCH'],
			$user->lang['APRIL'],
			$user->lang['MAY'],
			$user->lang['JUNE'],
			$user->lang['JULY'],
			$user->lang['AUGUST'],
			$user->lang['SEPTEMBER'],
			$user->lang['OCTOBER'],
			$user->lang['NOVEMBER'],
			$user->lang['DECEMBER']
			);

	}

	public static function set_cols($class, $today_class)
	{
		if ($class)
		{
			if ($class instanceof calendar_pattern)
			{
				if ($class->ary[0] instanceof calendar_pattern)
				{
					$countdown = 0;

					for ($col = 0; $col < self::$days_num; $col++)
					{
						if (!$countdown)
						{
							$unix = ((self::$start_jd + $col) - 2440588) * 86400;
							$month_num_days = (int) gmdate('t', $unix);
							$month_day = (int) gmdate('j', $unix);
							$countdown = $month_num_days - $month_day + 1;
							$month = (int) gmdate('n', $unix) - 1;
							$year = (int) gmdate('Y', $unix);
							$val = $class->ary[($month + ($year *12)) % $class->length];
						}

						self::$col_class_ary[$col] = $val->ary[abs((self::$start_week + $col) % $val->length)];

						$countdown--;
					}
				}
				else
				{
					for ($col = 0; $col < self::$days_num; $col++)
					{
						self::$col_class_ary[$col] = $class->ary[abs((self::$start_week + $col) % $class->length)];
					}
				}
			}
			else
			{
				for ($col = 0; $col < self::$days_num; $col++)
				{
					self::$col_class_ary[$col] = $class;
				}
			}
		}

		if (isset($today_class) && self::$today >= 0 && self::$today < self::$days_num)
		{
			self::$col_class_ary[self::$today] .= ' ' . $today_class;
		}
	}

	public static function insert_month_year_row($class = null)
	{
		if (!($class instanceof calendar_pattern))
		{
			return;
		}
		self::$row++;
		self::$row_type_ary[self::$row] = self::MONTH_YEAR_TYPE;
		self::$month_year_class = $class;
	}

	public static function insert_month_days_row($today_class = null)
	{
		self::$row++;
		self::$row_type_ary[self::$row] = self::MONTH_DAYS_TYPE;
		self::$today_class_ary[self::$row] = $today_class;
	}

	public static function insert_week_days_row($today_class = null)
	{
		self::$row++;
		self::$row_type_ary[self::$row] = self::WEEK_DAYS_TYPE;
		self::$today_class_ary[self::$row] = $today_class;
	}

	public static function insert_mooniso_row($today_class = null)
	{
		global $user;

		self::$row++;
		self::$row_type_ary[self::$row] = self::MOONISO_TYPE;
		self::$today_class_ary[self::$row] = $today_class;

		self::find_moonphases();

		// copy format from user_dateformat for moon_timeformat

		$dateformat = $user->data['user_dateformat'];
		$am_pm = '';
		$g_bool = is_int(strpos($dateformat, 'g'));

		if ($g_bool || is_int(strpos($dateformat, 'h')))
		{
			$hours = ($g_bool) ? 'g' : 'h';
			$am_pm = (is_int(strpos($dateformat, 'A'))) ? ' A' : ' a';
		}
		else
		{
			$hours = (is_int(strpos($dateformat, 'G'))) ? 'G' : 'H';
		}

		self::$moon_timeformat = $hours . ':i' . $am_pm;

		for ($col = self::$first_monday; $col < self::$days_num; $col += 7)
		{
			$unix = ((self::$start_jd + $col) - 2440588) * 86400;
			self::$mooniso_ary[$col] = array(
				'class'	=> 'isoweek',
				'text'	=> gmdate('W', $unix),
				);
		}

		foreach(self::$moon_unix_ary as $key => $unix)
		{
			$local_time = $unix + ($user->data['user_timezone'] * 3600) + ($user->data['user_dst'] * 3600);
			$jd = floor(($local_time / 86400) + 2440588);
			$col = $jd - self::$start_jd;

			if ($col >= 0 && $col <= self::$days_num)
			{
				self::$mooniso_ary[$col] = array(
					'class' => self::$moon_class_ary[self::$moon_phase_ary[$key]],
					'text'	=> '&nbsp;',
					'title'	=> $user->lang[self::$moon_text_ary[self::$moon_phase_ary[$key]]] . ' @ ' .gmdate(self::$moon_timeformat, $local_time),
					);
			}
		}
	}

	public static function insert_days_row($today_class = null)
	{
		self::$row++;
		self::$row_type_ary[self::$row] = self::DAYS_TYPE;
		self::$today_class_ary[self::$row] = $today_class;
	}

	public static function insert_events_rows($today_class = null)
	{
		global $db, $template, $config, $auth, $phpEx;

		$sql_pre = ' AND t.topic_approved = 1';
		$sql_pre .= ' AND t.topic_type IN (' . POST_NORMAL . ', ' . POST_STICKY . ')';

		self::$topic_filters->init_calendar_mode(self::$start_jd, self::$end_jd, $sql_pre, self::$post_data);
		self::$topic_filters->add_forums();
		$prefix_names = (self::$topic_filters->add_prefix());
		self::$topic_filters->add_location(self::$topic_locations);

		self::$topic_filters->gen_sql();
		self::$topic_filters->gen_u();

		self::$topic_filters->set_sql_suf('');
		if ($config['show_topic_filters']) // quick fix by mysql memory shortage
		{
			self::$topic_filters->to_template();
		}

	// print view link

		$print_view = '&amp;start_jd=' . self::$start_jd;
		$print_view .= '&amp;show_today=0';
		$print_view .= self::$topic_filters->get_u();

		$template->assign_vars(array(
			'U_PRINT_TOPIC'			=> append_sid($phpbb_root_path . 'calendar.'  . $phpEx, 'view=print' . $print_view),
			));

	// get calendar events (archive included)

		$topic_list = $cal_events = array();

		$sql = 'SELECT c.*, t.forum_id, t.topic_title,
					t.topic_approved, t.topic_reported,
					t.topic_bg_color
				FROM ' . TOPICS_TABLE . ' t
				LEFT JOIN ' . CALENDAR_PERIODS_TABLE . ' c ON (t.topic_id = c.topic_id)
				WHERE ' . self::$topic_filters->get_sql_base() .
					self::$topic_filters->get_sql_pre() .
					self::$topic_filters->get_sql();

		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$cal_events[] = $row;
			$topic_list[$row['topic_id']] = $row['topic_id'];
		}
		$db->sql_freeresult($result);

	// get topic title prefixes

		$sql = 'SELECT t.topic_id, p.prefix_name
			FROM ' . TTP_PREFIXES_TABLE . ' p
			LEFT JOIN ' . TOPICS_TABLE . ' t ON (t.topic_prefix_id = p.prefix_id)
			WHERE ' . $db->sql_in_set('t.topic_id', $topic_list, false, true);
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			self::$prefix_list[$row['topic_id']] = $row['prefix_name'];
		}
		$db->sql_freeresult($result);

	// place events

		if (!sizeof($cal_events))
		{
			return;
		}

		self::$row++;
		$last_e_row = self::$row;
		$max_row = self::$row + self::$max_evt_rows;

		foreach ($cal_events as $cal_event)
		{
			if (!$auth->acl_get('m_approve', $cal_event['forum_id']) && !$cal_event['topic_approved'])
			{
				continue;
			}

			$event_start = $cal_event['start_jd'] - self::$start_jd;
			$event_end = $cal_event['end_jd'] - self::$start_jd;

			$event_start = ($event_start < 0) ? 0 : $event_start;
			$event_end = ($event_end >= self::$days_num) ? self::$days_num - 1 : $event_end;

			$place_found = false;

			for ($e_row = self::$row; $e_row < $max_row; $e_row++)
			{
				self::$row_type_ary[$e_row] = self::EVENTS_TYPE;
				self::$today_class_ary[$e_row] = $today_class;
				$last_e_row = ($last_e_row < $e_row) ? $e_row : $last_e_row;

				if (empty(self::$event_ary[$e_row]))
				{
					$place_found = true;
				}
				else
				{
					$overlap = false;

					foreach (self::$event_ary[$e_row] as $col => $placed_evt)
					{

						if (!($event_start > $placed_evt['end'] || $event_end < $col))
						{
							$overlap = true;
							break;
						}
					}

					if (!$overlap)
					{
						$place_found = true;
					}
				}

				if ($place_found)
				{
					while (true)
					{
						$next_table = floor($event_start / self::$table_days_num) + 1;
						$next_start = $next_table * self::$table_days_num;

						if ($event_end >= $next_start)
						{
							self::$event_ary[$e_row][$event_start] = array_merge($cal_event, array(
								'end'		=> $next_start - 1,
								'colspan'	=> $next_start - $event_start,
								));
							$event_start = $next_start;
						}
						else
						{
							self::$event_ary[$e_row][$event_start] = array_merge($cal_event, array(
								'end'		=> $event_end,
								'colspan'	=> $event_end - $event_start + 1,
								));
							break;
						}
					}
					break;
				}
			}
		}

		self::$row = $last_e_row + 1;
		self::$row_type_ary[self::$row] = self::DAYS_TYPE;
		self::$today_class_ary[self::$row] = $today_class;
	}

	public static function insert_birthday_rows($today_class = null)
	{
		global $db, $config, $template;

		if (self::$post_data['display_birthdays'])
		{
			self::$row++;
			$first_b_row = self::$row;
			$last_b_row = self::$row;

			$greg = cal_from_jd(self::$start_jd, CAL_GREGORIAN);
			$start_day = $greg['day'];
			$start_month = $greg['month'];
			$start_year = $greg['year'];
			$greg = cal_from_jd(self::$end_jd, CAL_GREGORIAN);
			$end_day = $greg['day'];
			$end_month = $greg['month'];
			$end_year = $greg['year'];

			if ($start_month == $end_month)
			{
				$sql_where = ' user_bday_day >= ' .  $start_day . ' AND user_bday_day <= ' . $end_day;
			}
			else
			{
				$days_in_start_month = cal_days_in_month(CAL_GREGORIAN, $start_month, $start_year);
				$sql_where = ' (user_bday_day >= ' . $start_day . ' AND user_bday_day <= ' . $days_in_start_month . ' AND user_bday_month = ' . $start_month . ')';

				$next_month = $start_month + 1;
				$next_month = ($next_month == 13) ? 1 : $next_month;

				while ($next_month != $end_month)
				{
					$sql_where .= ' OR user_bday_month = ' . $next_month;

					$next_month++;
					$next_month = ($next_month == 13) ? 1 : $next_month;
				}

				$sql_where .= ' OR (user_bday_day <= ' . $end_day . ' AND user_bday_month = ' .  $end_month . ')';
			}

			$sql = 'SELECT user_id, username, user_bday_day, user_bday_month, user_bday_year
					FROM ' . USERS_TABLE . '
					WHERE ' . $sql_where;
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$jd1 = gregoriantojd($row['user_bday_month'], $row['user_bday_day'], $start_year);
				self::add_birthday($row['user_id'], $row['username'], $start_year - $row['user_bday_year'], $jd1, $last_b_row, $today_class);

				if ($start_year != $end_year)
				{
					$jd2 = gregoriantojd($row['user_bday_month'], $row['user_bday_day'], $end_year);
					self::add_birthday($row['user_id'], $row['username'], $end_year - $row['user_bday_year'], $jd2, $last_b_row, $today_class);
				}
			}
			$db->sql_freeresult($result);

			if (!sizeof(self::$birthdays))
			{
				self::$row--;
				return;
			}

			self::$row = $last_b_row + 1;
			self::$row_type_ary[self::$row] = self::DAYS_TYPE;
			self::$today_class_ary[self::$row] = $today_class;

		// shuffle birthdays

			if ($last_b_row != $first_b_row)
			{
				for ($row = $first_b_row; $row <= $last_b_row; $row++)
				{
					foreach (self::$birthdays[$row] as $col => $birthday)
					{
						$rand = mt_rand($first_b_row, $last_b_row);
						if ($rand == $row || isset(self::$birthdays[$rand][$col]) || isset(self::$birthdays[$row][$col]['shuffled']))
						{
							continue;
						}
						self::$birthdays[$rand][$col] = $birthday;
						self::$birthdays[$rand][$col]['shuffled'] = true;
						unset(self::$birthdays[$row][$col]);
					}
				}
			}
		}

		$template->assign_vars(array(
			'S_BIRTHDAYS'	=> true,
			));
	}

	/**
	**
	**/

	private static function add_birthday($user_id, $username, $age, $jd, &$last_b_row, $today_class)
	{
		if ($jd < self::$start_jd && $jd > self::$end_jd)
		{
			return;
		}

		for ($row = self::$row; $row < (self::$row + 10); $row++)
		{
			self::$row_type_ary[$row] = self::BIRTHDAYS_TYPE;
			self::$today_class_ary[$row] = $today_class;
			$last_b_row = ($last_b_row < $row) ? $row : $last_b_row;
			$col = $jd - self::$start_jd;

			if (!isset(self::$birthdays[$row][$col]))
			{
				self::$birthdays[$row][$col] = array(
					'user_id'	=> $user_id,
					'username'	=> $username,
					'age'		=> $age,
					);
				break;
			}
		}
	}

	/**
	**
	**/

	public static function write_to_template()
	{
		global $template, $phpbb_root_path, $phpEx;

		for ($table_index = 0; $table_index < self::$tables_num; $table_index++)
		{
			self::table_to_template($table_index);
		}

		$hidden_calendar_fields = '<input type="hidden" value="' . self::$start_jd . '" name="start_jd" />';

		$template->assign_vars(array(
			'SCRIPT_MSVR_EVT_ID_ARY'		=> '[' . implode('], [', self::$mouseover_evt_id_ary) . ']',
			'S_POST_ACTION'					=> append_sid($phpbb_root_path . 'calendar.' . $phpEx, 'start_jd=' . calendar::$start_jd),
			'S_HIDDEN_CALENDAR_FIELDS'		=> $hidden_calendar_fields,
			'S_DISPLAY_BIRTHDAYS'			=> self::$post_data['display_birthdays'],

			'U_FORUM'						=> (self::$post_data['view']) ? generate_board_url() . '/' : $phpbb_root_path,
			));

		$mid_nav_jd = (self::$post_data['mid_jd']) ? self::$post_data['mid_jd'] : floor((self::$start_jd + self::$end_jd) / 2);
		$nav_start_jd = $mid_nav_jd - 400;
		$unix = ($nav_start_jd - 2440588) * 86400;
		$nav_month = (int) gmdate('n', $unix) - 1;
		$year = (int) gmdate('Y', $unix);
		$nav_sel = 0;

		for ($i = 0; $i < self::$nav_num; $i++)
		{

			$month = ($nav_month + $i) % 12;
			$year = (!$i || $month) ? $year : $year += 1;
			$m_jd = cal_to_jd(CAL_GREGORIAN, ($month + 1), 1, $year);

			$class = ' class="';
			$class .= self::$month_year_class->ary[($month + ($year * 12)) % self::$month_year_class->length];
			$class .= '"';

			$template->assign_block_vars('nav', array(
				'PARAM'		=> $class,
				'TEXT'		=> substr(self::$month_names[$month], 0, 3),
				));

			for ($a = 0; $a < 4; $a++)
			{
				unset($class);

				switch ($nav_sel)
				{
					case 0:

						if (($m_jd + ($a * 7)) > self::$start_jd)
						{
							$class = 'view-left';
							$nav_sel = 1;
						}

						break;

					case 1:

						if (($m_jd + (($a + 1) * 7)) > self::$end_jd)
						{
							$class = 'view-right';
							$nav_sel = 2;
						}
						else
						{
							$class = 'view-mid';
						}

						break;
				}

				$mid_jd = $m_jd + ($a * 7);
				$onclick = ' onclick="navigate_to(' . $mid_jd . ');"';

				$n = $i * 4 + $a;

				$class = ($class) ? ' class="' . $class . '"' : '';

				$template->assign_block_vars('topnav_a', array(
					'PARAM'		=> $class . ' id="na' . $n . '"' . $onclick,
					));
				$template->assign_block_vars('topnav_b', array(
					'PARAM'		=> $class . ' id="nb' . $n . '"' . $onclick,
					));
			}
		}

		add_form_key('postform');
	}

	private static function table_to_template($table_index)
	{
		global $template, $phpbb_root_path, $phpEx, $user, $auth;

		$template->assign_block_vars('table', array(
			'PARAM'		=> ''
			));

		$col_start = $table_index * self::$table_days_num;
		$col_end = $col_start + self::$table_days_num;

		for ($col = $col_start; $col < $col_end; $col++)
		{
			$template->assign_block_vars('table.col', array(
				'PARAM'		=> (isset(self::$col_class_ary[$col]) ? ' class="' . self::$col_class_ary[$col] . '"' : '')
				));
		}

		foreach (self::$row_type_ary as $row => $type)
		{
			$template->assign_block_vars('table.row', array(
				'PARAM'		=> ''
				));

			$today_class = self::$today_class_ary[$row];

			switch ($type)
			{
				case self::DAYS_TYPE:

					for ($col = $col_start; $col < $col_end; $col++)
					{
						$class = ($today_class && $col == self::$today) ? ' class="' . $today_class . '"' : '';
						$template->assign_block_vars('table.row.cell', array(
							'TEXT'		=> ' ',
							'PARAM'		=> $class,
							));
					}

					break;

				case self::MONTH_DAYS_TYPE:

					for ($col = $col_start; $col < $col_end; $col++)
					{
						$unix = ((self::$start_jd + $col) - 2440588) * 86400;
						$class = ($today_class && $col == self::$today) ? ' class="' . $today_class . '"' : '';
						$template->assign_block_vars('table.row.cell', array(
							'TEXT'		=> gmdate('j', $unix),
							'PARAM'		=> $class,
							));
					}

					break;

				case self::WEEK_DAYS_TYPE:

					for ($col = $col_start; $col < $col_end; $col++)
					{
						$unix = ((self::$start_jd + $col) - 2440588) * 86400;
						$class = ($today_class && $col == self::$today) ? ' class="' . $today_class . '"' : '';
						$template->assign_block_vars('table.row.cell', array(
							'TEXT'		=> self::$week_days_names[gmdate('w', $unix)],
							'PARAM'		=> $class,
							));
					}

					break;

				case self::BIRTHDAYS_TYPE:

					for ($col = $col_start; $col < $col_end; $col++)
					{
						if (isset(self::$birthdays[$row][$col]))
						{
							$birthday = self::$birthdays[$row][$col];

							$text = substr($birthday['username'], 0, 2) . '..';
							$title = $user->lang['BIRTHDAY_OF'] . ' ' . $birthday['username'] . (($birthday['age'] < 1000) ? '(' . $birthday['age'] . ')' : '');
							$title = ' title="' . $title . '"';
							$onclick = append_sid($phpbb_root_path . 'memberlist.'  . $phpEx, 'mode=viewprofile&u=' . $birthday['user_id']);
							$onclick = ' onclick="window.location.href=\'' . $onclick . '\'"';

							$param = ' class="birthday" style="cursor:pointer;"' . $title . $onclick;

							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> $text,
								'PARAM'		=> $param,
								));
						}
						else
						{
							$class = ($today_class && $col == self::$today) ? ' class="' . $today_class . '"' : '';
							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> ' ',
								'PARAM'		=> $class,
								));
						}
					}

					break;

				case self::MOONISO_TYPE:

					for ($col = $col_start; $col < $col_end; $col++)
					{
						if (isset(self::$mooniso_ary[$col]))
						{
							$class = ' class="' . self::$mooniso_ary[$col]['class'];
							$class .= ($today_class && $col == self::$today) ? ' ' . $today_class : '';
							$class .= '"';
							$title = (self::$mooniso_ary[$col]['title']) ? ' title="' . self::$mooniso_ary[$col]['title'] . '"' : '';
							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> self::$mooniso_ary[$col]['text'],
								'PARAM'		=> $class . $title,
								));
						}
						else
						{
							$class = ($col == self::$today) ? ' class="' . self::$today_class_ary[$row] . '"' : '';
							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> ' ',
								'PARAM'		=> $class,
								));
						}
					}

					break;

				case self::MONTH_YEAR_TYPE:

					$col = $col_start;

					do
					{
						$jd = $col + self::$start_jd;
						$unix = ($jd - 2440588) * 86400;
						$month_num_days = (int) gmdate('t', $unix);
						$month_day = (int) gmdate('j', $unix);
						$year = gmdate('Y', $unix);
						$month = (int) gmdate('n', $unix) - 1;
						$colspan = $month_num_days - $month_day + 1;
						$last_col = $colspan + $col - 1;

						if ($last_col >= $col_end)
						{
							$colspan = $col_end - $col;
						}

						$class = ' class="';
						$class .= self::$month_year_class->ary[($month + ($year * 12)) % self::$month_year_class->length];
						$class .= '"';
						$text = ($colspan > self::$text_min_span) ? self::$month_names[$month] . ' ' . $year : '&nbsp;';

						$template->assign_block_vars('table.row.cell', array(
							'TEXT'		=> $text,
							'PARAM'		=> $class . ' colspan="' . $colspan . '"',
							));

						$col += $colspan;
					}
					while ($col < $col_end);

					break;

				case self::EVENTS_TYPE:

					$col = $col_start;

					do
					{
						if (isset(self::$event_ary[$row][$col]))
						{
							$evt = self::$event_ary[$row][$col];
							$id = self::$ch_row . $row . self::$ch_col . $col;

							$topic_id = $evt['topic_id'];
							$forum_id = $evt['forum_id'];

							if (isset(self::$evt_topic_id_ary[$topic_id]))
							{
								$evt_id = self::$evt_topic_id_ary[$topic_id];
								self::$mouseover_evt_id_ary[$evt_id] .= ', \'' . $id . '\'';
							}
							else
							{
								$evt_id = sizeof(self::$evt_topic_id_ary);
								self::$evt_topic_id_ary[$topic_id] = $evt_id;
								self::$mouseover_evt_id_ary[$evt_id] = '\'' . $id . '\'';
							}

							$mouseover = ' onmouseover="mouseover_evt(this);"'; //' . $evt_id . ');"';
							$mouseout = ' onmouseout="mouseout_evt(this);"'; //  . $evt_id . ');"';

							$url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $forum_id . '&amp;t=' .$topic_id);
							$onclick = ' onclick="window.location.href=\'' . $url . '\'"';

							$prefix = (self::$prefix_list[$topic_id]) ? self::$prefix_list[$topic_id] : '';

							if ($evt['topic_approved'] || self::$post_data['view'] == 'print')
							{
								$unapproved = '';
							}
							else
							{
								$unapproved = '<a href="';
								$unapproved .= append_sid($phpbb_root_path . 'mcp.' . $phpEx, 'i=queue&amp;mode=approve_details&amp;t=' . $topic_id);
								$unapproved .= '">';
								$unapproved .= $user->img('icon_topic_unapproved', 'TOPIC_UNAPPROVED');
								$unapproved .= '</a>';
							}

							if ($evt['topic_reported'] && $auth->acl_get('m_report', $forum_id) && self::$post_data['view'] != 'print')
							{
								$reported = '<a href="';
								$reported .= append_sid($phpbb_root_path . 'mcp.' . $phpEx, 'i=reports&amp;mode=reports&amp;f=' . $forum_id . '&amp;t=' . $topic_id);
								$reported .= '">';
								$reported .= $user->img('icon_topic_reported', 'POST_REPORTED');
								$reported .= '</a>';
							}
							else
							{
								$reported = '';
							}

							$text = $prefix . $evt['topic_title'];

							$colspan = $evt['colspan'];

							if (self::$post_data['view'] == 'print')
							{
								$color_style = '';
							}
							else
							{
								$color_style = ' style="background-color: ';
								$color_style .= self::$topic_color->obtain_color($evt['topic_bg_color'], 'topic', false, ($row % 2)) . ';"';
							}

							$max_text_lenght = floor(self::$text_char_per_col * $colspan);

							if (strlen($text) > $max_text_lenght)
							{
								$title = ' title="' . $text . '"';

								$len = ($colspan == 1) ? self::$text_limit_one_col : $max_text_lenght;
								$text = substr($text, 0, $len) . '..';
							}
							else
							{
								$title = '';
							}

							$colspan = ($colspan > 1) ? ' colspan="' . $colspan . '"' : '';
							$class = ' class="evt"';

							$param = ' id="' . $id . '"' . $class . $colspan . $onclick . $title . $color_style . $mouseover . $mouseout;
							$text = '<a href="' . $url . '">' . $text . '</a>' . $unapproved . $reported;

							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> $text,
								'PARAM'		=> $param,
								));
							$col += $evt['colspan'];
						}
						else
						{
							$class = ($today_class && $col == self::$today) ? ' class="' . $today_class . '"' : '';
							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> ' ',
								'PARAM'		=> $class,
								));
							$col++;
						}
					}
					while($col < $col_end);

				break;
			}
		}
	}

	/**
	** Find jd_time of phases of the moon between two dates.
	** phases: 0 = new moon, 1 = first quarter, 2 = full moon, 3 = third quarter
	** output is self::$moon_phase_ary and self::$moon_unix_ary in local time
	**/
	private static function find_moonphases()
	{
		global $user;

		$synodic_month_length = 29.53058868;
		$deg = pi() / 180;
		$max_moon_cycles = 100;

		$day_floor = self::$start_jd;	//

		if ($day_floor >= 2299161)
		{
			$alpha = floor(($day_floor - 1867216.25) / 36524.25);
			$day_floor = $day_floor + 1 + $alpha - floor($alpha / 4);
		}

		$day_floor += 1524;

		$year = floor(($day_floor - 122.1) / 365.25);
		$month = floor(($day_floor - floor(365.25 * $year)) / 30.6001);

		$month = ($month < 14) ? $month - 1 : $month - 13;
		$year = ($month > 2) ? $year - 4716 : $year - 4715;

		$syn_month = floor(($year + (($month - 1) * (1 / 12)) - 1900) * 12.3685) - 2;  // before :: -2

		for ($max_loop = $syn_month + $max_moon_cycles; $syn_month < $max_loop; $syn_month += 0.25)
		{
			$phase = $syn_month - floor($syn_month);

			$jc_time = $syn_month / 1236.85;		// time in Julian centuries from 1900 January 0.5
			$jc_time2 = $jc_time * $jc_time;		// square for frequent use
			$jc_time3 = $jc_time2 * $jc_time;		// cube for frequent use

			// mean time of phase
			$phase_time = 2415020.75933
				+ $synodic_month_length * $syn_month
				+ 0.0001178 * $jc_time2
				- 0.000000155 * $jc_time3
				+ 0.00033 * sin((166.56 + 132.87 * $jc_time - 0.009173 * $jc_time2) * $deg);

			// Sun's mean anomaly
			$sun_anom = 359.2242
				+ 29.10535608 * $syn_month
				- 0.0000333 * $jc_time2
				- 0.00000347 * $jc_time3;

			// Moon's mean anomaly
			$moon_anom = 306.0253
				+ 385.81691806 * $syn_month
				+ 0.0107306 * $jc_time2
				+ 0.00001236 * $jc_time3;

			// Moon's argument of latitude
			$moon_lat = 21.2964
				+ 390.67050646 * $syn_month
				- 0.0016528 * $jc_time2
				- 0.00000239 * $jc_time3;

			if (($phase < 0.01) || (abs($phase - 0.5) < 0.01))
			{
				// Corrections for New and Full Moon.
				$phase_time += (0.1734 - 0.000393 * $jc_time) * sin($sun_anom * $deg)
					+ 0.0021 * sin(2 * $sun_anom * $deg)
					- 0.4068 * sin($moon_anom * $deg)
					+ 0.0161 * sin(2 * $moon_anom * $deg)
					- 0.0004 * sin(3 * $moon_anom * $deg)
					+ 0.0104 * sin(2 * $moon_lat * $deg)
					- 0.0051 * sin(($sun_anom + $moon_anom) * $deg)
					- 0.0074 * sin(($sun_anom - $moon_anom) * $deg)
					+ 0.0004 * sin((2 * $moon_lat + $sun_anom) * $deg)
					- 0.0004 * sin((2 * $moon_lat - $sun_anom) * $deg)
					- 0.0006 * sin((2 * $moon_lat + $moon_anom) * $deg)
					+ 0.0010 * sin((2 * $moon_lat - $moon_anom) * $deg)
					+ 0.0005 * sin(($sun_anom + 2 * $moon_anom) * $deg);
			}
			else if ((abs($phase - 0.25) < 0.01 || (abs($phase - 0.75) < 0.01)))
			{
				$phase_time += (0.1721 - 0.0004 * $jc_time) * sin($sun_anom * $deg)
					+ 0.0021 * sin(2 * $sun_anom * $deg)
					- 0.6280 * sin($moon_anom * $deg)
					+ 0.0089 * sin(2 * $moon_anom * $deg)
					- 0.0004 * sin(3 * $moon_anom * $deg)
					+ 0.0079 * sin(2 * $moon_lat * $deg)
					- 0.0119 * sin(($sun_anom + $moon_anom) * $deg)
					- 0.0047 * sin(($sun_anom - $moon_anom) * $deg)
					+ 0.0003 * sin((2 * $moon_lat + $sun_anom) * $deg)
					- 0.0004 * sin((2 * $moon_lat - $sun_anom) * $deg)
					- 0.0006 * sin((2 * $moon_lat + $moon_anom) * $deg)
					+ 0.0021 * sin((2 * $moon_lat - $moon_anom) * $deg)
					+ 0.0003 * sin(($sun_anom + 2 * $moon_anom) * $deg)
					+ 0.0004 * sin(($sun_anom - 2 * $moon_anom) * $deg)
					- 0.0003 * sin((2 * $sun_anom + $moon_anom) * $deg);

				if ($phase < 0.5)
				{
					// First quarter correction.
					$phase_time += 0.0028 - (0.0004 * cos($sun_anom * $deg)) + (0.0003 * cos($moon_anom * $deg));
				}
				else
				{
					// Last quarter correction.
					$phase_time += -0.0028 + (0.0004 * cos($sun_anom * $deg)) - (0.0003 * cos($moon_anom * $deg));
				}
			}

			if ($phase_time >= self::$end_jd)
			{
				return true;
			}

			if ($phase_time >= self::$start_jd)
			{
				self::$moon_phase_ary[] = (int) floor(4 * $phase);
				self::$moon_unix_ary[] = ($phase_time - 2440587.5) * 86400;
			}
		}
	}
}

?>
=======
=======
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
<?php

class calendar
{
	private static $post_data = array();

	private static $days_num = 112;
	private static $tables_num = 4;
	private static $table_days_num = 28;
	private static $nav_num = 28;

	private static $start;
	private static $end;
	private static $today_jd;
	private static $today;
	private static $show_today = 1;

	private static $start_week = 0;
	private static $first_monday = 0;

	private static $text_char_per_col = 4.7;
	private static $text_limit_one_col = 2;
	private static $text_min_span_ratio = 0.25;
	private static $text_min_span = 7;

	private static $max_evt_rows = 20;

	private static $props = array(
		'type'				=> self::DAYS_TYPE,
		'min_evt_rows'		=> 0,
		'max_evt_rows'		=> 30
		);

	private static $row_props = array();
	private static $props_keymap = array(
		'class'				=> 0,
		'today_class'		=> 0,
		'text'				=> 0,
		'title'				=> 0,
		'onclick'			=> 0,
		'colspan'			=> 0,
		'rowspan'			=> 0,
		'type'				=> 0,
		'min_evt_rows'		=> 0,
		'max_evt_rows'		=> 0,
		);

	//
	private static $row = -1;
	private static $col = 0;

	private static $ch_row = 'r';
	private static $ch_col = 'c';
	private static $ch_table = 't';

	// classes for every col
	private static $col_class_ary = array();

	// array that keep track of grouped calendar evt properties
	private static $msvr_evt_ary = array();

	// arrays to javascript.
	private static $mouseover_evt_id_ary = array();
	private static $evt_topic_id_ary = array();

	// types of rows

	const DAYS_TYPE = 10;
	const MONTH_DAYS_TYPE = 20;
	const WEEK_DAYS_TYPE = 30;
	const MOONISO_TYPE = 40;
	const MONTH_YEAR_TYPE = 50;
	const EVENTS_TYPE = 60;
	const BIRTHDAYS_TYPE = 70;

	//
	private static $row_type_ary = array();
	private static $today_class_ary = array();
	private static $month_year_class;

	private static $cells = array();

	// moon
	private static $moon_phase_ary = array();
	private static $moon_unix_ary = array();
	private static $mooniso_ary = array();
	private static $moon_class_ary = array('m0', 'm1', 'm2', 'm3');
	private static $moon_text_ary = array(
		'CALENDAR_NEW_MOON',
		'CALENDAR_FIRST_QUARTER_MOON',
		'CALENDAR_FULL_MOON',
		'CALENDAR_THIRD_QUARTER_MOON');
	private static $moon_timeformat = 'H:i';

	// events
	private static $event_ary = array();
	private static $cal_events = array();
	private static $prefix_list = array();

	// birthdays
	private static $birthdays = array();

	// topic color mod
	private static $topic_color;

	// topic filters mod
	private static $topic_filters;
	private static $topic_locations;

	//lang
	private static $week_days_names = array();
	private static $month_names = array();
	private static $month_names_short = array();

/**
**
**/

	public static function init($today_jd, $post_data)
	{
		global $db, $template, $user, $phpEx, $config;

		foreach ($post_data as $key => $val)
		{
			if (property_exists(calendar, $key))
			{
				self::$$key = $val;
			}
		}

		if (!isset(self::$start_jd))
		{
			return false;
		}

		self::$post_data = $post_data;
		self::$today_jd = $today_jd;
		self::$table_days_num = (int) floor(self::$days_num / self::$tables_num);
		self::$days_num = self::$table_days_num * self::$tables_num;
		self::$start_week = self::$start_jd % 7;
		self::$first_monday = 7 - (self::$start_week);
		self::$today = self::$today_jd - self::$start_jd;
		self::$end_jd = self::$start_jd + self::$days_num;
		self::$text_min_span = floor(self::$table_days_num * self::$text_min_span_ratio);
		self::$topic_color = new topic_color();
		self::$topic_locations = new topic_locations();
		self::$topic_filters = new topic_filters($user->lang['ALL_TOPICS']);
		self::$week_days_names = array(
			$user->lang['SUN'],
			$user->lang['MON'],
			$user->lang['TUE'],
			$user->lang['WED'],
			$user->lang['THU'],
			$user->lang['FRI'],
			$user->lang['SAT'],
			);
		self::$month_names = array(
			$user->lang['JANUARY'],
			$user->lang['FEBRUARY'],
			$user->lang['MARCH'],
			$user->lang['APRIL'],
			$user->lang['MAY'],
			$user->lang['JUNE'],
			$user->lang['JULY'],
			$user->lang['AUGUST'],
			$user->lang['SEPTEMBER'],
			$user->lang['OCTOBER'],
			$user->lang['NOVEMBER'],
			$user->lang['DECEMBER']
			);

	}

	public static function set_cols($class, $today_class)
	{
		if ($class)
		{
			if ($class instanceof calendar_pattern)
			{
				if ($class->ary[0] instanceof calendar_pattern)
				{
					$countdown = 0;

					for ($col = 0; $col < self::$days_num; $col++)
					{
						if (!$countdown)
						{
							$unix = ((self::$start_jd + $col) - 2440588) * 86400;
							$month_num_days = (int) gmdate('t', $unix);
							$month_day = (int) gmdate('j', $unix);
							$countdown = $month_num_days - $month_day + 1;
							$month = (int) gmdate('n', $unix) - 1;
							$year = (int) gmdate('Y', $unix);
							$val = $class->ary[($month + ($year *12)) % $class->length];
						}

						self::$col_class_ary[$col] = $val->ary[abs((self::$start_week + $col) % $val->length)];

						$countdown--;
					}
				}
				else
				{
					for ($col = 0; $col < self::$days_num; $col++)
					{
						self::$col_class_ary[$col] = $class->ary[abs((self::$start_week + $col) % $class->length)];
					}
				}
			}
			else
			{
				for ($col = 0; $col < self::$days_num; $col++)
				{
					self::$col_class_ary[$col] = $class;
				}
			}
		}

		if (isset($today_class) && self::$today >= 0 && self::$today < self::$days_num)
		{
			self::$col_class_ary[self::$today] .= ' ' . $today_class;
		}
	}

	public static function insert_month_year_row($class = null)
	{
		if (!($class instanceof calendar_pattern))
		{
			return;
		}
		self::$row++;
		self::$row_type_ary[self::$row] = self::MONTH_YEAR_TYPE;
		self::$month_year_class = $class;
	}

	public static function insert_month_days_row($today_class = null)
	{
		self::$row++;
		self::$row_type_ary[self::$row] = self::MONTH_DAYS_TYPE;
		self::$today_class_ary[self::$row] = $today_class;
	}

	public static function insert_week_days_row($today_class = null)
	{
		self::$row++;
		self::$row_type_ary[self::$row] = self::WEEK_DAYS_TYPE;
		self::$today_class_ary[self::$row] = $today_class;
	}

	public static function insert_mooniso_row($today_class = null)
	{
		global $user;

		self::$row++;
		self::$row_type_ary[self::$row] = self::MOONISO_TYPE;
		self::$today_class_ary[self::$row] = $today_class;

		self::find_moonphases();

		// copy format from user_dateformat for moon_timeformat

		$dateformat = $user->data['user_dateformat'];
		$am_pm = '';
		$g_bool = is_int(strpos($dateformat, 'g'));

		if ($g_bool || is_int(strpos($dateformat, 'h')))
		{
			$hours = ($g_bool) ? 'g' : 'h';
			$am_pm = (is_int(strpos($dateformat, 'A'))) ? ' A' : ' a';
		}
		else
		{
			$hours = (is_int(strpos($dateformat, 'G'))) ? 'G' : 'H';
		}

		self::$moon_timeformat = $hours . ':i' . $am_pm;

		for ($col = self::$first_monday; $col < self::$days_num; $col += 7)
		{
			$unix = ((self::$start_jd + $col) - 2440588) * 86400;
			self::$mooniso_ary[$col] = array(
				'class'	=> 'isoweek',
				'text'	=> gmdate('W', $unix),
				);
		}

		foreach(self::$moon_unix_ary as $key => $unix)
		{
			$local_time = $unix + ($user->data['user_timezone'] * 3600) + ($user->data['user_dst'] * 3600);
			$jd = floor(($local_time / 86400) + 2440588);
			$col = $jd - self::$start_jd;

			if ($col >= 0 && $col <= self::$days_num)
			{
				self::$mooniso_ary[$col] = array(
					'class' => self::$moon_class_ary[self::$moon_phase_ary[$key]],
					'text'	=> '&nbsp;',
					'title'	=> $user->lang[self::$moon_text_ary[self::$moon_phase_ary[$key]]] . ' @ ' .gmdate(self::$moon_timeformat, $local_time),
					);
			}
		}
	}

	public static function insert_days_row($today_class = null)
	{
		self::$row++;
		self::$row_type_ary[self::$row] = self::DAYS_TYPE;
		self::$today_class_ary[self::$row] = $today_class;
	}

	public static function insert_events_rows($today_class = null)
	{
		global $db, $template, $config, $auth, $phpEx;

		$sql_pre = ' AND t.topic_approved = 1';
		$sql_pre .= ' AND t.topic_type IN (' . POST_NORMAL . ', ' . POST_STICKY . ')';

		self::$topic_filters->init_calendar_mode(self::$start_jd, self::$end_jd, $sql_pre, self::$post_data);
		self::$topic_filters->add_forums();
		$prefix_names = (self::$topic_filters->add_prefix());
		self::$topic_filters->add_location(self::$topic_locations);

		self::$topic_filters->gen_sql();
		self::$topic_filters->gen_u();

		self::$topic_filters->set_sql_suf('');
		if ($config['show_topic_filters']) // quick fix by mysql memory shortage
		{
			self::$topic_filters->to_template();
		}

	// print view link

		$print_view = '&amp;start_jd=' . self::$start_jd;
		$print_view .= '&amp;show_today=0';
		$print_view .= self::$topic_filters->get_u();

		$template->assign_vars(array(
			'U_PRINT_TOPIC'			=> append_sid($phpbb_root_path . 'calendar.'  . $phpEx, 'view=print' . $print_view),
			));

	// get calendar events (archive included)

		$topic_list = $cal_events = array();

		$sql = 'SELECT c.*, t.forum_id, t.topic_title,
					t.topic_approved, t.topic_reported,
					t.topic_bg_color
				FROM ' . TOPICS_TABLE . ' t
				LEFT JOIN ' . CALENDAR_PERIODS_TABLE . ' c ON (t.topic_id = c.topic_id)
				WHERE ' . self::$topic_filters->get_sql_base() .
					self::$topic_filters->get_sql_pre() .
					self::$topic_filters->get_sql();

		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$cal_events[] = $row;
			$topic_list[$row['topic_id']] = $row['topic_id'];
		}
		$db->sql_freeresult($result);

	// get topic title prefixes

		$sql = 'SELECT t.topic_id, p.prefix_name
			FROM ' . TTP_PREFIXES_TABLE . ' p
			LEFT JOIN ' . TOPICS_TABLE . ' t ON (t.topic_prefix_id = p.prefix_id)
			WHERE ' . $db->sql_in_set('t.topic_id', $topic_list, false, true);
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			self::$prefix_list[$row['topic_id']] = $row['prefix_name'];
		}
		$db->sql_freeresult($result);

	// place events

		if (!sizeof($cal_events))
		{
			return;
		}

		self::$row++;
		$last_e_row = self::$row;
		$max_row = self::$row + self::$max_evt_rows;

		foreach ($cal_events as $cal_event)
		{
			if (!$auth->acl_get('m_approve', $cal_event['forum_id']) && !$cal_event['topic_approved'])
			{
				continue;
			}

			$event_start = $cal_event['start_jd'] - self::$start_jd;
			$event_end = $cal_event['end_jd'] - self::$start_jd;

			$event_start = ($event_start < 0) ? 0 : $event_start;
			$event_end = ($event_end >= self::$days_num) ? self::$days_num - 1 : $event_end;

			$place_found = false;

			for ($e_row = self::$row; $e_row < $max_row; $e_row++)
			{
				self::$row_type_ary[$e_row] = self::EVENTS_TYPE;
				self::$today_class_ary[$e_row] = $today_class;
				$last_e_row = ($last_e_row < $e_row) ? $e_row : $last_e_row;

				if (empty(self::$event_ary[$e_row]))
				{
					$place_found = true;
				}
				else
				{
					$overlap = false;

					foreach (self::$event_ary[$e_row] as $col => $placed_evt)
					{

						if (!($event_start > $placed_evt['end'] || $event_end < $col))
						{
							$overlap = true;
							break;
						}
					}

					if (!$overlap)
					{
						$place_found = true;
					}
				}

				if ($place_found)
				{
					while (true)
					{
						$next_table = floor($event_start / self::$table_days_num) + 1;
						$next_start = $next_table * self::$table_days_num;

						if ($event_end >= $next_start)
						{
							self::$event_ary[$e_row][$event_start] = array_merge($cal_event, array(
								'end'		=> $next_start - 1,
								'colspan'	=> $next_start - $event_start,
								));
							$event_start = $next_start;
						}
						else
						{
							self::$event_ary[$e_row][$event_start] = array_merge($cal_event, array(
								'end'		=> $event_end,
								'colspan'	=> $event_end - $event_start + 1,
								));
							break;
						}
					}
					break;
				}
			}
		}

		self::$row = $last_e_row + 1;
		self::$row_type_ary[self::$row] = self::DAYS_TYPE;
		self::$today_class_ary[self::$row] = $today_class;
	}

	public static function insert_birthday_rows($today_class = null)
	{
		global $db, $config, $template;

		if (self::$post_data['display_birthdays'])
		{
			self::$row++;
			$first_b_row = self::$row;
			$last_b_row = self::$row;

			$greg = cal_from_jd(self::$start_jd, CAL_GREGORIAN);
			$start_day = $greg['day'];
			$start_month = $greg['month'];
			$start_year = $greg['year'];
			$greg = cal_from_jd(self::$end_jd, CAL_GREGORIAN);
			$end_day = $greg['day'];
			$end_month = $greg['month'];
			$end_year = $greg['year'];

			if ($start_month == $end_month)
			{
				$sql_where = ' user_bday_day >= ' .  $start_day . ' AND user_bday_day <= ' . $end_day;
			}
			else
			{
				$days_in_start_month = cal_days_in_month(CAL_GREGORIAN, $start_month, $start_year);
				$sql_where = ' (user_bday_day >= ' . $start_day . ' AND user_bday_day <= ' . $days_in_start_month . ' AND user_bday_month = ' . $start_month . ')';

				$next_month = $start_month + 1;
				$next_month = ($next_month == 13) ? 1 : $next_month;

				while ($next_month != $end_month)
				{
					$sql_where .= ' OR user_bday_month = ' . $next_month;

					$next_month++;
					$next_month = ($next_month == 13) ? 1 : $next_month;
				}

				$sql_where .= ' OR (user_bday_day <= ' . $end_day . ' AND user_bday_month = ' .  $end_month . ')';
			}

			$sql = 'SELECT user_id, username, user_bday_day, user_bday_month, user_bday_year
					FROM ' . USERS_TABLE . '
					WHERE ' . $sql_where;
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$jd1 = gregoriantojd($row['user_bday_month'], $row['user_bday_day'], $start_year);
				self::add_birthday($row['user_id'], $row['username'], $start_year - $row['user_bday_year'], $jd1, $last_b_row, $today_class);

				if ($start_year != $end_year)
				{
					$jd2 = gregoriantojd($row['user_bday_month'], $row['user_bday_day'], $end_year);
					self::add_birthday($row['user_id'], $row['username'], $end_year - $row['user_bday_year'], $jd2, $last_b_row, $today_class);
				}
			}
			$db->sql_freeresult($result);

			if (!sizeof(self::$birthdays))
			{
				self::$row--;
				return;
			}

			self::$row = $last_b_row + 1;
			self::$row_type_ary[self::$row] = self::DAYS_TYPE;
			self::$today_class_ary[self::$row] = $today_class;

		// shuffle birthdays

			if ($last_b_row != $first_b_row)
			{
				for ($row = $first_b_row; $row <= $last_b_row; $row++)
				{
					foreach (self::$birthdays[$row] as $col => $birthday)
					{
						$rand = mt_rand($first_b_row, $last_b_row);
						if ($rand == $row || isset(self::$birthdays[$rand][$col]) || isset(self::$birthdays[$row][$col]['shuffled']))
						{
							continue;
						}
						self::$birthdays[$rand][$col] = $birthday;
						self::$birthdays[$rand][$col]['shuffled'] = true;
						unset(self::$birthdays[$row][$col]);
					}
				}
			}
		}

		$template->assign_vars(array(
			'S_BIRTHDAYS'	=> true,
			));
	}

	/**
	**
	**/

	private static function add_birthday($user_id, $username, $age, $jd, &$last_b_row, $today_class)
	{
		if ($jd < self::$start_jd && $jd > self::$end_jd)
		{
			return;
		}

		for ($row = self::$row; $row < (self::$row + 10); $row++)
		{
			self::$row_type_ary[$row] = self::BIRTHDAYS_TYPE;
			self::$today_class_ary[$row] = $today_class;
			$last_b_row = ($last_b_row < $row) ? $row : $last_b_row;
			$col = $jd - self::$start_jd;

			if (!isset(self::$birthdays[$row][$col]))
			{
				self::$birthdays[$row][$col] = array(
					'user_id'	=> $user_id,
					'username'	=> $username,
					'age'		=> $age,
					);
				break;
			}
		}
	}

	/**
	**
	**/

	public static function write_to_template()
	{
		global $template, $phpbb_root_path, $phpEx;

		for ($table_index = 0; $table_index < self::$tables_num; $table_index++)
		{
			self::table_to_template($table_index);
		}

		$hidden_calendar_fields = '<input type="hidden" value="' . self::$start_jd . '" name="start_jd" />';

		$template->assign_vars(array(
			'SCRIPT_MSVR_EVT_ID_ARY'		=> '[' . implode('], [', self::$mouseover_evt_id_ary) . ']',
			'S_POST_ACTION'					=> append_sid($phpbb_root_path . 'calendar.' . $phpEx, 'start_jd=' . calendar::$start_jd),
			'S_HIDDEN_CALENDAR_FIELDS'		=> $hidden_calendar_fields,
			'S_DISPLAY_BIRTHDAYS'			=> self::$post_data['display_birthdays'],

			'U_FORUM'						=> (self::$post_data['view']) ? generate_board_url() . '/' : $phpbb_root_path,
			));

		$mid_nav_jd = (self::$post_data['mid_jd']) ? self::$post_data['mid_jd'] : floor((self::$start_jd + self::$end_jd) / 2);
		$nav_start_jd = $mid_nav_jd - 400;
		$unix = ($nav_start_jd - 2440588) * 86400;
		$nav_month = (int) gmdate('n', $unix) - 1;
		$year = (int) gmdate('Y', $unix);
		$nav_sel = 0;

		for ($i = 0; $i < self::$nav_num; $i++)
		{

			$month = ($nav_month + $i) % 12;
			$year = (!$i || $month) ? $year : $year += 1;
			$m_jd = cal_to_jd(CAL_GREGORIAN, ($month + 1), 1, $year);

			$class = ' class="';
			$class .= self::$month_year_class->ary[($month + ($year * 12)) % self::$month_year_class->length];
			$class .= '"';

			$template->assign_block_vars('nav', array(
				'PARAM'		=> $class,
				'TEXT'		=> substr(self::$month_names[$month], 0, 3),
				));

			for ($a = 0; $a < 4; $a++)
			{
				unset($class);

				switch ($nav_sel)
				{
					case 0:

						if (($m_jd + ($a * 7)) > self::$start_jd)
						{
							$class = 'view-left';
							$nav_sel = 1;
						}

						break;

					case 1:

						if (($m_jd + (($a + 1) * 7)) > self::$end_jd)
						{
							$class = 'view-right';
							$nav_sel = 2;
						}
						else
						{
							$class = 'view-mid';
						}

						break;
				}

				$mid_jd = $m_jd + ($a * 7);
				$onclick = ' onclick="navigate_to(' . $mid_jd . ');"';

				$n = $i * 4 + $a;

				$class = ($class) ? ' class="' . $class . '"' : '';

				$template->assign_block_vars('topnav_a', array(
					'PARAM'		=> $class . ' id="na' . $n . '"' . $onclick,
					));
				$template->assign_block_vars('topnav_b', array(
					'PARAM'		=> $class . ' id="nb' . $n . '"' . $onclick,
					));
			}
		}

		add_form_key('postform');
	}

	private static function table_to_template($table_index)
	{
		global $template, $phpbb_root_path, $phpEx, $user, $auth;

		$template->assign_block_vars('table', array(
			'PARAM'		=> ''
			));

		$col_start = $table_index * self::$table_days_num;
		$col_end = $col_start + self::$table_days_num;

		for ($col = $col_start; $col < $col_end; $col++)
		{
			$template->assign_block_vars('table.col', array(
				'PARAM'		=> (isset(self::$col_class_ary[$col]) ? ' class="' . self::$col_class_ary[$col] . '"' : '')
				));
		}

		foreach (self::$row_type_ary as $row => $type)
		{
			$template->assign_block_vars('table.row', array(
				'PARAM'		=> ''
				));

			$today_class = self::$today_class_ary[$row];

			switch ($type)
			{
				case self::DAYS_TYPE:

					for ($col = $col_start; $col < $col_end; $col++)
					{
						$class = ($today_class && $col == self::$today) ? ' class="' . $today_class . '"' : '';
						$template->assign_block_vars('table.row.cell', array(
							'TEXT'		=> ' ',
							'PARAM'		=> $class,
							));
					}

					break;

				case self::MONTH_DAYS_TYPE:

					for ($col = $col_start; $col < $col_end; $col++)
					{
						$unix = ((self::$start_jd + $col) - 2440588) * 86400;
						$class = ($today_class && $col == self::$today) ? ' class="' . $today_class . '"' : '';
						$template->assign_block_vars('table.row.cell', array(
							'TEXT'		=> gmdate('j', $unix),
							'PARAM'		=> $class,
							));
					}

					break;

				case self::WEEK_DAYS_TYPE:

					for ($col = $col_start; $col < $col_end; $col++)
					{
						$unix = ((self::$start_jd + $col) - 2440588) * 86400;
						$class = ($today_class && $col == self::$today) ? ' class="' . $today_class . '"' : '';
						$template->assign_block_vars('table.row.cell', array(
							'TEXT'		=> self::$week_days_names[gmdate('w', $unix)],
							'PARAM'		=> $class,
							));
					}

					break;

				case self::BIRTHDAYS_TYPE:

					for ($col = $col_start; $col < $col_end; $col++)
					{
						if (isset(self::$birthdays[$row][$col]))
						{
							$birthday = self::$birthdays[$row][$col];

							$text = substr($birthday['username'], 0, 2) . '..';
							$title = $user->lang['BIRTHDAY_OF'] . ' ' . $birthday['username'] . (($birthday['age'] < 1000) ? '(' . $birthday['age'] . ')' : '');
							$title = ' title="' . $title . '"';
							$onclick = append_sid($phpbb_root_path . 'memberlist.'  . $phpEx, 'mode=viewprofile&u=' . $birthday['user_id']);
							$onclick = ' onclick="window.location.href=\'' . $onclick . '\'"';

							$param = ' class="birthday" style="cursor:pointer;"' . $title . $onclick;

							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> $text,
								'PARAM'		=> $param,
								));
						}
						else
						{
							$class = ($today_class && $col == self::$today) ? ' class="' . $today_class . '"' : '';
							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> ' ',
								'PARAM'		=> $class,
								));
						}
					}

					break;

				case self::MOONISO_TYPE:

					for ($col = $col_start; $col < $col_end; $col++)
					{
						if (isset(self::$mooniso_ary[$col]))
						{
							$class = ' class="' . self::$mooniso_ary[$col]['class'];
							$class .= ($today_class && $col == self::$today) ? ' ' . $today_class : '';
							$class .= '"';
							$title = (self::$mooniso_ary[$col]['title']) ? ' title="' . self::$mooniso_ary[$col]['title'] . '"' : '';
							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> self::$mooniso_ary[$col]['text'],
								'PARAM'		=> $class . $title,
								));
						}
						else
						{
							$class = ($col == self::$today) ? ' class="' . self::$today_class_ary[$row] . '"' : '';
							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> ' ',
								'PARAM'		=> $class,
								));
						}
					}

					break;

				case self::MONTH_YEAR_TYPE:

					$col = $col_start;

					do
					{
						$jd = $col + self::$start_jd;
						$unix = ($jd - 2440588) * 86400;
						$month_num_days = (int) gmdate('t', $unix);
						$month_day = (int) gmdate('j', $unix);
						$year = gmdate('Y', $unix);
						$month = (int) gmdate('n', $unix) - 1;
						$colspan = $month_num_days - $month_day + 1;
						$last_col = $colspan + $col - 1;

						if ($last_col >= $col_end)
						{
							$colspan = $col_end - $col;
						}

						$class = ' class="';
						$class .= self::$month_year_class->ary[($month + ($year * 12)) % self::$month_year_class->length];
						$class .= '"';
						$text = ($colspan > self::$text_min_span) ? self::$month_names[$month] . ' ' . $year : '&nbsp;';

						$template->assign_block_vars('table.row.cell', array(
							'TEXT'		=> $text,
							'PARAM'		=> $class . ' colspan="' . $colspan . '"',
							));

						$col += $colspan;
					}
					while ($col < $col_end);

					break;

				case self::EVENTS_TYPE:

					$col = $col_start;

					do
					{
						if (isset(self::$event_ary[$row][$col]))
						{
							$evt = self::$event_ary[$row][$col];
							$id = self::$ch_row . $row . self::$ch_col . $col;

							$topic_id = $evt['topic_id'];
							$forum_id = $evt['forum_id'];

							if (isset(self::$evt_topic_id_ary[$topic_id]))
							{
								$evt_id = self::$evt_topic_id_ary[$topic_id];
								self::$mouseover_evt_id_ary[$evt_id] .= ', \'' . $id . '\'';
							}
							else
							{
								$evt_id = sizeof(self::$evt_topic_id_ary);
								self::$evt_topic_id_ary[$topic_id] = $evt_id;
								self::$mouseover_evt_id_ary[$evt_id] = '\'' . $id . '\'';
							}

							$mouseover = ' onmouseover="mouseover_evt(this);"'; //' . $evt_id . ');"';
							$mouseout = ' onmouseout="mouseout_evt(this);"'; //  . $evt_id . ');"';

							$url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $forum_id . '&amp;t=' .$topic_id);
							$onclick = ' onclick="window.location.href=\'' . $url . '\'"';

							$prefix = (self::$prefix_list[$topic_id]) ? self::$prefix_list[$topic_id] : '';

							if ($evt['topic_approved'] || self::$post_data['view'] == 'print')
							{
								$unapproved = '';
							}
							else
							{
								$unapproved = '<a href="';
								$unapproved .= append_sid($phpbb_root_path . 'mcp.' . $phpEx, 'i=queue&amp;mode=approve_details&amp;t=' . $topic_id);
								$unapproved .= '">';
								$unapproved .= $user->img('icon_topic_unapproved', 'TOPIC_UNAPPROVED');
								$unapproved .= '</a>';
							}

							if ($evt['topic_reported'] && $auth->acl_get('m_report', $forum_id) && self::$post_data['view'] != 'print')
							{
								$reported = '<a href="';
								$reported .= append_sid($phpbb_root_path . 'mcp.' . $phpEx, 'i=reports&amp;mode=reports&amp;f=' . $forum_id . '&amp;t=' . $topic_id);
								$reported .= '">';
								$reported .= $user->img('icon_topic_reported', 'POST_REPORTED');
								$reported .= '</a>';
							}
							else
							{
								$reported = '';
							}

							$text = $prefix . $evt['topic_title'];

							$colspan = $evt['colspan'];

							if (self::$post_data['view'] == 'print')
							{
								$color_style = '';
							}
							else
							{
								$color_style = ' style="background-color: ';
								$color_style .= self::$topic_color->obtain_color($evt['topic_bg_color'], 'topic', false, ($row % 2)) . ';"';
							}

							$max_text_lenght = floor(self::$text_char_per_col * $colspan);

							if (strlen($text) > $max_text_lenght)
							{
								$title = ' title="' . $text . '"';

								$len = ($colspan == 1) ? self::$text_limit_one_col : $max_text_lenght;
								$text = substr($text, 0, $len) . '..';
							}
							else
							{
								$title = '';
							}

							$colspan = ($colspan > 1) ? ' colspan="' . $colspan . '"' : '';
							$class = ' class="evt"';

							$param = ' id="' . $id . '"' . $class . $colspan . $onclick . $title . $color_style . $mouseover . $mouseout;
							$text = '<a href="' . $url . '">' . $text . '</a>' . $unapproved . $reported;

							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> $text,
								'PARAM'		=> $param,
								));
							$col += $evt['colspan'];
						}
						else
						{
							$class = ($today_class && $col == self::$today) ? ' class="' . $today_class . '"' : '';
							$template->assign_block_vars('table.row.cell', array(
								'TEXT'		=> ' ',
								'PARAM'		=> $class,
								));
							$col++;
						}
					}
					while($col < $col_end);

				break;
			}
		}
	}

	/**
	** Find jd_time of phases of the moon between two dates.
	** phases: 0 = new moon, 1 = first quarter, 2 = full moon, 3 = third quarter
	** output is self::$moon_phase_ary and self::$moon_unix_ary in local time
	**/
	private static function find_moonphases()
	{
		global $user;

		$synodic_month_length = 29.53058868;
		$deg = pi() / 180;
		$max_moon_cycles = 100;

		$day_floor = self::$start_jd;	//

		if ($day_floor >= 2299161)
		{
			$alpha = floor(($day_floor - 1867216.25) / 36524.25);
			$day_floor = $day_floor + 1 + $alpha - floor($alpha / 4);
		}

		$day_floor += 1524;

		$year = floor(($day_floor - 122.1) / 365.25);
		$month = floor(($day_floor - floor(365.25 * $year)) / 30.6001);

		$month = ($month < 14) ? $month - 1 : $month - 13;
		$year = ($month > 2) ? $year - 4716 : $year - 4715;

		$syn_month = floor(($year + (($month - 1) * (1 / 12)) - 1900) * 12.3685) - 2;  // before :: -2

		for ($max_loop = $syn_month + $max_moon_cycles; $syn_month < $max_loop; $syn_month += 0.25)
		{
			$phase = $syn_month - floor($syn_month);

			$jc_time = $syn_month / 1236.85;		// time in Julian centuries from 1900 January 0.5
			$jc_time2 = $jc_time * $jc_time;		// square for frequent use
			$jc_time3 = $jc_time2 * $jc_time;		// cube for frequent use

			// mean time of phase
			$phase_time = 2415020.75933
				+ $synodic_month_length * $syn_month
				+ 0.0001178 * $jc_time2
				- 0.000000155 * $jc_time3
				+ 0.00033 * sin((166.56 + 132.87 * $jc_time - 0.009173 * $jc_time2) * $deg);

			// Sun's mean anomaly
			$sun_anom = 359.2242
				+ 29.10535608 * $syn_month
				- 0.0000333 * $jc_time2
				- 0.00000347 * $jc_time3;

			// Moon's mean anomaly
			$moon_anom = 306.0253
				+ 385.81691806 * $syn_month
				+ 0.0107306 * $jc_time2
				+ 0.00001236 * $jc_time3;

			// Moon's argument of latitude
			$moon_lat = 21.2964
				+ 390.67050646 * $syn_month
				- 0.0016528 * $jc_time2
				- 0.00000239 * $jc_time3;

			if (($phase < 0.01) || (abs($phase - 0.5) < 0.01))
			{
				// Corrections for New and Full Moon.
				$phase_time += (0.1734 - 0.000393 * $jc_time) * sin($sun_anom * $deg)
					+ 0.0021 * sin(2 * $sun_anom * $deg)
					- 0.4068 * sin($moon_anom * $deg)
					+ 0.0161 * sin(2 * $moon_anom * $deg)
					- 0.0004 * sin(3 * $moon_anom * $deg)
					+ 0.0104 * sin(2 * $moon_lat * $deg)
					- 0.0051 * sin(($sun_anom + $moon_anom) * $deg)
					- 0.0074 * sin(($sun_anom - $moon_anom) * $deg)
					+ 0.0004 * sin((2 * $moon_lat + $sun_anom) * $deg)
					- 0.0004 * sin((2 * $moon_lat - $sun_anom) * $deg)
					- 0.0006 * sin((2 * $moon_lat + $moon_anom) * $deg)
					+ 0.0010 * sin((2 * $moon_lat - $moon_anom) * $deg)
					+ 0.0005 * sin(($sun_anom + 2 * $moon_anom) * $deg);
			}
			else if ((abs($phase - 0.25) < 0.01 || (abs($phase - 0.75) < 0.01)))
			{
				$phase_time += (0.1721 - 0.0004 * $jc_time) * sin($sun_anom * $deg)
					+ 0.0021 * sin(2 * $sun_anom * $deg)
					- 0.6280 * sin($moon_anom * $deg)
					+ 0.0089 * sin(2 * $moon_anom * $deg)
					- 0.0004 * sin(3 * $moon_anom * $deg)
					+ 0.0079 * sin(2 * $moon_lat * $deg)
					- 0.0119 * sin(($sun_anom + $moon_anom) * $deg)
					- 0.0047 * sin(($sun_anom - $moon_anom) * $deg)
					+ 0.0003 * sin((2 * $moon_lat + $sun_anom) * $deg)
					- 0.0004 * sin((2 * $moon_lat - $sun_anom) * $deg)
					- 0.0006 * sin((2 * $moon_lat + $moon_anom) * $deg)
					+ 0.0021 * sin((2 * $moon_lat - $moon_anom) * $deg)
					+ 0.0003 * sin(($sun_anom + 2 * $moon_anom) * $deg)
					+ 0.0004 * sin(($sun_anom - 2 * $moon_anom) * $deg)
					- 0.0003 * sin((2 * $sun_anom + $moon_anom) * $deg);

				if ($phase < 0.5)
				{
					// First quarter correction.
					$phase_time += 0.0028 - (0.0004 * cos($sun_anom * $deg)) + (0.0003 * cos($moon_anom * $deg));
				}
				else
				{
					// Last quarter correction.
					$phase_time += -0.0028 + (0.0004 * cos($sun_anom * $deg)) - (0.0003 * cos($moon_anom * $deg));
				}
			}

			if ($phase_time >= self::$end_jd)
			{
				return true;
			}

			if ($phase_time >= self::$start_jd)
			{
				self::$moon_phase_ary[] = (int) floor(4 * $phase);
				self::$moon_unix_ary[] = ($phase_time - 2440587.5) * 86400;
			}
		}
	}
}

?>
<<<<<<< HEAD
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
=======
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
