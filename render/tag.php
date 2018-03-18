<?php

/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\render;

use phpbb\controller\helper;
use marttiphpbb\calendarinput\util\dateformat;

class tag
{
	/* @var helper */
	private $helper;

	/* @var dateformat */
	private $dateformat;

	/**
	* @param helper		$helper
	* @param dateformat	$dateformat
	*/
	public function __construct(helper $helper, dateformat $dateformat)
	{
		$this->helper = $helper;
		$this->dateformat = $dateformat;
	}

	/*
	 * @param array 
	 * @return array
	 */
	public function get(array $input):array
	{
		$start = $input['topic_calendarinput_start'];

		if (!$start)
		{
			return [];
		}

		$end = $input['topic_calendarinput_end'];
		
		$year = gmdate('Y', $start);
		$month = gmdate('n', $start);

		return [
			'CALENDARINPUT_TAG_URL' => $this->helper->route('marttiphpbb_calendarinput_monthview_controller', ['year' => $year, 'month' => $month]),
			'CALENDARINPUT_TAG' => $this->dateformat->get_period($start, $end),
		];
	}
}
