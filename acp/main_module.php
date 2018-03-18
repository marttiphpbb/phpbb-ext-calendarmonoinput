<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\acp;

class main_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $template, $request;
		global $config, $phpbb_root_path;
		global $phpbb_container;

		$language = $phpbb_container->get('language');
		$language->add_lang('acp', 'marttiphpbb/calendarinput');
		add_form_key('marttiphpbb/calendarinput');

		$settings = $phpbb_container->get('marttiphpbb.calendarinput.repository.settings');

		switch($mode)
		{
			case 'input':

				$this->tpl_name = 'input';
				$this->page_title = $language->lang('ACP_CALENDARINPUT_INPUT');

				$input_settings = $phpbb_container->get('marttiphpbb.calendarinput.render.input_settings');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendarinput'))
					{
						trigger_error('FORM_INVALID');
					}

					$input_names = array_keys($input_settings->get_days());

					$set_ary = [];

					foreach ($input_names as $name)
					{
						$set_ary[$name] = $request->variable($name, 0);
					}

					$input_settings->set_days($set_ary);

					trigger_error($language->lang('ACP_CALENDARINPUT_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$input_settings->assign_acp_template_vars();

				break;

			case 'input_forums':

				$this->tpl_name = 'input_forums';
				$this->page_title = $language->lang('ACP_CALENDARINPUT_INPUT_FORUMS');

				$input_settings = $phpbb_container->get('marttiphpbb.calendarinput.render.input_settings');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendarinput'))
					{
						trigger_error('FORM_INVALID');
					}

					$enabled_ary = $request->variable('enabled', [0 => 0]);
					$required_ary = $request->variable('required', [0 => 0]);

					$forum_ary = [];

					foreach ($enabled_ary as $fid)
					{
						$forum_ary[$fid]['enabled'] = true;
					}

					foreach ($required_ary as $fid)
					{
						$forum_ary[$fid]['required'] = true;
					}

					$input_settings->set_forums($forum_ary);

					trigger_error($language->lang('ACP_CALENDARINPUT_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$input_ary = $input_settings->get_forums();

				$cforums = make_forum_select(false, false, false, false, true, false, true);

				if (sizeof($cforums))
				{
					foreach ($cforums as $forum)
					{
						$forum_id = $forum['forum_id'];

						$template->assign_block_vars('cforums', [
							'NAME'		=> $forum['padding'] . $forum['forum_name'],
							'ID'		=> $forum_id,
							'ENABLED'	=> isset($input_ary[$forum_id]['enabled']) ? true : false,
							'REQUIRED'	=> isset($input_ary[$forum_id]['required']) ? true : false,
						]);
					}
				}

				$input_settings->assign_acp_template_vars();

				break;

			case 'include_assets':

				$include_assets = $phpbb_container->get('marttiphpbb.calendarinput.render.include_assets');
	
				$this->tpl_name = 'include_assets';
				$this->page_title = $language->lang('ACP_CALENDARINPUT_INCLUDE_ASSETS');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendarinput'))
					{
						trigger_error('FORM_INVALID');
					}

					$settings->set_include_jquery_ui_datepicker($request->variable('include_jquery_ui_datepicker', false));
					$settings->set_include_jquery_ui_datepicker_i18n($request->variable('include_jquery_ui_datepicker_i18n', false));
					$settings->set_datepicker_theme($request->variable('datepicker_theme', ''));

					trigger_error($language->lang('ACP_CALENDARINPUT_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$include_assets->assign_acp_select_template_vars();
		
				break;

			case 'repo_link':

				$this->tpl_name = 'repo_link';
				$this->page_title = $language->lang('ACP_CALENDARINPUT_REPO_LINK_MENU');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendarinput'))
					{
						trigger_error('FORM_INVALID');
					}

					$config->set('marttiphpbb_calendarinput_repo_link', $request->variable('calendar_repo_link', '0'));

					trigger_error($language->lang('ACP_CALENDARINPUT_SETTING_SAVED') . adm_back_link($this->u_action));
				}
				
				$template->assign_var('S_CALENDAR_REPO_LINK', $config['marttiphpbb_calendarinput_repo_link']);

				break;
		}

		$template->assign_var('U_ACTION', $this->u_action);
	}
}
