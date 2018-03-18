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

	'CALENDARINPUT'						=> 'Calendar',
	'CALENDARINPUT_EXTENSION'			=> '%sCalendar%s extension for phpBB',

// viewonline
	'CALENDARINPUT_VIEWING'			=> 'Viewing calendarinput',

	/*
	'CALENDARINPUT_MONTH'			=> 'month',
	'CALENDARINPUT_DAY'				=> 'day',
	'CALENDARINPUT_YEAR'				=> 'year',
	*/

// %1$s : context (see below)
	'calendarinput_format'	=> [
		'MONTH_DAY_YEAR'				=> '%1$s %1$s %2$s, %3$s',
		'MONTH_DAY_YEAR_TIME'			=> '%1$s %2$s, %3$s - %4$s',
		'MONTH_DAY_DAY_YEAR'			=> '%1$s %2$s - %5$s, %3$s',
		'MONTH_DAY_MONTH_DAY_YEAR'		=> '%1$s %2$s - %4$s %5$s, %6$s',
		'MONTH_DAY_YEAR_MONTH_DAY_YEAR'	=> '%1$s %2$s, %3$s - %4$s %5$s, %6$s',
	],

// Calendar page

	'CALENDARINPUT_NEW_MOON'				=> 'New moon',
	'CALENDARINPUT_FIRST_QUARTER_MOON'	=> 'First quarter moon',
	'CALENDARINPUT_FULL_MOON'			=> 'Full moon',
	'CALENDARINPUT_THIRD_QUARTER_MOON'	=> 'Third quarter moon',
	'CALENDARINPUT_AT'					=> '@',
]);
