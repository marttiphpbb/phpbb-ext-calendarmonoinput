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
