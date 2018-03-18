<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2017 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\migrations;

class v_0_1_0 extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		$input_settings = [
			'lower_limit'		=> 0,
			'upper_limit'		=> 720,
			'min_duration'		=> 0,
			'max_duration'		=> 30,
			'forums'			=> [],
		];

		return [

			['config_text.add', ['marttiphpbb_calendar_input', serialize($input_settings)]],

			['config.add', ['calendar_first_weekday', 0]],
			['config.add', ['calendar_links', 2]],
			['config.add', ['calendar_include_assets', 3]],
			['config.add', ['calendar_datepicker_theme', 'smoothness']],
			['config.add', ['calendar_render_settings', 7]],
			['config.add', ['calendar_min_rows', 5]],

			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_CALENDAR'
			]],
			['module.add', [
				'acp',
				'ACP_CALENDAR',
				[
					'module_basename'	=> '\marttiphpbb\calendar\acp\main_module',
					'modes'				=> [
						'links',
						'page_rendering',
						'input',
						'input_forums',
						'include_assets',
					],
				],
			]],
		];
	}

	public function update_schema()
	{
		return [
			'add_columns'        => [
				$this->table_prefix . 'topics'        => [
					'topic_calendar_start_day'  		=> ['UINT', NULL],
					'topic_calendar_end_day' 			=> ['UINT', NULL],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'        => [
				$this->table_prefix . 'topics'        => [
					'topic_calendar_start_day',
					'topic_calendar_end_day',
				],
			],
		];
	}
}
