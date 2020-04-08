<?php
/**
* phpBB Extension - marttiphpbb calendarmonoinput
* @copyright (c) 2014 - 2020 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarmonoinput\migrations;

use marttiphpbb\calendarmonoinput\util\cnst;

class mgr_1 extends \phpbb\db\migration\migration
{
	static public function depends_on():array
	{
		return [
			'\phpbb\db\migration\data\v330\v330',
		];
	}

	public function update_data():array
	{
		$input_settings = [
			'lower_limit_days'			=> 0,
			'upper_limit_days'			=> 720,
			'min_duration_days'			=> 1,
			'max_duration_days'			=> 30,
			'placement_before'			=> true,
			'date_format'				=> 'MM d, yy',
			'placeholder_start_date'	=> '',
			'placeholder_end_date'		=> '',
			'first_day'					=> 0,
			'forums'					=> [],
		];

		return [
			['config_text.add', [cnst::ID, serialize($input_settings)]],
		];
	}
}
