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

	'ACP_CALENDARINPUT_SETTING_SAVED'						=> 'Settings have been saved successfully!',

// rendering: links
	'ACP_CALENDARINPUT_LINKS'								=> 'Links',
	'ACP_CALENDARINPUT_LINK_LOCATIONS' 						=> 'Link locations to the Calendar page',
	'ACP_CALENDARINPUT_REPO_LINK'							=> 'Enable link to the Calendar extension repository in copyright footer',

	'ACP_CALENDARINPUT_OVERALL_HEADER_NAVIGATION_PREPEND'	=> 'Overall header navigation prepend',
	'ACP_CALENDARINPUT_OVERALL_HEADER_NAVIGATION_APPEND'		=> 'Overall header navigation append',
	'ACP_CALENDARINPUT_NAVBAR_HEADER_QUICK_LINKS_BEFORE'		=> 'Navbar header quick links before',
	'ACP_CALENDARINPUT_NAVBAR_HEADER_QUICK_LINKS_AFTER'		=> 'Navbar header quick links after',
	'ACP_CALENDARINPUT_OVERALL_HEADER_BREADCRUMBS_BEFORE'	=> 'Overall header breadcrumbs before',
	'ACP_CALENDARINPUT_OVERALL_HEADER_BREADCRUMBS_AFTER'		=> 'Overall header breadcrumbs after',
	'ACP_CALENDARINPUT_OVERALL_FOOTER_TIMEZONE_BEFORE'		=> 'Overall footer timezone before',
	'ACP_CALENDARINPUT_OVERALL_FOOTER_TIMEZONE_AFTER'		=> 'Overall footer timezone after',
	'ACP_CALENDARINPUT_OVERALL_FOOTER_TEAMLINK_BEFORE'		=> 'Overall footer teamlink before',
	'ACP_CALENDARINPUT_OVERALL_FOOTER_TEAMLINK_AFTER'		=> 'Overall footer teamlink after',

// rendering: calendarinput page
	'ACP_CALENDARINPUT_PAGE'									=> 'Calendar page',
	'ACP_CALENDARINPUT_MOONPHASE'							=> 'Display moon cycles',
	'ACP_CALENDARINPUT_ISOWEEK'								=> 'Display the week number (ISO 1806)',
	'ACP_CALENDARINPUT_ISOWEEK_EXPLAIN'						=> 'According to ISO 1806, the first day of the week is defined monday.',
	'ACP_CALENDARINPUT_TODAY'								=> 'Mark today´s date',
	'ACP_CALENDARINPUT_SELECT_FIRST_WEEKDAY'					=> 'First day of the week',
	'ACP_CALENDARINPUT_MIN_ROWS'								=> 'Minumum height of the calendarinput cells',
	'ACP_CALENDARINPUT_MIN_ROWS_EXPLAIN'						=> '',

// input

	'ACP_CALENDARINPUT_LOWER_LIMIT_DAYS'				=> 'Lower limit when a event may start.',
	'ACP_CALENDARINPUT_LOWER_LIMIT_DAYS_EXPLAIN'		=> 'Measured from now in days (value may be negative)',
	'ACP_CALENDARINPUT_UPPER_LIMIT_DAYS'				=> 'Upper limit when a event may start.',
	'ACP_CALENDARINPUT_UPPER_LIMIT_DAYS_EXPLAIN'		=> 'Measured from now in days (value may be negative)',

	'ACP_CALENDARINPUT_MIN_DURATION_DAYS'			=> 'Minimum duration of an event in days.',
	'ACP_CALENDARINPUT_MIN_DURATION_DAYS_EXPLAIN'	=> '',
	'ACP_CALENDARINPUT_MAX_DURATION_DAYS'			=> 'Maximum duration of an event in days.',
	'ACP_CALENDARINPUT_MAX_DURATION_DAYS_EXPLAIN'	=> 'Must be longer than the minimum duration',

// input_forums

	'ACP_CALENDARINPUT_INPUT_FORUMS'				=> 'Input forums',
	'ACP_CALENDARINPUT_INPUT_FORUMS_EXPLAIN'		=> 'Select which in forums topics can be calendarinput events.',
	'ACP_CALENDARINPUT_INPUT_FORUMS_ENABLED'		=> 'Enabled',
	'ACP_CALENDARINPUT_INPUT_FORUMS_REQUIRED'	=> 'Required',


// include_assets

	'ACP_CALENDARINPUT_DIRECTORY_LIST_FAIL'		=> 'Failed to list content of directory %s',
	'ACP_CALENDARINPUT_INCLUDE_ASSETS'			=> 'Include assets',
	'ACP_CALENDARINPUT_JQUERY_UI_DATEPICKER_JS'
											=> 'Include jQuery UI Datepicker.',
	'ACP_CALENDARINPUT_JQUERY_UI_DATEPICKER_JS_EXPLAIN'
											=> 'Disable when already included by another extension.',
	'ACP_CALENDARINPUT_JQUERY_UI_DATEPICKER_I18N_JS'
											=> 'Include jQuery UI Datepicker i18n',
	'ACP_CALENDARINPUT_JQUERY_UI_DATEPICKER_I18N_JS_EXPLAIN'
											=> 'Disable when already included by another extension.',
	'ACP_CALENDARINPUT_DATEPICKER_THEME'			=> 'jQuery UI Datepicker theme',
	'ACP_CALENDARINPUT_DATEPICKER_THEME_EXPLAIN'	=> 'Select none if another extension has already included one.',
	'ACP_CALENDARINPUT_DATEPICKER_THEME_NONE'	=> '-- none --',
]);
