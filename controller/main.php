<?php

/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2015 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\controller;

use phpbb\auth\auth;
use phpbb\cache\service as cache;
use phpbb\config\db as config;
use phpbb\db\driver\factory as db;
use phpbb\request\request;
use phpbb\template\twig\twig as template;
use phpbb\user;
use phpbb\controller\helper;

use marttiphpbb\calendar\manager\calendar_event_manager;
use marttiphpbb\calendar\util\moonphase_calculator;
use marttiphpbb\calendar\util\timeformat;

use marttiphpbb\calendar\core\timespan;

use Symfony\Component\HttpFoundation\Response;

class main
{
	/*
	 * @var auth
	*/
	protected $auth;

	/*
	 * @var cache
	*/
	protected $cache;

	/*
	 * @var config
	*/
	protected $config;

	/*
	 * @var array
	*/
	protected $now;

	/*
	 * @var calendar_event_manager
	 */

	protected $calendar_event_manager;

	/*
	 * @var moonphase_calculator
	 */
	protected $moonphase_calculator;

	/*
	 * @var int
	 */

	protected $time_offset;

	/*
	 * @var timeformat
	 */
	protected $timeformat;

	static protected $month_abbrev = array(
		1	=> 'Jan',
		2	=> 'Feb',
		3	=> 'Mar',
		4	=> 'Apr',
		5	=> 'May_short',
		6	=> 'Jun',
		7	=> 'Jul',
		8	=> 'Aug',
		9	=> 'Sep',
		10	=> 'Oct',
		11	=> 'Nov',
		12	=> 'Dec',
	);

	/**
	* @param auth $auth
	* @param cache $cache
	* @param config   $config
	* @param db   $db
	* @param string $php_ext
	* @param request   $request
	* @param template   $template
	* @param user   $user
	* @param helper $helper
	* @param string $root_path
	* @param moonphase_calculator $moonphase_calculator
	* @param timeformat $timeformat
	*
	*/

	public function __construct(
		auth $auth,
		cache $cache,
		config $config,
		db $db,
		$php_ext,
		request $request,
		template $template,
		user $user,
		helper $helper,
		$root_path,
		calendar_event_manager $calendar_event_manager,
		moonphase_calculator $moonphase_calculator,
		timeformat $timeformat
	)
	{
		$this->auth = $auth;
		$this->cache = $cache;
		$this->config = $config;
		$this->db = $db;
		$this->php_ext = $php_ext;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->root_path = $root_path;
		$this->calendar_event_manager = $calendar_event_manager;
		$this->moonphase_calculator = $moonphase_calculator;
		$this->timeformat = $timeformat;

		$now = $user->create_datetime();
		$this->time_offset = $now->getOffset();
		$this->now = phpbb_gmgetdate($now->getTimestamp() + $this->time_offset);
	}

	/**
	* @return Response
	*/
	public function defaultview()
	{
		make_jumpbox(append_sid($this->root_path . 'viewforum.' . $this->php_ext));
		return $this->monthview($this->now['year'], $this->now['mon']);
	}

	/**
	* @param int   $year
	* @return Response
	*/
	public function yearview($year)
	{
		make_jumpbox(append_sid($this->root_path . 'viewforum.' . $this->php_ext));
		return $this->helper->render('year.html');
	}

	/**
	* @param int   $year
	* @param int   $month
	* @return Response
	*/
	public function monthview($year, $month)
	{
		$month_start_time = gmmktime(0,0,0, (int) $month, 1, (int) $year);
		$month_start_weekday = gmdate('w', $month_start_time);
		$month_days_num = gmdate('t', $month_start_time);
//		$prev_month_days_num = gmdate('t', $month_start_time - 86400);

		$days_prefill = $month_start_weekday - $this->config['calendar_first_weekday'];
		$days_prefill += ($days_prefill < 0) ? 7 : 0;
		$prefill = $days_prefill * 86400;

		$days_endfill = 7 - (($month_days_num + $days_prefill) % 7);
		$days_endfill = ($days_endfill == 7) ? 0 : $days_endfill;
		$endfill = $days_endfill * 86400 - 1;

		$month_length = $month_days_num * 86400;

		$start = $month_start_time - $prefill;
		$end = $month_start_time + $month_length + $endfill;

		$days_num = $days_prefill + $month_days_num + $days_endfill;

		$mday = 1;
		$mday_total = 0;

		$timespan = new timespan($start - $this->time_offset, $end - $this->time_offset);

		//
		$moonphases = $this->moonphase_calculator->find_in_timespan($timespan);
		reset($moonphases);

		$events = $this->calendar_event_manager->find_in_timespan($timespan);
		reset($events);

		$time = $start;

		for ($day = 0; $day < $days_num; $day++)
		{
			$new_week = ($day % 7) ? false : true;
			$wday = ($day % 7) + $this->config['calendar_first_weekday'];

			if ($new_week)
			{
				$this->template->assign_block_vars('week', array(
					'ISOWEEK'  => gmdate('W', $time + 86400),
				));

			}

			if ($mday > $mday_total)
			{
				$mday = gmdate('j', $time);
				$mday_total = gmdate('t', $time);
				$mon = gmdate('n', $time);
				$myear = gmdate('Y', $time);
			}

			$day_end_time = $time + 86399;

			$weekday_abbrev = gmdate('D', $time);
			$weekday_name = gmdate('l', $time);

			$day_template = array(
				'WEEKDAY_CLASS' 	=> strtolower($weekday_abbrev),
				'WEEKDAY_NAME'		=> $this->user->lang['datetime'][$weekday_name],
				'WEEKDAY_ABBREV'	=> $this->user->lang['datetime'][$weekday_abbrev],
				'MDAY'				=> $mday,
				'S_TODAY'			=> ($this->now['year'] == $year && $this->now['mon'] == $mon && $this->now['mday'] == $mday) ? true : false,
				'S_FOCUS'			=> ($mon == $month) ? true : false,
				'U_DAY'		=> $this->helper->route('marttiphpbb_calendar_dayview_controller', array(
					'year' 	=> $myear,
					'month'	=> $mon,
					'day'	=> $mday)),
			);

			$moonphase = current($moonphases);

			if (is_array($moonphase) && ($moonphase['time'] >= $time && $moonphase['time'] <= $day_end_time))
			{
				$day_template = array_merge($day_template, array(
					'MOON_NAME'			=> $moonphase['name'],
					'MOON_PHASE'		=> $moonphase['phase'],
					'MOON_TIME'			=> $this->user->format_date($moonphase['time'], (string) $this->timeformat, true),
				));

				if (!next($moonphases))
				{
					$moonphases = array();
				}
			}

			$this->template->assign_block_vars('week.day', $day_template);

			$mday++;
			$time += 86400;
		}

		$this->template->assign_vars(array(
			'MONTH'			=> $this->user->format_date($month_start_time, 'F', true),
			'YEAR'			=> $year,
			'U_YEAR'		=> $this->helper->route('marttiphpbb_calendar_yearview_controller', array(
				'year' => $year)),
		));

		// pagination

		$this->template->assign_block_vars('pagination', array(
			'S_IS_PREV'		=> true,
			'PAGE_URL'		=> $this->helper->route('marttiphpbb_calendar_monthview_controller', array(
				'year' 	=> ($month == 1) ? $year - 1 : $year,
				'month'	=> ($month == 1) ? 12 : $month - 1,
			)),
		));

		for ($i = -2; $i < 3; $i++)
		{
			$pag_month = $month + $i;
			$pag_year = $year;

			if ($pag_month < 1)
			{
				$pag_year--;
				$pag_month += 12;
			}
			else if ($pag_month > 12)
			{
				$pag_year++;
				$pag_month -= 12;
			}

			$this->template->assign_block_vars('pagination', array(
				'S_IS_CURRENT'	=> ($i) ? false : true,
				'PAGE_NUMBER'	=> $this->user->lang['datetime'][main::$month_abbrev[$pag_month]],
				'PAGE_URL'		=> $this->helper->route('marttiphpbb_calendar_monthview_controller', array(
					'year' 	=> $pag_year,
					'month'	=> $pag_month,
				)),
			));
		}

		$this->template->assign_block_vars('pagination', array(
			'S_IS_NEXT'		=> true,
			'PAGE_URL'		=> $this->helper->route('marttiphpbb_calendar_monthview_controller', array(
				'year' 	=> ($month == 12) ? $year + 1 : $year,
				'month'	=> ($month == 12) ? 1 : $month + 1,
			)),
		));

		make_jumpbox(append_sid($this->root_path . 'viewforum.' . $this->php_ext));
		return $this->helper->render('month.html');
	}

	/**
	* @param int   $year
	* @param int   $month
	* @param int   $day
	* @return Response
	*/
	public function dayview($year, $month, $day)
	{
		make_jumpbox(append_sid($this->root_path . 'viewforum.' . $this->php_ext));
		return $this->helper->render('day.html');
	}
	/**
	* @param int   $year
	* @param int   $month
	* @param int   $day
	* @param int   $length
	* @return Response
	*/
	public function continuousview($year, $month, $day, $length)
	{
		make_jumpbox(append_sid($this->root_path . 'viewforum.' . $this->php_ext));
		return $this->helper->render('calendar.html');
	}

}
