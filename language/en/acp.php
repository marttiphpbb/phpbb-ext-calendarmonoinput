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
		=> 'Lower limit when a event can start.',
	'ACP_MARTTIPHPBB_CALENDARINPUT_LOWER_LIMIT_DAYS_EXPLAIN'
		=> 'Measured from now in days (value may be negative)',
	'ACP_MARTTIPHPBB_CALENDARINPUT_UPPER_LIMIT_DAYS'
		=> 'Upper limit when a event can start.',
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

// format

	'ACP_MARTTIPHPBB_CALENDARINPUT_VISUALIZATION_DATE_FORMAT_EXPLAIN'
		=> 'This is the date format shown to the User in the input field',

	'ACP_MARTTIPHPBB_CALENDARINPUT_VISUALIZATION_DATE_FORMAT_CODES'
		=> 'The format can be combinations of the following: <ul>
			<li>d - day of month (no leading zero)</li>
			<li>dd - day of month (two digit)</li>
			<li>o - day of the year (no leading zeros)</li>
			<li>oo - day of the year (three digit)</li>
			<li>D - day name short</li>
			<li>DD - day name long</li>
			<li>m - month of year (no leading zero)</li>
			<li>mm - month of year (two digit)</li>
			<li>M - month name short</li>
			<li>MM - month name long</li>
			<li>y - year (two digit)</li>
			<li>yy - year (four digit)</li>
			<li>@ - Unix timestamp (ms since 01/01/1970)</li>
			<li>! - Windows ticks (100ns since 01/01/0001)</li>
			<li>\'...\' - literal text</li>
			<li>\'\' - single quote</li>
			<li>anything else - literal text</li></ul>
			(the %1$sJQuery UI Datepicker API%2$s)',

		'ACP_MARTTIPHPBB_CALENDARINPUT_VISUALIZATION_DATE_FORMAT_SHOW'
			=> 'This is how it will look like',

// placeholder

		'ACP_MARTTIPHPBB_CALENDARINPUT_PLACEHOLDER_DATE_FIELDS_EXPLAIN'
			=> 'You can optionally set placeholders for the date input
			fields. The placeholder is shown to the user when
			no date is selected.',
		'ACP_MARTTIPHPBB_CALENDARINPUT_PLACEHOLDER_START_DATE'
			=> 'Placeholder for the start date field',
		'ACP_MARTTIPHPBB_CALENDARINPUT_PLACEHOLDER_END_DATE'
			=> 'Placeholder for the end date field',

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
