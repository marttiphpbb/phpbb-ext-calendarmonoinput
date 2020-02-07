<?php
/**
* phpBB Extension - marttiphpbb calendarmonoinput
* @copyright (c) 2014 - 2020 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarmonoinput\acp;

use marttiphpbb\calendarmonoinput\util\cnst;

class main_info
{
	function module():array
	{
		return [
			'filename'	=> '\marttiphpbb\calendarmonoinput\acp\main_module',
			'title'		=> cnst::L_ACP,
			'modes'		=> [
				'range'	=> [
					'title'	=> cnst::L_ACP . '_RANGE',
					'auth'	=> 'ext_marttiphpbb/calendarmonoinput && acl_a_board',
					'cat'	=> [cnst::L_ACP],
				],
				'forums'	=> [
					'title'	=> cnst::L_ACP . '_FORUMS',
					'auth'	=> 'ext_marttiphpbb/calendarmonoinput && acl_a_board',
					'cat'	=> [cnst::L_ACP],
				],
				'placement'	=> [
					'title'	=> cnst::L_ACP . '_PLACEMENT',
					'auth'	=> 'ext_marttiphpbb/calendarmonoinput && acl_a_board',
					'cat'	=> [cnst::L_ACP],
				],
				'format'	=> [
					'title'	=> cnst::L_ACP . '_DATE_FORMAT',
					'auth'	=> 'ext_marttiphpbb/calendarmonoinput && acl_a_board',
					'cat'	=> [cnst::L_ACP],
				],
				'placeholder'	=> [
					'title'	=> cnst::L_ACP . '_PLACEHOLDER',
					'auth'	=> 'ext_marttiphpbb/calendarmonoinput && acl_a_board',
					'cat'	=> [cnst::L_ACP],
				],
			],
		];
	}
}
