<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2016 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\model;

use phpbb\config\config;
use phpbb\template\template;
use phpbb\user;

class include_files
{

	/* @var config */
	protected $config;

	/* @var template */
	protected $template;

	/* @var user */
	protected $user;

	/* @var string */
	private $phpbb_root_path;

	/* @var string */
	private $dir = 'ext/marttiphpbb/calendar/styles/all/template/jquery-ui/themes';

	/* */
	protected $include_files = array(
		1		=> 'JQUERY_UI_DATEPICKER_JS',
		2		=> 'JQUERY_UI_DATEPICKER_I18N_JS',
	);

	/**
	* @param config		$config
	* @param template	$template
	* @param user		$user
	* @param string 	$phpbb_root_path
	* @return links
	*/
	public function __construct(
		config $config,
		template $template,
		user $user,
		$phpbb_root_path
	)
	{
		$this->config = $config;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;
	}

	/*
	 * @return links
	 */
	public function assign_template_vars()
	{
		$include_files_enabled = $this->config['calendar_include_files'];
		$template_vars = array();

		foreach ($this->include_files as $key => $value)
		{
			if ($key & $include_files_enabled)
			{
				$template_vars['S_CALENDAR_' . $value] = true;
			}
		}

		$this->template->assign_vars($template_vars);
		return $this;
	}

	/*
	 * @return include_files
	 */
	public function assign_acp_select_template_vars()
	{
		$include_files_enabled = $this->config['calendar_include_files'];

		foreach ($this->include_files as $key => $value)
		{
			$explain_key = 'ACP_CALENDAR_' . $value . '_EXPLAIN';
			$explain = (isset($this->user->lang[$explain_key])) ? $this->user->lang[$explain_key] : '';

			$this->template->assign_block_vars('include_files', array(
				'VALUE'			=> $key,
				'S_CHECKED'		=> ($key & $include_files_enabled) ? true : false,
				'LABEL'			=> $this->user->lang('ACP_CALENDAR_' . $value),
				'EXPLAIN'		=> $explain,
			));
		}

		$jquery_ui_theme = trim($this->config['calendar_jquery_ui_theme']);

		$scanned = @scandir($this->phpbb_root_path . $this->dir);

		if ($scanned === false)
		{
			trigger_error(sprintf($this->user->lang('ACP_CALENDAR_DIRECTORY_LIST_FAIL'), $this->dir), E_USER_WARNING);
		}

		$scanned = array_diff($scanned, array('.', '..', '.htaccess'));

		$scanned = array('') + $scanned;

		foreach ($scanned as $dirname)
		{
			trim($dirname);

			if (!is_dir($this->phpbb_root_path . $this->dir . '/' . $dirname))
			{
				continue;
			}

			$this->template->assign_block_vars('jquery_ui_themes', array(
				'VALUE'			=> $dirname,
				'LANG'			=> $dirname,
				'S_SELECTED'	=> ($jquery_ui_theme == $dirname) ? true : false,
			));
		}

		return $this;
	}

	/*
	 * @param array		$include_files
	 * @return links
	 */
	public function set($include_files)
	{
		$this->config->set('calendar_include_files', array_sum($include_files));
		return $this;
	}
}
