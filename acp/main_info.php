<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\acp;

class main_info
{
	function module()
	{
		return [
			'filename'	=> '\marttiphpbb\calendarinput\acp\main_module',
			'title'		=> 'ACP_CALENDARINPUT',
			'modes'		=> [
				'links'	=> [
					'title' => 'ACP_CALENDARINPUT_LINKS',
					'auth' => 'ext_marttiphpbb/calendarinput && acl_a_board',
					'cat' => ['ACP_CALENDARINPUT'],
				],
				'page_rendering'	=> [
					'title' => 'ACP_CALENDARINPUT_PAGE_RENDERING',
					'auth' => 'ext_marttiphpbb/calendarinput && acl_a_board',
					'cat' => ['ACP_CALENDARINPUT'],
				],				
				'input'		=> [
					'title'	=> 'ACP_CALENDARINPUT_INPUT',
					'auth'	=> 'ext_marttiphpbb/calendarinput && acl_a_board',
					'cat'	=> ['ACP_CALENDARINPUT'],
				],
				'input_forums'		=> [
					'title'	=> 'ACP_CALENDARINPUT_INPUT_FORUMS',
					'auth'	=> 'ext_marttiphpbb/calendarinput && acl_a_board',
					'cat'	=> ['ACP_CALENDARINPUT'],
				],
				'include_assets'		=> [
					'title'	=> 'ACP_CALENDARINPUT_INCLUDE_ASSETS',
					'auth'	=> 'ext_marttiphpbb/calendarinput && acl_a_board',
					'cat'	=> ['ACP_CALENDARINPUT'],
				],
			],
		];
	}
}
