<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\migrations;

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

			['config_text.add', ['marttiphpbb_calendarinput_input', serialize($input_settings)]],

			['config.add', ['calendarinput_first_weekday', 0]],
			['config.add', ['calendarinput_links', 2]],
			['config.add', ['calendarinput_include_assets', 3]],
			['config.add', ['calendarinput_datepicker_theme', 'smoothness']],
			['config.add', ['calendarinput_render_settings', 7]],
			['config.add', ['calendarinput_min_rows', 5]],

			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_CALENDARINPUT'
			]],
			['module.add', [
				'acp',
				'ACP_CALENDARINPUT',
				[
					'module_basename'	=> '\marttiphpbb\calendarinput\acp\main_module',
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
					'topic_calendarinput_start_day'  		=> ['UINT', NULL],
					'topic_calendarinput_end_day' 			=> ['UINT', NULL],
				],
			],
		];
	}

	public function revert_schema()
	{
		return [
			'drop_columns'        => [
				$this->table_prefix . 'topics'        => [
					'topic_calendarinput_start_day',
					'topic_calendarinput_end_day',
				],
			],
		];
	}
}
