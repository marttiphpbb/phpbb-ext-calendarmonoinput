<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2015 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\migrations;

class v_0_1_0 extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		$input_settings = array(
			'default'	=> array(
				'min_length'		=> 1800,
				'max_length'		=> 14400,
				'length'			=> 0,
				'format'			=> '',
				'min_gap'			=> 43200,
				'max_gap'			=> 43200,
				'granularity'		=> 900,
				'max_event_count'	=> 1,
				'min_date'			=> 0,
				'max_date'			=> 365,
			),
			'forums'		=> array(),
		);

		return array(

			array('config_text.add', array('marttiphpbb_calendar_input', serialize($input_settings))),

			array('config.add', array('calendar_default_view', 'month')),
			array('config.add', array('calendar_first_weekday', 0)),

			array('config.add', array('calendar_links', 3)),
			array('config.add', array('calendar_rendering', 7)),
/*
			array('config.add', array('calendar_show_isoweek', 1)),
			array('config.add', array('calendar_show_moon', 1)),
			array('config.add', array('calendar_show_today', 1)),
*/
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_CALENDAR'
			)),
			array('module.add', array(
				'acp',
				'ACP_CALENDAR',
				array(
					'module_basename'	=> '\marttiphpbb\calendar\acp\main_module',
					'modes'				=> array(
						'rendering',
						'input',
					),
				),
			)),
		);
	}

	public function update_schema()
	{
		return array(
/*			'add_columns'        => array(
				$this->table_prefix . 'topics'        => array(
					'topic_calendar_start'  		=> array('UINT', 0),
					'topic_calendar_end' 			=> array('UINT', 0),
					'topic_calendar_event_id'		=> array('UINT', 0),
					'topic_calendar_event_pos'		=> array('UINT', 0),
					'topic_calendar_event_count'	=> array('UINT', 0),
				),
			),
*/
			'add_tables'		=> array(
				$this->table_prefix . 'calendar_events'	=> array(
					'COLUMNS'	=> array(
						'calendar_event_id'     => array('UINT', NULL, 'auto_increment'),
						'calendar_topic_id'		=> array('UINT', 0),
						'calendar_start'		=> array('UINT', 0),
						'calendar_end'			=> array('UINT', 0),
					),
					'PRIMARY_KEY'  	=> 'calendar_event_id',
					'KEYS' 		=> array(
						'tid'		=> array('INDEX', 'calendar_topic_id'),
					),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
/*			'drop_columns'        => array(
				$this->table_prefix . 'topics'        => array(
					'topic_calendar_start',
					'topic_calendar_end',
					'topic_calendar_event_id',
					'topic_calendar_event_pos',
					'topic_calendar_event_num',
				),
			), */
			'drop_tables'	=> array(
				$this->table_prefix . 'calendar_events',
			),
		);
	}

}
