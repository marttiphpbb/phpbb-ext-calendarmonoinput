<?php

/**
* phpBB Extension - marttiphpbb calendarmonoinput
* @copyright (c) 2014 - 2019 marttiphpbb <info@martti.be>
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

	'MARTTIPHPBB_CALENDARMONOINPUT_DATES_WRONG_ORDER_ERROR'
		=> 'The dates are in wrong order.',

	'MARTTIPHPBB_CALENDARMONOINPUT_TOO_LONG_PERIOD_ERROR'	=> [
		1	=> 'The calendar period can be maximum 1 day long.',
		2	=> 'The calendar period exceeds the maximum of %s days',
	],

	'MARTTIPHPBB_CALENDARMONOINPUT_TOO_SHORT_PERIOD_ERROR'	=> [
		1	=> 'The calendar period has to be minimum 1 day long.',
		2	=> 'The calendar period has to be minimum %s days long',
	],

	'MARTTIPHPBB_CALENDARMONOINPUT_START_DATE_EMPTY_ERROR'
		=> 'The start date is empty',
	'MARTTIPHPBB_CALENDARMONOINPUT_END_DATE_EMPTY_ERROR'
		=> 'The end date is empty',

	'MARTTIPHPBB_CALENDARMONOINPUT_DATE_FORMAT_ERROR'
		=> 'Incorrect date format',
	'MARTTIPHPBB_CALENDARMONOINPUT_START_DATE_FORMAT_ERROR'
		=> 'Incorrect start date format',
	'MARTTIPHPBB_CALENDARMONOINPUT_END_DATE_FORMAT_ERROR'
		=> 'Incorrect end date format',

	'MARTTIPHPBB_CALENDARMONOINPUT_DATE_REQUIRED_ERROR'
		=> 'The date is a required field',
	'MARTTIPHPBB_CALENDARMONOINPUT_START_DATE_REQUIRED_ERROR'
		=> 'The start date is a required field',
	'MARTTIPHPBB_CALENDARMONOINPUT_END_DATE_REQUIRED_ERROR'
		=> 'The end date is a required field',

	'MARTTIPHPBB_CALENDARMONOINPUT_DATE_UNDER_LIMIT_ERROR'
		=> 'The date is before the allowed range',
	'MARTTIPHPBB_CALENDARMONOINPUT_START_DATE_UNDER_LIMIT_ERROR'
		=> 'The start date is before the allowed range',
	'MARTTIPHPBB_CALENDARMONOINPUT_DATE_OVER_LIMIT_ERROR'
		=> 'The date is after the allowed range',
	'MARTTIPHPBB_CALENDARMONOINPUT_START_DATE_OVER_LIMIT_ERROR'
		=> 'The start date is after the allowed range',

	'MARTTIPHPBB_CALENDARMONOINPUT_EVENT_DATE'		=> 'Date',
	'MARTTIPHPBB_CALENDARMONOINPUT_TO'				=> 'to',
]);
