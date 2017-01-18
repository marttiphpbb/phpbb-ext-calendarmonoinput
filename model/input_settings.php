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

	protected $granularity_ary = [60, 300, 600, 900, 1800, 3600, 86400];

	protected $input_settings_default = [
		'max_event_count'	=> 1,
		'required'			=> 0,
		'granularity'		=> 900,
		'default_time'		=> 43200,
		'lower_limit'		=> 0,
		'upper_limit'		=> 31536000,
		'default_duration'	=> 0,
		'fixed_duration'	=> 0,
		'min_duration'		=> 1800,
		'max_duration'		=> 14400,
/*		'min_gap'			=> 43200,
		'max_gap'			=> 86400, */
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
		$this->input_settings = (is_array($input_settings)) ? $input_settings : [];
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

		foreach ($this->granularity_ary as $seconds)
		{	
			$this->template->assign_block_vars('granularity', [
				'VALUE'		=> $seconds,
				'SELECTED'	=> isset($template_vars['GRANULARITY']) && $template_vars['GRANULARITY'] == $seconds ? true : false,
				'OPTION'	=> $this->language->lang(['ACP_CALENDAR_GRANULARITY_OPTIONS', $seconds]),
			]);
		}		

		return $this;
	}

	/*
	 * @param array		$input_settings
	 * @param int		$forum_id
	 * @return links
	 */
	public function set($input_settings, $forum_id = null)
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
	public function get($forum_id = null)
	{
		$ary = array();

		foreach ($this->input_settings_default as $key => $value)
		{
			$v = (isset($input_settings[$key])) ? $input_settings[$key] : $value;
			$v = (isset($this->input_settings['forums'][$forum_id][$key])) ? $this->input_settings['forums'][$forum_id][$key] : $v;
		
			$ary[$key] = $v;
		}

		return $ary;
	}
}
