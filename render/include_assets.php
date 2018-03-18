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

class include_assets
{
	/* @var config */
	protected $config;

	/* @var template */
	protected $template;

	/* @var language */
	protected $language;

	/* @var string */
	private $phpbb_root_path;

	/* @var string */
	private $dir = 'ext/marttiphpbb/calendarinput/styles/all/template/jquery-ui/themes';

	/* */
	protected $include_assets = [
		1		=> 'JQUERY_UI_DATEPICKER_JS',
		2		=> 'JQUERY_UI_DATEPICKER_I18N_JS',
	];

	/**
	* @param config		$config
	* @param template	$template
	* @param language	$language
	* @param string 	$phpbb_root_path
	* @return links
	*/
	public function __construct(
		config $config,
		template $template,
		language $language,
		string $phpbb_root_path
	)
	{
		$this->config = $config;
		$this->template = $template;
		$this->language = $language;
		$this->phpbb_root_path = $phpbb_root_path;
	}

	/*
	 * @return self
	 */
	public function assign_template_vars():self
	{
		$include_assets_enabled = $this->config['calendarinput_include_assets'];
		$template_vars = [];

		foreach ($this->include_assets as $key => $value)
		{
			if ($key & $include_assets_enabled)
			{
				$template_vars['S_CALENDARINPUT_' . $value] = true;
			}
		}

		$this->template->assign_vars($template_vars);
		return $this;
	}

	/*
	 * @return self
	 */
	public function assign_acp_select_template_vars():self
	{
		$include_assets_enabled = $this->config['calendarinput_include_assets'];

		foreach ($this->include_assets as $key => $value)
		{
			$this->template->assign_block_vars('include_assets', [
				'VALUE'			=> $key,
				'S_CHECKED'		=> $key & $include_assets_enabled ? true : false,
				'LABEL'			=> $this->language->lang('ACP_CALENDARINPUT_' . $value),
				'EXPLAIN'		=> $this->language->lang('ACP_CALENDARINPUT_' . $value . '_EXPLAIN'),
			]);
		}

		$datepicker_theme = trim($this->config['calendarinput_datepicker_theme']);

		$this->template->assign_block_vars('datepicker_themes', [
			'VALUE'			=> 'none',
			'LANG'			=> $this->language->lang('ACP_CALENDARINPUT_DATEPICKER_THEME_NONE'),
			'S_SELECTED'	=> $datepicker_theme == 'none' ? true : false,
		]);

		$scanned = @scandir($this->phpbb_root_path . $this->dir);

		if ($scanned === false)
		{
			trigger_error(sprintf($this->language->lang('ACP_CALENDARINPUT_DIRECTORY_LIST_FAIL'), $this->dir), E_USER_WARNING);
		}

		$scanned = array_diff($scanned, ['.', '..', '.htaccess']);

		$scanned = [] + $scanned;

		foreach ($scanned as $dirname)
		{
			trim($dirname);

			if (!is_dir($this->phpbb_root_path . $this->dir . '/' . $dirname))
			{
				continue;
			}

			$this->template->assign_block_vars('datepicker_themes', [
				'VALUE'			=> $dirname,
				'LANG'			=> $dirname,
				'S_SELECTED'	=> $datepicker_theme == $dirname ? true : false,
			]);
		}

		return $this;
	}

	/*
	 * @param array		$include_assets
	 * @return self
	 */
	public function set($include_assets):self
	{
		$this->config->set('calendarinput_include_assets', array_sum($include_assets));
		return $this;
	}
}
