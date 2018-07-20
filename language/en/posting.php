<?php

/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [

	'MARTTIPHPBB_CALENDARINPUT_DATES_WRONG_ORDER_ERROR'	=> 'Calendar dates are in wrong order.',

	'MARTTIPHPBB_CALENDARINPUT_TOO_LONG_PERIOD_ERROR'	=> [
		1	=> 'The calendar period can be maximum 1 day long.',
		2	=> 'The calendar period exceeds the maximum of %s days',
	],
	'MARTTIPHPBB_CALENDARINPUT_START_DATE_ERROR'	=> 'Incorrect start date',
	'MARTTIPHPBB_CALENDARINPUT_END_DATE_ERROR'		=> 'Incorrect end date',

	'MARTTIPHPBB_CALENDARINPUT_EVENT_DATE'		=> 'Date',
	'MARTTIPHPBB_CALENDARINPUT_TO'				=> 'to',
]);
