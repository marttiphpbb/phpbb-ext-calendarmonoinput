<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2015 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\model;

use phpbb\config\config;
use phpbb\template\template;
use phpbb\user;

class rendering
{

	/* @var config */
	protected $config;

	/* @var template */
	protected $template;

	/* @var user */
	protected $user;

	protected $rendering = array(
		1		=> 'ISOWEEK',
		2		=> 'MOONPHASE',
		4		=> 'TODAY',
	);

	/**
	* @param config		$config
	* @param template	$template
	* @param user		$user
	* @return rendering
	*/
	public function __construct(
		config $config,
		template $template,
		user $user
	)
	{
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
	}

	/*
	 * @return rendering
	 */
	public function assign_template_vars()
	{
		$rendering_enabled = $this->config['calendar_rendering'];
		$template_vars = array();

		foreach ($this->rendering as $key => $value)
		{
			if ($key & $links_enabled)
			{
				$template_vars['S_' . $value] = true;
			}
		}

		$this->template->assign_vars($template_vars);
		return $this;
	}

	/*
	 * @return rendering
	 */
	public function assign_acp_template_vars()
	{
		$rendering_enabled = $this->config['calendar_rendering'];

		$return_ary = array();

		foreach ($this->rendering as $key => $value)
		{
			$explain_key = 'ACP_CALENDAR_' . $value . '_EXPLAIN';
			$explain = (isset($this->user->lang[$explain_key])) ? $this->user->lang[$explain_key] : '';

			$this->template->assign_block_vars('rendering', array(
				'VALUE'			=> $key,
				'S_CHECKED'		=> ($key & $rendering_enabled) ? true : false,
				'LABEL'			=> $this->user->lang('ACP_CALENDAR_' . $value),
				'EXPLAIN'		=> $explain,
			));
		}
		return $this;
	}

	/*
	 * @param array		$links
	 * @param int		$repo_link
	 * @return links
	 */
	public function set($rendering)
	{
		$this->config->set('calendar_rendering', array_sum($rendering));
		return $this;
	}
}
