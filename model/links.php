<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\model;

use phpbb\config\config;
use phpbb\template\template;

class links
{

	/* @var config */
	protected $config;

	/* @var template */
	protected $template;

	protected $links = array(
		1		=> 'OVERALL_FOOTER_COPYRIGHT_APPEND',
		2		=> 'OVERALL_HEADER_NAVIGATION_PREPEND',
		4		=> 'OVERALL_HEADER_NAVIGATION_APPEND',
		8		=> 'NAVBAR_HEADER_QUICK_LINKS_BEFORE',
		16		=> 'NAVBAR_HEADER_QUICK_LINKS_AFTER',
		32		=> 'OVERALL_HEADER_BREADCRUMBS_BEFORE',
		64		=> 'OVERALL_HEADER_BREADCRUMBS_AFTER',
		128		=> 'OVERALL_FOOTER_TIMEZONE_BEFORE',
		256		=> 'OVERALL_FOOTER_TIMEZONE_AFTER',
		512		=> 'OVERALL_FOOTER_TEAMLINK_BEFORE',
		1024	=> 'OVERALL_FOOTER_TEAMLINK_AFTER',
	);

	/**
	* @param config		$config
	* @param template	$template
	*/
	public function __construct(
		config $config,
		template $template
	)
	{
		$this->config = $config;
		$this->template = $template;
	}

	public function assign_template_vars()
	{
		$links_enabled = $this->config['calendar_links'];
		$template_vars = array();

		foreach ($this->links as $key => $value)
		{
			if ($key & $links_enabled)
			{
				$template_vars['S_CALENDAR_' . $value] = true;
			}
		}

		$this->template->assign_vars($template_vars);
		return $this;
	}

	public function assign_acp_select_template_vars()
	{
		$links_enabled = $this->config['calendar_links'];

		$return_ary = array();

		foreach ($this->links as $key => $value)
		{
			$this->template->assign_block_vars('links', array(
				'value'			=> $key,
				'selected'		=> ($key & $links_enabled), 
				'lang'			=> 'L_CALENDAR_' . $value,
			));
		}
		return $this;
	}

	public function set($links)
	{
		$this->config->set('calendar_links', array_sum($links));
		return $this;
	}
}
