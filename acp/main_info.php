<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2017 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\acp;

class main_info
{
	function module()
	{
		return [
			'filename'	=> '\marttiphpbb\calendar\acp\main_module',
			'title'		=> 'ACP_CALENDAR',
			'modes'		=> [
				'rendering'	=> [
					'title' => 'ACP_CALENDAR_RENDERING',
					'auth' => 'ext_marttiphpbb/calendar && acl_a_board',
					'cat' => ['ACP_CALENDAR'],
				],
				'input'		=> [
					'title'	=> 'ACP_CALENDAR_INPUT',
					'auth'	=> 'ext_marttiphpbb/calendar && acl_a_board',
					'cat'	=> ['ACP_CALENDAR'],
				],
				'input_forums'		=> [
					'title'	=> 'ACP_CALENDAR_INPUT_FORUMS',
					'auth'	=> 'ext_marttiphpbb/calendar && acl_a_board',
					'cat'	=> ['ACP_CALENDAR'],
				],
				'include_assets'		=> [
					'title'	=> 'ACP_CALENDAR_INCLUDE_ASSETS',
					'auth'	=> 'ext_marttiphpbb/calendar && acl_a_board',
					'cat'	=> ['ACP_CALENDAR'],
				],
			],
		];
	}
}
