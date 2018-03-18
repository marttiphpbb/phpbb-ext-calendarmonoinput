<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\render;

use phpbb\template\template;
use phpbb\language\language;
use marttiphpbb\calendarinput\render\include_assets;
use marttiphpbb\calendarinput\repository\settings;

class posting
{
	/** @var settings */
	private $settings;

	/** @var template */
	private $template;

	/** @var language */
	private $language;

	/** @var include_assets */
	private $include_assets;

	/**
	 * @param settings $settings
	* @param template	$template
	* @param language		$language
	* @param include_assets $include_assets
	*/
	public function __construct(
		settings $settings,
		template $template,
		language $language,
		include_assets $include_assets
	)
	{
		$this->settings = $settings;
		$this->template = $template;
		$this->language = $language;
		$this->include_assets = $include_assets;
	}

	/*
	 * @param int
	 * @param array
	 * @return self
	 */
	public function assign_template_vars(int $forum_id, array $post_data)
	{
		$enabled = $this->settings->get_enabled($forum_id);

		if (!$enabled)
		{
			return;
		}

		$user_lang = $this->language->lang('USER_LANG');

		$strpos_user_lang = strpos($user_lang, '-x-');

		if ($strpos_user_lang !== false)
		{
			$user_lang = substr($user_lang, 0, $strpos_user_lang);
		}

		list($user_lang_short) = explode('-', $user_lang);

		$this->template->assign_vars([
			'CALENDARINPUT_USER_LANG_SHORT'		=> $user_lang_short,
			'S_CALENDARINPUT_INPUT'				=> true,
			'S_CALENDARINPUT_TO_INPUT'			=> $this->settings->get_max_duration() > 1,
			'S_CALENDARINPUT_REQUIRED'			=> $this->settings->get_required($forum_id),
			'CALENDARINPUT_LOWER_LIMIT'			=> $this->settings->get_lower_limit(),
			'CALENDARINPUT_UPPER_LIMIT'			=> $this->settings->get_upper_limit(),
			'CALENDARINPUT_MIN_DURATION'		=> $this->settings->get_min_duration(),
			'CALENDARINPUT_MAX_DURATION'		=> $this->settings->get_max_duration(),
			'CALENDARINPUT_DATE_FORMAT'			=> 'yyyy-mm-dd',
			'CALENDARINPUT_DATE_START'			=> isset($post_data['topic_calendarinput_start']) ? gmdate('Y-m-d', $post_data['topic_calendarinput_start']) : '', 
			'CALENDARINPUT_DATE_END'			=> isset($post_data['topic_calendarinput_end']) ? gmdate('Y-m-d', $post_data['topic_calendarinput_end']) : '',
			'CALENDARINPUT_DATEPICKER_THEME'	=> $this->settings->get_datepicker_theme(),
		]);

		$this->include_assets->assign_template_vars();
		$this->language->add_lang('posting', 'marttiphpbb/calendarinput');
	}
}
