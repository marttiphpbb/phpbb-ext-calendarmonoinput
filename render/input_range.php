<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\render;

use marttiphpbb\calendarinput\repository\settings;
use phpbb\template\template;

class input_range
{
	/** @var settings */
	private $settings;

	/* @var template */
	private $template;

	/**
	* @param settings $settings
	* @param template	$template
	*/
	public function __construct(
		settings $settings,
		template $template
	)
	{
		$this->settings = $settings;
		$this->template = $template;
	}

	public function assign_template_vars()
	{
		$this->template->assign_vars([
			'CALENDARINPUT_LOWER_LIMIT_DAYS' 	=> $this->settings->get_lower_limit_days(),
			'CALENDARINPUT_UPPER_LIMIT_DAYS' 	=> $this->settings->get_upper_limit_days(),			
			'CALENDARINPUT_MIN_DURATION_DAYS' 	=> $this->settings->get_min_duration_days(),			
			'CALENDARINPUT_MAX_DURATION_DAYS' 	=> $this->settings->get_max_duration_days(),
			'S_CALENDARINPUT_TO_INPUT'			=> $this->settings->get_max_duration_days() > 1,
		]);
	}
}
