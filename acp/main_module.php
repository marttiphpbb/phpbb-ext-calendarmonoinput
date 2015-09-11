<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2015 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\acp;

use marttiphpbb\calendar\model\links;
use marttiphpbb\calendar\model\rendering;

class main_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $user, $auth, $template, $cache, $request;
		global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;

		$user->add_lang_ext('marttiphpbb/calendar', 'acp');
		add_form_key('marttiphpbb/calendar');

		$links = new links($config, $template, $user);
		$rendering = new rendering($config, $template, $user);

		switch($mode)
		{
			case 'rendering':

				$this->tpl_name = 'rendering';
				$this->page_title = $user->lang('ACP_CALENDAR_RENDERING');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendar'))
					{
						trigger_error('FORM_INVALID');
					}

					$links->set($request->variable('links', array(0 => 0)), $request->variable('calendar_repo_link', 0));
					$rendering->set($request->variable('rendering', array(0 => 0)));
					$config->set('calendar_first_weekday', $request->variable('calendar_first_weekday', 0));

					trigger_error($user->lang('ACP_CALENDAR_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$weekdays = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

				foreach ($weekdays as $value => $name)
				{
					$template->assign_block_vars('weekdays', array(
						'VALUE'		=> $value,
						'SELECTED'	=> ($config['calendar_first_weekday'] == $value) ? true : false,
						'LANG'		=> $user->lang['datetime'][$name],
					));
				}

				$links->assign_acp_select_template_vars();
				$rendering->assign_acp_template_vars();

				$template->assign_vars(array(
					'U_ACTION'		=> $this->u_action,
				));

				break;

			case 'input':

				$this->tpl_name = 'input';
				$this->page_title = $user->lang('ACP_CALENDAR_INPUT');

				if ($request->is_set_post('submit'))
				{
					if (!check_form_key('marttiphpbb/calendar'))
					{
						trigger_error('FORM_INVALID');
					}

					$config->set('calendar_granularity_minutes', $request->variable('calendar_granularity_minutes', 15));
					$config->set('calendar_max_periods', $request->variable('calendar_max_periods', 1));
					$config->set('calendar_max_period_hours', $request->variable('calendar_max_period_hours', 60));
					$config->set('calendar_min_limit_hours', $request->variable('calendar_min_limit_hours', -10));
					$config->set('calendar_max_limit_hours', $request->variable('calendar_max_limit_hours', 700));
					$config->set('calendar_min_gap_hours', $request->variable('calendar_min_gap_hours', 1));
					$config->set('calendar_max_gap_hours', $request->variable('calendar_max_gap_hours', 80));

					trigger_error($user->lang('ACP_CALENDAR_SETTING_SAVED') . adm_back_link($this->u_action));
				}

				$granularity_ary = $user->lang['ACP_CALENDAR_GRANULARITY_OPTIONS'];
				$granularity_ary = (is_array($granularity_ary)) ? $granularity_ary : array();
				$granularity_options = '';

				foreach ($granularity_ary as $key => $option)
				{
					$granularity_options .= '<option value="'.$key.'"';
					$granularity_options .= ($key == $config['calendar_granularity_minutes']) ? ' selected="selected"' : '';
					$granularity_options .= '>'.$option.'</option>';
				}

				$template->assign_vars(array(
					'U_ACTION'							=> $this->u_action,

					'S_CALENDAR_GRANULARITY_OPTIONS'	=> $granularity_options,
					'CALENDAR_MAX_PERIODS'				=> $config['calendar_max_periods'],
					'CALENDAR_MAX_PERIOD_HOURS'			=> $config['calendar_max_period_hours'],
					'CALENDAR_MIN_LIMIT_HOURS' 			=> $config['calendar_min_limit_hours'],
					'CALENDAR_MAX_LIMIT_HOURS' 			=> $config['calendar_max_limit_hours'],
					'CALENDAR_MIN_GAP_HOURS' 			=> $config['calendar_min_gap_hours'],
					'CALENDAR_MAX_GAP_HOURS' 			=> $config['calendara_max_gap_hours'],
				));

				break;
		}
	}
}
