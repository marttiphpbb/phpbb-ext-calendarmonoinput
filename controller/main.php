<?php

/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2017 marttiphpbb <info@martti.be>
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
use phpbb\language\language;
use phpbb\controller\helper;

use marttiphpbb\calendar\core\event_container;
use marttiphpbb\calendar\util\moonphase_calculator;
use marttiphpbb\calendar\util\timeformat;
use marttiphpbb\calendar\model\render_settings;
use marttiphpbb\calendar\model\pagination;

use marttiphpbb\calendar\core\timespan;

use Symfony\Component\HttpFoundation\Response;

class main
{
	/* @var auth */
	protected $auth;

	/* @var cache */
	protected $cache;

	/* @var config */
	protected $config;

	/* @var array */
	protected $now;

	/* @var event_container */
	protected $event_container;

	/* @var moonphase_calculator */
	protected $moonphase_calculator;

	/* @var int */
	protected $time_offset;

	/* @var timeformat */
	protected $timeformat;

	/* @var render_settings */
	protected $render_settings;

	/* @var pagination */
	protected $pagination;

	/**
	* @param auth $auth
	* @param cache $cache
	* @param config   $config
	* @param db   $db
	* @param string $php_ext
	* @param request   $request
	* @param template   $template
	* @param user   $user
	* @param language $language
	* @param helper $helper
	* @param string $root_path
	* @param moonphase_calculator $moonphase_calculator
	* @param timeformat $timeformat
	* @param render_settings $render_settings
	* @param pagination $pagination
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
		language $language,
		helper $helper,
		$root_path,
		event_container $event_container,
		moonphase_calculator $moonphase_calculator,
		timeformat $timeformat,
		render_settings $render_settings,
		pagination $pagination
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
		$this->language = $language;
		$this->helper = $helper;
		$this->root_path = $root_path;
		$this->event_container = $event_container;
		$this->moonphase_calculator = $moonphase_calculator;
		$this->timeformat = $timeformat;
		$this->render_settings = $render_settings;
		$this->pagination = $pagination;

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

		$days_prefill = $month_start_weekday - $this->config['calendar_first_weekday'];
		$days_prefill += $days_prefill < 0 ? 7 : 0;
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

		$moonphases = $this->moonphase_calculator->find_in_timespan($timespan);
		reset($moonphases);

		$this->event_container->fetch($timespan);

		$day_tpl = [];

		$time = $start;

		for ($day = 0; $day < $days_num; $day++)
		{
			$new_week = ($day % 7) ? false : true;
			$wday = ($day % 7) + $this->config['calendar_first_weekday'];

			if ($new_week)
			{
				$day_tpl[$day]['week'] = [
					'ISOWEEK'  => gmdate('W', $time + 86400),
				];
			}

			if ($mday > $mday_total)
			{
				$mday = gmdate('j', $time);
				$mday_total = gmdate('t', $time);
				$mon = gmdate('n', $time);
			}

			$day_end_time = $time + 86399;

			$weekday_abbrev = gmdate('D', $time);
			$weekday_name = gmdate('l', $time);

			$day_template = [
				'CLASS' 	=> strtolower($weekday_abbrev),
				'NAME'		=> $this->language->lang(['datetime', $weekday_name]),
				'ABBREV'	=> $this->language->lang(['datetime', $weekday_abbrev]),
				'MDAY'		=> $mday,
				'S_TODAY'	=> $this->now['year'] == $year && $this->now['mon'] == $mon && $this->now['mday'] == $mday ? true : false,
				'S_BLUR'	=> $mon != $month ? true : false,
			];

			$day_tpl[$day]['day'] = $day_template;

			$moonphase = current($moonphases);

			if (is_array($moonphase)
				&& ($moonphase['time'] >= $time
				&& $moonphase['time'] <= $day_end_time))
			{
				$day_template = array_merge($day_template, [
					'MOON_NAME'			=> $moonphase['name'],
					'MOON_ICON'			=> $moonphase['icon'],
					'MOON_PHASE'		=> $moonphase['phase'],
					'MOON_TIME'			=> $this->user->format_date($moonphase['time'], (string) $this->timeformat, true),
				]);

				if (!next($moonphases))
				{
					$moonphases = [];
				}
			}

			$day_tpl[$day]['day_moon'] = $day_template;

			$mday++;
			$time += 86400;
		}


		foreach($day_tpl as $day => $tpl)
		{
			if (isset($tpl['week']))
			{
				$this->template->assign_block_vars('week', $tpl['week']);

				for($evrow = 0; $evrow < 8; $evrow++)
				{
					$this->template->assign_block_vars('week.eventrow', $tpl['week']);

					$d7 = $day + 7;

					for($d = $day; $d < $d7; $d++)
					{
						$this->template->assign_block_vars('week.eventrow.day', $day_tpl[$d]['day']);
					} 
				}
			}

			$this->template->assign_block_vars('week.day', $tpl['day_moon']);
		}


		$this->render_settings->assign_template_vars();

		$this->template->assign_vars([
			'MONTH'			=> $this->user->format_date($month_start_time, 'F', true),
			'YEAR'			=> $year,
			'U_YEAR'		=> $this->helper->route('marttiphpbb_calendar_yearview_controller', [
				'year' => $year]),
		]);

		$this->render_settings->assign_template_vars();

		$this->pagination->render($year, $month);

		make_jumpbox(append_sid($this->root_path . 'viewforum.' . $this->php_ext));

		return $this->helper->render('month.html');
	}
}
