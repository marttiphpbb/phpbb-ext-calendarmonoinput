<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2016 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\acp;

use marttiphpbb\calendar\model\links;
use marttiphpbb\calendar\model\include_assets;
use marttiphpbb\calendar\model\render_settings;
use marttiphpbb\calendar\model\input_settings;

class main_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
		global $phpbb_container;

		$language = $phpbb_container->get('language');
		$language->add_lang('acp', 'marttiphpbb/calendar');
		add_form_key('marttiphpbb/calendar');

		switch($mode)
		{
			case 'rendering':

				$links = new links($config, $template, $language);
				$render_settings = new render_settings($config, $template, $language);

				$this->tpl_name = 'rendering';
				$this->page_title = $language->lang('ACP_CALENDAR_RENDERING');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendar'))
					{
						trigger_error('FORM_INVALID');
					}

					$links->set($request->variable('links', [0 => 0]), $request->variable('calendar_repo_link', 0));
					$render_settings->set($request->variable('render_settings', [0 => 0]));
					$config->set('calendar_first_weekday', $request->variable('calendar_first_weekday', 0));

					trigger_error($language->lang('ACP_CALENDAR_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$weekdays = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

				foreach ($weekdays as $value => $name)
				{
					$template->assign_block_vars('weekdays', [
						'VALUE'			=> $value,
						'S_SELECTED'	=> ($config['calendar_first_weekday'] == $value) ? true : false,
						'LANG'			=> $language->lang(['datetime', $name]),
					]);
				}

				$links->assign_acp_select_template_vars();
				$render_settings->assign_acp_template_vars();

				$template->assign_vars([
					'U_ACTION'		=> $this->u_action,
				]);

				break;

			case 'input':

				$this->tpl_name = 'input';
				$this->page_title = $language->lang('ACP_CALENDAR_INPUT');

				$input_settings = $phpbb_container->get('marttiphpbb.calendar.model.input_settings');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendar'))
					{
						trigger_error('FORM_INVALID');
					}

					$input_names = array_keys($input_settings->get());

					$set_ary = [];

					foreach ($input_names as $name)
					{
						$set_ary[$name] = $request->variable($name, 0);
					}

					$input_settings->set($set_ary);

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

				$input_settings = $phpbb_container->get('marttiphpbb.calendar.model.input_settings');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendar'))
					{
						trigger_error('FORM_INVALID');
					}

					$input_names = array_keys($input_settings->get());

					$set_ary = [];

					foreach ($input_names as $name)
					{
						$set_ary[$name] = $request->variable($name, 0);
					}

					$input_settings->set($set_ary);

					trigger_error($language->lang('ACP_CALENDAR_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$cforums = make_forum_select(false, false, false, false, true, false, true);

	//			var_dump($cforums);

	//			var_dump($input = $input_settings->get());

				if (sizeof($cforums))
				{
					foreach ($cforums as $forum)
					{
						$forum_id = $forum['forum_id'];
						$enabled = isset($input['forums'][$forum_id]['enabled']) && $input['forums'][$forum_id]['enabled'] ? true : false;
						$enabled = isset($input['forums'][$forum_id]['required']) && $input['forums'][$forum_id]['required'] ? true : false;

						$template->assign_block_vars('cforums', [
							'NAME'		=> $forum['padding'] . $forum['forum_name'],
							'ID'		=> $forum_id,
							'ENABLED'	=> $enabled,
							'REQUIRED'	=> $required,
						]);
					}
				}

				$input_settings->assign_acp_template_vars();

				$template->assign_vars([
					'U_ACTION'		=> $this->u_action,
				]);

				break;

			case 'include_assets':

				$include_assets = new include_assets($config, $template, $language, $phpbb_root_path);

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
