<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\render;

use phpbb\config\config;
use phpbb\config\db_text as config_text;
use phpbb\template\template;
use phpbb\language\language;

class input_settings
{
	/* @var config */
	protected $config;

	/* @var config_text */
	protected $config_text;

	/* @var template */
	protected $template;

	/* @var language */
	protected $language;

	/* @var input_settings */
	protected $input_settings;

	protected $input_settings_default = [
		'lower_limit'	=> 0,
		'upper_limit'	=> 31536000,
		'min_duration'	=> 0,
		'max_duration'	=> 2592000,
	];

	/**
	* @param config		$config
	* @param config_text		$config_text
	* @param template	$template
	* @param language		$language
	*/
	public function __construct(
		config $config,
		config_text $config_text,
		template $template,
		language $language
	)
	{
		$this->config = $config;
		$this->config_text = $config_text;
		$this->template = $template;
		$this->language = $language;

		$this->init();
	}

	private function init()
	{
		$input_settings = unserialize($this->config_text->get('marttiphpbb_calendarinput_input'));
		$this->input_settings = is_array($input_settings) ? $input_settings : $this->input_settings_default;		
	}

	/*
	 * @return self
	 */
	public function assign_template_vars():self
	{
		$template_vars = [];

		foreach ($this->input_settings_default as $key => $value)
		{
			if ($key & $input_settings)
			{
				$template_vars['S_' . $value] = true;
			}
		}

		$this->template->assign_vars($template_vars);

		return $this;
	}

	/*
	 * @return self
	 */
	public function assign_acp_template_vars():self
	{
		$template_vars = array_change_key_case($this->get_days(), CASE_UPPER);

		$this->template->assign_vars($template_vars);

		return $this;
	}

	/*
	 * @param array		$input_settings
	 * @return self
	 */
	public function set(array $input_settings):self
	{
		$this->input_settings = array_merge($this->input_settings, $input_settings);

		$this->config_text->set('marttiphpbb_calendarinput_input', serialize($this->input_settings));

		return $this;
	}

	/*
	 * @return array
	 */
	public function get():array
	{
		$ary = array();

		foreach ($this->input_settings_default as $key => $value)
		{
			$ary[$key] = isset($this->input_settings[$key]) ? $this->input_settings[$key] : $value;
		}

		return $ary;
	}

	/*
	 * @param array		$input_settings
	 * @return self
	 */
	public function set_days(array $input_settings):self
	{
		foreach ($input_settings as $key => $days)
		{
			$seconds = $days * 86400;
			$this->input_settings[str_replace('_days', '', $key)] = $seconds;
		}

		$this->config_text->set('marttiphpbb_calendarinput_input', serialize($this->input_settings));

		return $this;
	}

	/*
	 * @return array
	 */
	public function get_days():array
	{
		$ary = array();

		foreach ($this->input_settings_default as $key => $value)
		{
			$seconds = isset($this->input_settings[$key]) ? $this->input_settings[$key] : $value;
			$ary[$key . '_days'] = floor($seconds / 86400);
		}

		return $ary;
	}	

	/*
	 * @return array
	 */
	public function get_forums()
	{
		return is_array($this->input_settings['forums']) ? $this->input_settings['forums'] : [];
	}

	/*
	 * @param array forums
	 */
	public function set_forums(array $forum_ary)
	{
		$this->input_settings['forums'] = $forum_ary;
		$this->config_text->set('marttiphpbb_calendarinput_input', serialize($this->input_settings));
		return $this;
	}

	/*
	 * @param int $forum_id
	 * @return boolean
	 */
	public function get_enabled(int $forum_id)
	{
		return isset($this->input_settings['forums'][$forum_id]['enabled']) ? true : false;
	}

	/*
	 * @param int $forum_id
	 * @return boolean
	 */
	public function get_required(int $forum_id)
	{
		return isset($this->input_settings['forums'][$forum_id]['required']) ? true : false;
	}
}
