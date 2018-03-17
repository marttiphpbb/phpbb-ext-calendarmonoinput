<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2017 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\acp;

class main_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $template, $request;
		global $config, $phpbb_root_path;
		global $phpbb_container;

		$language = $phpbb_container->get('language');
		$language->add_lang('acp', 'marttiphpbb/calendar');
		add_form_key('marttiphpbb/calendar');

		switch($mode)
		{
			case 'links':

				$links = $phpbb_container->get('marttiphpbb.calendar.render.links');

				$this->tpl_name = 'links';
				$this->page_title = $language->lang('ACP_CALENDAR_LINKS');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendar'))
					{
						trigger_error('FORM_INVALID');
					}

					$links->set($request->variable('links', [0 => 0]), $request->variable('calendar_repo_link', 0));

					trigger_error($language->lang('ACP_CALENDAR_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$links->assign_acp_select_template_vars();

				$template->assign_vars([
					'U_ACTION'				=> $this->u_action,
				]);

				break;

			case 'page_rendering':

				$render_settings = $phpbb_container->get('marttiphpbb.calendar.render.render_settings');

				$this->tpl_name = 'page_rendering';
				$this->page_title = $language->lang('ACP_CALENDAR_PAGE_RENDERING');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendar'))
					{
						trigger_error('FORM_INVALID');
					}

					$render_settings->set($request->variable('render_settings', [0 => 0]));
					$config->set('calendar_first_weekday', $request->variable('calendar_first_weekday', 0));
					$config->set('calendar_min_rows', $request->variable('calendar_min_rows', 5));

					trigger_error($language->lang('ACP_CALENDAR_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$weekdays = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

				foreach ($weekdays as $value => $name)
				{
					$template->assign_block_vars('weekdays', [
						'VALUE'			=> $value,
						'S_SELECTED'	=> $config['calendar_first_weekday'] == $value ? true : false,
						'LANG'			=> $language->lang(['datetime', $name]),
					]);
				}

				$render_settings->assign_acp_template_vars();

				$template->assign_vars([
					'CALENDAR_MIN_ROWS'		=> $config['calendar_min_rows'],
					'U_ACTION'				=> $this->u_action,
				]);

				break;

			case 'input':

				$this->tpl_name = 'input';
				$this->page_title = $language->lang('ACP_CALENDAR_INPUT');

				$input_settings = $phpbb_container->get('marttiphpbb.calendar.render.input_settings');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendar'))
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

					trigger_error($language->lang('ACP_CALENDAR_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$input_settings->assign_acp_template_vars();

				$template->assign_vars([
					'U_ACTION'		=> $this->u_action,
				]);

				break;

			case 'input_forums':

				$this->tpl_name = 'input_forums';
				$this->page_title = $language->lang('ACP_CALENDAR_INPUT_FORUMS');

				$input_settings = $phpbb_container->get('marttiphpbb.calendar.render.input_settings');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendar'))
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

					trigger_error($language->lang('ACP_CALENDAR_SETTING_SAVED') . adm_back_link($this->u_action));
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

				$template->assign_vars([
					'U_ACTION'		=> $this->u_action,
				]);

				break;

			case 'include_assets':

				$include_assets = $phpbb_container->get('marttiphpbb.calendar.render.include_assets');
	
				$this->tpl_name = 'include_assets';
				$this->page_title = $language->lang('ACP_CALENDAR_INCLUDE_ASSETS');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendar'))
					{
						trigger_error('FORM_INVALID');
					}

					$include_assets->set($request->variable('include_assets', [0 => 0]));
					$config->set('calendar_datepicker_theme', $request->variable('calendar_datepicker_theme', ''));

					trigger_error($language->lang('ACP_CALENDAR_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$include_assets->assign_acp_select_template_vars();
				
				$template->assign_vars([
					'U_ACTION'		=> $this->u_action,
				]);

				break;
		}
	}
}
