<?php
/**
* phpBB Extension - marttiphpbb calendarmonoinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarmonoinput\service;

use phpbb\language\language;
use marttiphpbb\calendarmonoinput\service\store;
use phpbb\extension\manager;
use phpbb\request\request;
use phpbb\event\dispatcher;
use marttiphpbb\calendarmonoinput\util\cnst;
use marttiphpbb\calendarmono\util\cnst as mono_cnst;
use Symfony\Component\DependencyInjection\ContainerInterface;

class posting
{
	protected $container;
	protected $store;
	protected $language;
	protected $ext_manager;
	protected $request;
	protected $dispatcher;

	protected $submit_dates = false;
	protected $start_atom;
	protected $end_atom;
	protected $has_end_date;
	protected $start_jd;
	protected $end_jd;

	public function __construct(
		ContainerInterface $container,
		store $store,
		language $language,
		manager $ext_manager,
		request $request,
		dispatcher $dispatcher
	)
	{
		$this->container = $container;
		$this->store = $store;
		$this->language = $language;
		$this->ext_manager = $ext_manager;
		$this->request = $request;
		$this->dispatcher = $dispatcher;
	}

	public function get_mono_enabled():bool
	{
		return $this->ext_manager->is_enabled('marttiphpbb/calendarmono');
	}

	public function get_datepicker_enabled():bool
	{
		return $this->ext_manager->is_enabled('marttiphpbb/jqueryuidatepicker');
	}

	public function get_ext_enabled():bool
	{
		return $this->get_mono_enabled() && $this->get_datepicker_enabled();
	}

	public function get_forum_enabled(int $forum_id):bool
	{
		return $this->store->get_enabled($forum_id);
	}

	public function get_template_vars(int $forum_id, array $topic_data):array
	{
		if (!$this->get_forum_enabled($forum_id))
		{
			return [];
		}

		if (!$this->get_datepicker_enabled())
		{
			return [];
		}

		$ext = '';
		$start_jd = $end_jd = 0;

		/**
		 * @event
		 * @var array	topic_data
		 * @var	string 	ext				name of listening extension
		 * @var int 	start_jd		start julian day of the calendar event (next, current or last)
		 * @var int 	end_jd			end julian day of the calendar event
		 */
		$vars = ['topic_data', 'ext', 'start_jd', 'end_jd'];
		extract($this->dispatcher->trigger_event('marttiphpbb.calendarmonoinput.tpl_vars', compact($vars)));

		if (!$ext)
		{
			return [];
		}

		$listener = $this->container->get('marttiphpbb.jqueryuidatepicker.listener');
		$listener->enable();

		$data = [
			'minLimit'		=> $this->store->get_lower_limit_days(),
			'maxLimit'		=> $this->store->get_upper_limit_days(),
			'minDuration'	=> $this->store->get_min_duration_days(),
			'maxDuration'	=> $this->store->get_max_duration_days(),
			'dateFormat'	=> $this->store->get_date_format(),
			'firstDay'		=> $this->store->get_first_day(),
			'refTime'		=> $topic_data['topic_time'] ?? time(),
		];

		$start_date = $start_jd ? $this->jd_to_atom_date($start_jd) : '';
		$end_date = $end_jd ? $this->jd_to_atom_date($end_jd) : '';

		$this->language->add_lang('posting', cnst::FOLDER);

		return [
			'S_MARTTIPHPBB_CALENDARMONOINPUT_BEFORE'	=> $this->store->get_placement_before(),
			'S_MARTTIPHPBB_CALENDARMONOINPUT_AFTER'		=> !$this->store->get_placement_before(),
			'S_MARTTIPHPBB_CALENDARMONOINPUT_REQUIRED'	=> $this->store->get_required($forum_id),
			'S_MARTTIPHPBB_CALENDARMONOINPUT_END'		=> $this->store->get_max_duration_days() > 1,
			'MARTTIPHPBB_CALENDARMONOINPUT_PLACEHOLDER_START_DATE'
				=> $this->store->get_placeholder_start_date(),
			'MARTTIPHPBB_CALENDARMONOINPUT_PLACEHOLDER_END_DATE'
				=> $this->store->get_placeholder_end_date(),
			'MARTTIPHPBB_CALENDARMONOINPUT_DATE_START'	=> $start_date,
			'MARTTIPHPBB_CALENDARMONOINPUT_DATE_END'	=> $end_date,
			'MARTTIPHPBB_CALENDARMONOINPUT_DATA'		=> htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8'),
		];
	}

	public function process_submit(int $forum_id):void
	{
		if (!$this->get_ext_enabled())
		{
			return;
		}

		if (!$this->get_forum_enabled($forum_id))
		{
			return;
		}

		$this->submit_dates = true;
		$this->has_end_date = $this->store->get_max_duration_days() > 1;
		$this->start_atom = $this->request->variable('alt_calendarmonoinput_date_start', '');
		$this->end_atom = $this->request->variable('alt_calendarmonoinput_date_end', '');

		$start_ui = $this->request->variable('calendarmonoinput_date_start', '');
		$end_ui = $this->request->variable('calendarmonoinput_date_end', '');

		$this->start_atom = empty($start_ui) ? '' : $this->start_atom;
		$this->end_atom = empty($end_ui) ? '' : $this->end_atom;
		$this->end_atom = $this->has_end_date ? $this->end_atom : $this->start_atom;
	}

	public function get_submit_errors(int $forum_id, array $topic_data):array
	{
		if (!$this->submit_dates)
		{
			return [];
		}

		$errors = [];
		$start_str = $this->has_end_date ? '_START' : '';
		$this->language->add_lang('posting', cnst::FOLDER);

		$required = $this->store->get_required($forum_id);

		if ($required)
		{
			if (empty($this->start_atom))
			{
				$errors[] = $this->language->lang(cnst::L . $start_str . '_DATE_REQUIRED_ERROR');
			}

			if (empty($this->end_atom) && $this->has_end_date)
			{
				$errors[] = $this->language->lang(cnst::L . '_END_DATE_REQUIRED_ERROR');
			}
		}
		else
		{
			if (empty($this->start_atom))
			{
				if (empty($this->end_atom))
				{
					return [];
				}

				if ($this->has_end_date)
				{
					return [$this->language->lang(cnst::L . '_START_DATE_EMPTY_ERROR')];
				}
			}
			else if ($this->has_end_date && empty($this->end_atom))
			{
				return [$this->language->lang(cnst::L . '_END_DATE_EMPTY_ERROR')];
			}
		}

		if (count($errors))
		{
			return $errors;
		}

		if (!$this->validate_atom_date($this->start_atom))
		{
			$errors[] = $this->language->lang(cnst::L . $start_str . '_DATE_FORMAT_ERROR');
		}

		if ($this->has_end_date && !$this->validate_atom_date($this->end_atom))
		{
			$errors[] = $this->language->lang(cnst::L . '_END_DATE_FORMAT_ERROR');
		}

		if (count($errors))
		{
			return $errors;
		}

		$this->start_jd = $this->atom_date_to_jd($this->start_atom);
		$this->end_jd = $this->atom_date_to_jd($this->end_atom);

		if ($this->start_jd > $this->end_jd)
		{
			return [$this->language->lang(cnst::L . '_DATES_WRONG_ORDER_ERROR')];
		}

		$max_duration = $this->store->get_max_duration_days();

		if (($this->end_jd - $this->start_jd + 1) > $max_duration)
		{
			return [$this->language->lang(cnst::L . '_TOO_LONG_PERIOD_ERROR', $max_duration)];
		}

		$min_duration = $this->store->get_min_duration_days();

		if (($this->end_jd - $this->start_jd + 1) < $min_duration)
		{
			return [$this->language->lang(cnst::L . '_TOO_SHORT_PERIOD_ERROR', $min_duration)];
		}

		$ref_jd = unixtojd($topic_data['topic_time'] ?? time());
		$min_jd = $ref_jd + $this->store->get_lower_limit_days();
		$max_jd = $ref_jd + $this->store->get_upper_limit_days();

		if ($this->start_jd  < $min_jd)
		{
			return [$this->language->lang(cnst::L . $start_str . '_DATE_UNDER_LIMIT_ERROR')];
		}

		if ($this->start_jd  > $max_jd)
		{
			return [$this->language->lang(cnst::L . $start_str . '_DATE_OVER_LIMIT_ERROR')];
		}

		return [];
	}

	public function get_start_jd():?int
	{
		return $this->start_jd;
	}

	public function get_end_jd():?int
	{
		return $this->end_jd;
	}

	private function validate_atom_date(string $atom_date):bool
	{
		$parts = [];

		if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $atom_date, $parts) === 1)
		{
			return checkdate($parts[2], $parts[3], $parts[1]);
		}

		return false;
	}

	private function atom_date_to_jd(string $atom_date):int
	{
		list($y, $m, $d) = explode('-', $atom_date);
		return cal_to_jd(CAL_GREGORIAN, (int) $m, (int) $d, (int) $y);
	}

	private function jd_to_atom_date(int $jd):string
	{
		$c = cal_from_jd($jd, CAL_GREGORIAN);
		return sprintf('%04d-%02d-%02d', $c['year'], $c['month'], $c['day']);
	}
}
