<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\render;

use phpbb\config\config;
use phpbb\template\template;
use phpbb\language\language;
use marttiphpbb\calendarinput\render\input_settings;
use marttiphpbb\calendarinput\render\include_assets;

class posting
{
	/* @var config */
	protected $config;

	/* @var template */
	protected $template;

	/* @var language */
	protected $language;

	/* @var input_settings */
	private $input_settings;

	/* @var include_assets */
	private $include_assets;

	/**
	* @param config		$config
	* @param template	$template
	* @param language		$language
	* @param input_settings $input_settings
	* @param include_assets $include_assets
	*/
	public function __construct(
		config $config,
		template $template,
		language $language,
		input_settings $input_settings,
		include_assets $include_assets
	)
	{
		$this->config = $config;
		$this->template = $template;
		$this->language = $language;
		$this->input_settings = $input_settings;
		$this->include_assets = $include_assets;
	}

	/*
	 * @param int
	 * @param array
	 * @return self
	 */
	public function assign_template_vars(int $forum_id, array $post_data)
	{
		$enabled = $this->input_settings->get_enabled($forum_id);

		if (!$enabled)
		{
			return;
		}

		$required = $this->input_settings->get_required($forum_id);		

		$input_settings = $this->input_settings->get();

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
			'S_CALENDARINPUT_TO_INPUT'			=> $input_settings['max_duration'] ? true : false,
			'S_CALENDARINPUT_REQUIRED'			=> $required,
			'CALENDARINPUT_LOWER_LIMIT'			=> $input_settings['lower_limit'],
			'CALENDARINPUT_UPPER_LIMIT'			=> $input_settings['upper_limit'],
			'CALENDARINPUT_MIN_DURATION'			=> $input_settings['min_duration'],
			'CALENDARINPUT_MAX_DURATION'			=> $input_settings['max_duration'],
			'CALENDARINPUT_DATE_FORMAT'			=> 'yyyy-mm-dd',
			'CALENDARINPUT_DATE_START'			=> isset($post_data['topic_calendarinput_start']) ? gmdate('Y-m-d', $post_data['topic_calendarinput_start']) : '', 
			'CALENDARINPUT_DATE_END'				=> isset($post_data['topic_calendarinput_end']) ? gmdate('Y-m-d', $post_data['topic_calendarinput_end']) : '',
			'CALENDARINPUT_DATEPICKER_THEME'		=> $this->config['calendarinput_datepicker_theme'],
		]);

		$this->include_assets->assign_template_vars();
		$this->language->add_lang('posting', 'marttiphpbb/calendarinput');
	}
}
