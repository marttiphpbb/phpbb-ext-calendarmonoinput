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

	'CALENDARINPUT_DATES_WRONG_ORDER_ERROR'	=> 'Calendar dates are in wrong order.',

	'CALENDARINPUT_TOO_LONG_PERIOD_ERROR'	=> [
		1	=> 'The calendarinput period can be maximum 1 day long.',
		2	=> 'The calendarinput period exceeds the maximum of %s days',
	],
	'CALENDARINPUT_START_DATE_ERROR'	=> 'Incorrect start date',
	'CALENDARINPUT_END_DATE_ERROR'		=> 'Incorrect end date',

	'CALENDARINPUT_EVENT_DATE'		=> 'Date',
	'CALENDARINPUT_TO'				=> 'to',
]);
