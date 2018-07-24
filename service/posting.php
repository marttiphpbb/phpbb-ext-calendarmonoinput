<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\service;

use phpbb\template\template;
use phpbb\language\language;
use marttiphpbb\calendarinput\service\store;
use phpbb\extension\manager;
use marttiphpbb\calendarinput\util\cnst;
use marttiphpbb\calendarmono\util\cnst as mono_cnst;
use Symfony\Component\DependencyInjection\ContainerInterface;


class posting
{
	protected $container;
	protected $store;
	protected $template;
	protected $language;
	protected $ext_manager;

	public function __construct(
		ContainerInterface $container,
		store $store,
		template $template,
		language $language,
		manager $ext_manager
	)
	{
		$this->container = $container;
		$this->store = $store;
		$this->template = $template;
		$this->language = $language;
		$this->ext_manager = $ext_manager;
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

	public function assign_template_vars(int $forum_id, array $post_data)
	{
		if (!$this->get_forum_enabled($forum_id))
		{
			return;
		}

		if (!$this->get_ext_enabled())
		{
			return;
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
		];

		$start_date = isset($post_data[mono_cnst::COLUMN_START]) ? cal_from_jd($post_data[mono_cnst::COLUMN_START]) : '';
		$end_date = isset($post_data[mono_cnst::COLUMN_END]) ? cal_from_jd($post_data[mono_cnst::COLUMN_END]) : '';

		$this->template->assign_vars([
			'S_MARTTIPHPBB_CALENDARINPUT_BEFORE'	=> $this->store->get_placement_before(),
			'S_MARTTIPHPBB_CALENDARINPUT_AFTER'		=> !$this->store->get_placement_before(),
			'S_MARTTIPHPBB_CALENDARINPUT_REQUIRED'	=> $this->store->get_required($forum_id),
			'S_MARTTIPHPBB_CALENDARINPUT_END'		=> $this->store->get_max_duration_days() > 1,
			'MARTTIPHPBB_CALENDARINPUT_PLACEHOLDER_START_DATE'
				=> $this->store->get_placeholder_start_date(),
			'MARTTIPHPBB_CALENDARINPUT_PLACEHOLDER_END_DATE'
				=> $this->store->get_placeholder_end_date(),
			'MARTTIPHPBB_CALENDARINPUT_DATE_START'	=> $end_date,
			'MARTTIPHPBB_CALENDARINPUT_DATE_END'	=> $start_date,
			'MARTTIPHPBB_CALENDARINPUT_DATA'		=> htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8'),
		]);
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
