<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\render;

use phpbb\template\template;
use phpbb\language\language;
use marttiphpbb\calendarinput\repository\settings;

class posting
{
	/** @var settings */
	private $settings;

	/** @var template */
	private $template;

	/** @var language */
	private $language;

	/**
	 * @param settings $settings
	* @param template	$template
	* @param language		$language
	*/
	public function __construct(
		settings $settings,
		template $template,
		language $language
	)
	{
		$this->settings = $settings;
		$this->template = $template;
		$this->language = $language;
	}

	/*
	 * @param int
	 * @param array
	 */
	public function assign_template_vars(int $forum_id, array $post_data)
	{
		$enabled = $this->settings->get_enabled($forum_id);

		if (!$enabled)
		{
			return;
		}

		$this->template->assign_vars([
			'S_CALENDARINPUT_INPUT'				=> true,
			'S_CALENDARINPUT_REQUIRED'			=> $this->settings->get_required($forum_id),
			'CALENDARINPUT_DATE_FORMAT'			=> 'yyyy-mm-dd',
			'CALENDARINPUT_DATE_START'			=> isset($post_data['topic_calendarinput_start']) ? gmdate('Y-m-d', $post_data['topic_calendarinput_start']) : '', 
			'CALENDARINPUT_DATE_END'			=> isset($post_data['topic_calendarinput_end']) ? gmdate('Y-m-d', $post_data['topic_calendarinput_end']) : '',
		]);
	}
}