<?php
/**
* phpBB Extension - marttiphpbb calendarmonoinput
* @copyright (c) 2014 - 2020 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarmonoinput\migrations;

use marttiphpbb\calendarmonoinput\util\cnst;

class mgr_2 extends \phpbb\db\migration\migration
{
	static public function depends_on():array
	{
		return [
			'\marttiphpbb\calendarmonoinput\migrations\mgr_1',
		];
	}

	public function update_data():array
	{
		return [
			['module.add', [
				'acp',
				'ACP_CAT_DOT_MODS',
				cnst::L_ACP
			]],
			['module.add', [
				'acp',
				cnst::L_ACP,
				[
					'module_basename'	=> '\marttiphpbb\calendarmonoinput\acp\main_module',
					'modes'				=> [
						'range',
						'forums',
						'placement',
						'format',
						'placeholder',
					],
				],
			]],
		];
	}
}
