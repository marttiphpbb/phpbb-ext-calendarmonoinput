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

	'ACP_MARTTIPHPBB_CALENDARINPUT_SETTINGS_SAVED'
		=> 'Settings have been saved successfully!',

// input_range

	'ACP_MARTTIPHPBB_CALENDARINPUT_LOWER_LIMIT_DAYS'
		=> 'Lower limit when a event may start.',
	'ACP_MARTTIPHPBB_CALENDARINPUT_LOWER_LIMIT_DAYS_EXPLAIN'
		=> 'Measured from now in days (value may be negative)',
	'ACP_MARTTIPHPBB_CALENDARINPUT_UPPER_LIMIT_DAYS'
		=> 'Upper limit when a event may start.',
	'ACP_MARTTIPHPBB_CALENDARINPUT_UPPER_LIMIT_DAYS_EXPLAIN'
		=> 'Measured from now in days (value may be negative)',
	'ACP_MARTTIPHPBB_CALENDARINPUT_MIN_DURATION_DAYS'
		=> 'Minimum duration of an event in days.',
	'ACP_MARTTIPHPBB_CALENDARINPUT_MIN_DURATION_DAYS_EXPLAIN'
		=> '',
	'ACP_MARTTIPHPBB_CALENDARINPUT_MAX_DURATION_DAYS'
		=> 'Maximum duration of an event in days.',
	'ACP_MARTTIPHPBB_CALENDARINPUT_MAX_DURATION_DAYS_EXPLAIN'
		=> 'Must be longer than the minimum duration',

// input_format

// input_forums

	'ACP_MARTTIPHPBB_CALENDARINPUT_FORUMS_EXPLAIN'
		=> 'Select the forums where a calendar event can be set for the topics.',
	'ACP_MARTTIPHPBB_CALENDARINPUT_FORUMS_ENABLED'
		=> 'Enabled',
	'ACP_MARTTIPHPBB_CALENDARINPUT_FORUMS_REQUIRED'
		=> 'Required',

// Placement

	'ACP_MARTTIPHPBB_CALENDARINPUT_PLACEMENT_EXPLAIN'
		=> 'Placement of the calendar date input before or after
		the subject input.',
	'ACP_MARTTIPHPBB_CALENDARINPUT_BEFORE'
		=> 'Before',
	'ACP_MARTTIPHPBB_CALENDARINPUT_AFTER'
		=> 'After',

]);
