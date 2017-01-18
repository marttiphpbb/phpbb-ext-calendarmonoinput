<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2016 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\model;

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
		'lower_limit_days'	=> 0,
		'upper_limit_days'	=> 365,
		'min_duration_days'	=> 1,
		'max_duration_days'	=> 30,
	];

	/**
	* @param config		$config
	* @param config_text		$config_text
	* @param template	$template
	* @param language		$language
	* @return input_settings
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

		$input_settings = unserialize($this->config_text->get('marttiphpbb_calendar_input'));
		$this->input_settings = is_array($input_settings) ? $input_settings : [];
	}

	/*
	 * @return input_settings
	 */
	public function assign_template_vars()
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
	 * @param int $forum_id (default input settings when forum_id = null)
	 * @return input_settings
	 */
	public function assign_acp_template_vars($forum_id = null)
	{
		$template_vars = array_change_key_case($this->get($forum_id), CASE_UPPER);

		$this->template->assign_vars($template_vars);

		return $this;
	}

	/*
	 * @param array		$input_settings
	 * @param int		$forum_id
	 * @return links
	 */
	public function set(array $input_settings, $forum_id = null)
	{
		if (isset($forum_id))
		{
			$this->input_settings[$forum_id] = array_merge($this->input_settings[$forum_id], $input_settings);
		}
		else
		{
			$this->input_settings = array_merge($this->input_settings, $input_settings);
		}

		$this->config_text->set('marttiphpbb_calendar_input', serialize($this->input_settings));

		return $this;
	}

	/*
	 * @return array
	 */
	public function get()
	{
		$ary = array();

		foreach ($this->input_settings_default as $key => $value)
		{
			$ary[$key] = isset($input_settings[$key]) ? $input_settings[$key] : $value;
		}

		return $ary;
	}
}
