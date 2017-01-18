<?php

/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2016 marttiphpbb <info@martti.be>
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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …

$lang = array_merge($lang, [

	'CALENDAR_ADD_ANOTHER'			=> 'Add another',
	'CALENDAR_EVENT_FROM'			=> 'Calendar event from',
	'CALENDAR_TO'					=> 'to',

	'CALENDAR_MONTH'				=> 'month',
	'CALENDAR_DAY'					=> 'day',
	'CALENDAR_YEAR'					=> 'year',

	'CALENDAR_MAX_PERIODS_EXCEDED'	=> 'The number of maximum %s calendar periods has exceded.',
	'CALENDAR_DATES_WRONG_ORDER'	=> 'Calendar dates are in wrong order.',
	'CALENDAR_MIN_DAYS_BETWEEN'		=> [
		1	=> 'Minimum 1 day is required between successive calendar periods',
		2	=> 'Minimum %s days are required between successive calendar periods',
	],
	'CALENDAR_MAX_DAYS_BETWEEN'		=> [
		1	=> 'Maximum 1 day is allowed between successive calendar periods',
		2	=> 'Maximum %s days are allowed between successive calendar periods',
	],
	'CALENDAR_TOO_LONG_PERIOD'		=> [
		1	=> 'The calendar period can be maximum 1 day long.',
		2	=> 'The calendar period exceeds the maximum of %s days',
	),
	'CALENDAR_EVENT_DATE'			=> 'Date',
	'CALENDAR_EVENT_DATE_AND_TIME'	=> 'Date and time',
	'CALENDAR_RECURRENT'			=> 'Recurrent',

]);
