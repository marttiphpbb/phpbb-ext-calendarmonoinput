<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\render;

use phpbb\template\template;
use phpbb\language\language;
use marttiphpbb\calendarinput\repository\settings;

class include_assets
{
	/** @var settings */
	private $settings;

	/** @var template */
	private $template;

	/** @var language */
	private $language;

	/** @var string */
	private $phpbb_root_path;

	/** @var string */
	private $dir = 'ext/marttiphpbb/calendarinput/styles/all/template/jquery-ui/themes';

	/**
	 * @param settings 	$settings
	* @param template	$template
	* @param language	$language
	* @param string 	$phpbb_root_path
	*/
	public function __construct(
		settings $settings,
		template $template,
		language $language,
		string $phpbb_root_path
	)
	{
		$this->settings = $settings;
		$this->template = $template;
		$this->language = $language;
		$this->phpbb_root_path = $phpbb_root_path;
	}

	public function assign_template_vars()
	{
		$datepicker_theme = $this->settings->get_datepicker_theme();

		if ($datepicker_theme === 'none')
		{
			$datepicker_theme = false;
		}

		$this->template->assign_vars([
			'S_CALENDARINPUT_JQUERY_UI_DATPICKER'		=> $this->settings->get_include_jquery_ui_datepicker(),
			'S_CALENDARINPUT_JQUERY_UI_DATPICKER_I18N'	=> $this->settings->get_include_jquery_ui_datepicker_i18n(),
			'CALENDARINPUT_DATEPICKER_THEME'			=> $datepicker_theme,
		]);
	}

	public function assign_acp_select_template_vars()
	{
		$this->assign_template_vars();

		$datepicker_theme = $this->settings->get_datepicker_theme();

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
	}
}
