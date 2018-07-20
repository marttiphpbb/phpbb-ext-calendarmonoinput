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
			case 'input_range':

				$this->tpl_name = 'input_range';
				$this->page_title = $language->lang('ACP_CALENDARINPUT_INPUT_RANGE');

				$input_range = $phpbb_container->get('marttiphpbb.calendarinput.render.input_range');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendarinput'))
					{
						trigger_error('FORM_INVALID');
					}

					$settings->set_lower_limit_days($request->variable('lower_limit_days', 0));
					$settings->set_upper_limit_days($request->variable('upper_limit_days', 0));
					$settings->set_min_duration_days($request->variable('min_duration_days', 0));
					$settings->set_max_duration_days($request->variable('max_duration_days', 0));

					trigger_error($language->lang('ACP_CALENDARINPUT_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$input_range->assign_template_vars();

			break;

			case 'input_format':

				$this->tpl_name = 'input_format';
				$this->page_title = $language->lang('ACP_CALENDARINPUT_INPUT_FORMAT');

				$input_range = $phpbb_container->get('marttiphpbb.calendarinput.render.input_range');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendarinput'))
					{
						trigger_error('FORM_INVALID');
					}

					$settings->set_lower_limit_days($request->variable('lower_limit_days', 0));
					$settings->set_upper_limit_days($request->variable('upper_limit_days', 0));
					$settings->set_min_duration_days($request->variable('min_duration_days', 0));
					$settings->set_max_duration_days($request->variable('max_duration_days', 0));

					trigger_error($language->lang('ACP_CALENDARINPUT_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$input_range->assign_template_vars();

			break;

			case 'input_forums':

				$this->tpl_name = 'input_forums';
				$this->page_title = $language->lang('ACP_CALENDARINPUT_INPUT_FORUMS');

				$cforums = make_forum_select(false, false, false, false, true, false, true);

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendarinput'))
					{
						trigger_error('FORM_INVALID');
					}

					$enabled_ary = $request->variable('enabled', [0 => 0]);
					$required_ary = $request->variable('required', [0 => 0]);

					foreach ($cforums as $forum)
					{
						$forum_id = $forum['forum_id'];

						$settings->set_enabled($forum_id, isset($enabled_ary[$forum_id]));
						$settings->set_required($forum_id, isset($required_ary[$forum_id]));
					}

					trigger_error($language->lang('ACP_CALENDARINPUT_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				if (sizeof($cforums))
				{
					foreach ($cforums as $forum)
					{
						$forum_id = $forum['forum_id'];

						$template->assign_block_vars('cforums', [
							'NAME'		=> $forum['padding'] . $forum['forum_name'],
							'ID'		=> $forum_id,
							'ENABLED'	=> $settings->get_enabled($forum_id),
							'REQUIRED'	=> $settings->get_required($forum_id),
						]);
					}
				}

			break;
		}

		$template->assign_var('U_ACTION', $this->u_action);
	}
}
