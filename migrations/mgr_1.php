<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\migrations;

use marttiphpbb\calendarinput\util\cnst;

class mgr_1 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return [
			'\phpbb\db\migration\data\v32x\v321',
		];
	}

	public function update_data()
	{
		$input_settings = [
			'lower_limit_days'	=> 0,
			'upper_limit_days'	=> 720,
			'min_duration_days'	=> 1,
			'max_duration_days'	=> 30,
			'placement_before'	=> true,
			'forums'			=> [],
		];

		return [
			['config_text.add', [cnst::ID, serialize($input_settings)]],
		];
	}
}
