<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\render;

use marttiphpbb\calendarinput\service\store;
use phpbb\template\template;

class input_range
{
	private $store;
	private $template;

	public function __construct(
		store $store,
		template $template
	)
	{
		$this->store = $store;
		$this->template = $template;
	}

	public function assign_template_vars()
	{
		$this->template->assign_vars([
			'CALENDARINPUT_LOWER_LIMIT_DAYS' 	=> $this->store->get_lower_limit_days(),
			'CALENDARINPUT_UPPER_LIMIT_DAYS' 	=> $this->store->get_upper_limit_days(),
			'CALENDARINPUT_MIN_DURATION_DAYS' 	=> $this->store->get_min_duration_days(),
			'CALENDARINPUT_MAX_DURATION_DAYS' 	=> $this->store->get_max_duration_days(),
			'S_CALENDARINPUT_TO_INPUT'			=> $this->store->get_max_duration_days() > 1,
		]);
	}
}
