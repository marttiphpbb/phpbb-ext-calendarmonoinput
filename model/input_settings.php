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
use phpbb\user;

class input_settings
{

	/* @var config */
	protected $config;

	/* @var config_text */
	protected $config_text;

	/* @var template */
	protected $template;

	/* @var user */
	protected $user;

	/* @var user */
	protected $input_settings;

	protected $input_settings_default = array(
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
		'min_gap'			=> 43200,
		'max_gap'			=> 86400,
	);

	/**
	* @param config		$config
	* @param config_text		$config_text
	* @param template	$template
	* @param user		$user
	* @return input_settings
	*/
	public function __construct(
		config $config,
		config_text $config_text,
		template $template,
		user $user
	)
	{
		$this->config = $config;
		$this->config_text = $config_text;
		$this->template = $template;
		$this->user = $user;

		$input_settings = unserialize($this->config_text->get('marttiphpbb_calendar_input'));
		$this->input_settings = (is_array($input_settings)) ? $input_settings : array();
	}

	/*
	 * @return input_settings
	 */
	public function assign_template_vars()
	{
		$template_vars = array();

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

		$granularity_ary = $this->user->lang['ACP_CALENDAR_GRANULARITY_OPTIONS'];
		$granularity_ary = (is_array($granularity_ary)) ? $granularity_ary : array();

		foreach ($granularity_ary as $key => $option)
		{	
			$this->template->assign_block_vars('granularity', array(
				'VALUE'		=> $key,
				'SELECTED'	=> (isset($template_vars['GRANULARITY']) && $template_vars['GRANULARITY'] == $key) ? true : false,
				'OPTION'	=> $option,
			));
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
