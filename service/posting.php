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

class posting
{
	private $store;
	private $template;
	private $language;

	public function __construct(
		store $store,
		template $template,
		language $language
	)
	{
		$this->store = $store;
		$this->template = $template;
		$this->language = $language;
	}

	public function assign_template_vars(int $forum_id, array $post_data)
	{
		$enabled = $this->store->get_enabled($forum_id);

		if (!$enabled)
		{
			return;
		}

		$data = [
			'min_limit'		=> $this->store->get_lower_limit_days(),
			'max_limit'		=> $this->store->get_upper_limit_days(),
			'min_duration'	=> $this->store->get_min_duration_days(),
			'max_duration'	=> $this->store->get_max_duration_days(),
		];

		$this->template->assign_vars([
			'S_CALENDARINPUT_INPUT'					=> true,
			'S_MARTTIPHPBB_CALENDARINPUT_DATA'		=> htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8'),
			'S_MARTTIPHPBB_CALENDARINPUT_REQUIRED'	=> $this->store->get_required($forum_id),
			'MARTTIPHPBB_CALENDARINPUT_DATE_FORMAT'	=> 'yyyy-mm-dd',
			'MARTTIPHPBB_CALENDARINPUT_DATE_START'	=> isset($post_data['topic_calendarinput_start']) ? gmdate('Y-m-d', $post_data['topic_calendarinput_start']) : '',
			'MARTTIPHPBB_CALENDARINPUT_DATE_END'	=> isset($post_data['topic_calendarinput_end']) ? gmdate('Y-m-d', $post_data['topic_calendarinput_end']) : '',
		]);
	}
}
