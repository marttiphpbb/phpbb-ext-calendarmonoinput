<?php

/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2015 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
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

$lang = array_merge($lang, array(

	'ACP_CALENDAR_SETTING_SAVED'						=> 'Settings have been saved successfully!',

// rendering: links
	'ACP_CALENDAR_LINKS'								=> 'Links',
	'ACP_CALENDAR_LINK_LOCATIONS' 						=> 'Link locations to the Calendar page',
	'ACP_CALENDAR_REPO_LINK'							=> 'Enable link to the Calendar extension repository in copyright footer',

	'ACP_CALENDAR_OVERALL_HEADER_NAVIGATION_PREPEND'	=> 'Overall header navigation prepend',
	'ACP_CALENDAR_OVERALL_HEADER_NAVIGATION_APPEND'		=> 'Overall header navigation append',
	'ACP_CALENDAR_NAVBAR_HEADER_QUICK_LINKS_BEFORE'		=> 'Navbar header quick links before',
	'ACP_CALENDAR_NAVBAR_HEADER_QUICK_LINKS_AFTER'		=> 'Navbar header quick links after',
	'ACP_CALENDAR_OVERALL_HEADER_BREADCRUMBS_BEFORE'	=> 'Overall header breadcrumbs before',
	'ACP_CALENDAR_OVERALL_HEADER_BREADCRUMBS_AFTER'		=> 'Overall header breadcrumbs after',
	'ACP_CALENDAR_OVERALL_FOOTER_TIMEZONE_BEFORE'		=> 'Overall footer timezone before',
	'ACP_CALENDAR_OVERALL_FOOTER_TIMEZONE_AFTER'		=> 'Overall footer timezone after',
	'ACP_CALENDAR_OVERALL_FOOTER_TEAMLINK_BEFORE'		=> 'Overall footer teamlink before',
	'ACP_CALENDAR_OVERALL_FOOTER_TEAMLINK_AFTER'		=> 'Overall footer teamlink after',

// rendering: calendar page
	'ACP_CALENDAR_PAGE'									=> 'Calendar page',
	'ACP_CALENDAR_SHOW_MOON'							=> 'Display moon cycles',
	'ACP_CALENDAR_SHOW_ISOWEEK'							=> 'Display the week number (ISO 1806)',
	'ACP_CALENDAR_SHOW_ISOWEEK_EXPLAIN'					=> 'According to ISO 1806, the first day of the week is defined monday.',
	'ACP_CALENDAR_SHOW_TODAY'							=> 'Mark today´s date',
	'ACP_CALENDAR_SELECT_FIRST_WEEKDAY'					=> 'First day of the week',

// input
	'ACP_CALENDAR_INPUT_GRANULARITY'	=> 'Input granularity',
	'ACP_CALENDAR_GRANULARITY_OPTIONS'	=> array(
		1		=> '1 min.',
		5		=> '5 min.',
		15		=> '15 min.',
		30 		=> '30 min.',
		60		=> '1 hour',
		1440 	=> '1 day',
	),

	'ACP_CALENDAR_MAX_PERIODS'			=> 'Maximum number of calendar periods per topic',

	'CALENDAR_ADD_ANOTHER'		=> 'Add another',
	'CALENDAR_PERIOD_FROM'		=> 'Calendar period from',
	'CALENDAR_N_PERIOD_FROM'	=> '%s period from',
	'CALENDAR_TO'				=> 'to',

	'CALENDAR_MONTH'			=> 'month',
	'CALENDAR_DAY'				=> 'day',
	'CALENDAR_YEAR'				=> 'year',

	'calendar_suffix_format'	=> array(
		'MONTH_DAY_YEAR'				=> '%1$s %2$s, %6$s',
		'MONTH_DAY_DAY_YEAR'			=> '%1$s %2$s - %5$s, %6$s',
		'MONTH_DAY_MONTH_DAY_YEAR'		=> '%1$s %2$s - %4$s %5$s, %6$s',
		'MONTH_DAY_YEAR_MONTH_DAY_YEAR'	=> '%1$s %2$s, %3$s - %4$s %5$s, %6$s'
	),

	'CALENDAR_SUFFIX_NOW'			=> 'now : ',
	'CALENDAR_SUFFIX_NEXT'			=> 'next : ',
	'CALENDAR_SUFFIX_LAST'			=> 'last : ',
	'CALENDAR_SUFFIX_SINGLE'		=> '',

	'CALENDAR_ALL_PERIODS'			=> 'All calendar periods',

	'CALENDAR_MAX_PERIODS_EXCEDED'	=> 'The number of maximum %s calendar periods has exceded.',
	'CALENDAR_DATES_WRONG_ORDER'	=> 'Calendar dates are in wrong order.',
	'CALENDAR_MIN_DAYS_BETWEEN'		=> 'Minimum %s day(s) are required between successive calendar periods',
	'CALENDAR_MAX_DAYS_BETWEEN'		=> 'Maximum %s day(s) are allowed between successive calendar periods',
	'CALENDAR_TOO_LONG_PERIOD'		=> 'Calendar period exceeds the maximum of %s days',

	'CALENDAR_NEW_MOON'				=> 'New moon',
	'CALENDAR_FIRST_QUARTER_MOON'	=> 'First quarter moon',
	'CALENDAR_FULL_MOON'			=> 'Full moon',
	'CALENDAR_THIRD_QUARTER_MOON'	=> 'Third quarter moon',

	'CALENDAR_AT'					=> '@',

	'CALENDAR_NO_FILTER'			=> 'all topics',
	'CALENDAR_NO_TAG'				=> 'no calendar tag',
	'CALENDAR_TAG_ONLY'				=> 'calendar tag only',
	'CALENDAR_WITH_TAG'				=> 'with calendar tag',
	'CALENDAR_FILTER'				=> 'calendar',
	'CALENDAR_ONE_TAG'				=> '1 calendar tag',
	'CALENDAR_MORE_TAGS'			=> '%s calendar tags',

	'CALENDAR_FORUMS'				=> 'forums',

// posting

	'CALENDAR_EVENT_DATE'			=> 'Date',
	'CALENDAR_EVENT_DATE_AND_TIME'	=> 'Date and time',
	'CALENDAR_RECURRENT'			=> 'Recurrent',

// notifications

	'NOTIFICATION_TYPE_CALENDAR_EVENT_START'	=>
			'A topic calendar event to which you are subscribed, starts',
	'NOTIFICATION_TYPE_CALENDAR_EVENT_START_FORUM'	=>
			'A forum calendar event to which you are subscribed, starts',

// ACP
	'ACP_CALENDAR_GENERAL_SETTINGS'		=> 'general',
	'ACP_CALENDAR_ENABLE'				=> 'calendar enable',
	'ACP_CALENDAR_BIRTHDAYS'			=> 'list birthdays on calendar page',

	'ACP_CALENDAR_DISPLAY_SUFFIX'		=> 'suffix display',
	'ACP_CALENDAR_ENABLE_SUFFIX'		=> 'suffix enable',

	'ACP_CALENDAR_SUFFIX_CONTEXT'		=> 'suffix context',
	'ACP_CALENDAR_MULTI_SUFFIX'			=> 'multi suffix',

	'ACP_CALENDAR_MONTH_ABBREV'			=> 'Use Month abbreviations',

	'ACP_CALENDAR_FILTER'				=> 'Calendar filter',
	'ACP_CALENDAR_ENABLE_FILTER'		=> 'Calendar enable filter',

	'ACP_CALENDAR_INPUT_FORUMS_SELECT'	=> 'Input forums select',
	'ACP_CALENDAR_INPUT_FORUMS'			=> 'Input forums',
	'ACP_CALENDAR_INPUT_FORUMS_EXPLAIN' => '',

	'ACP_CALENDAR_DATE_INPUTS'			=> 'Date Inputs',

	'ACP_CALENDAR_MIN_START'			=> 'start day possible minus days from now',
	'ACP_CALENDAR_MIN_START_EXPLAIN'	=> '',

	'ACP_CALENDAR_PLUS_START'			=> 'start day possible plus days from now',
	'ACP_CALENDAR_PLUS_START_EXPLAIN'	=> '',

	'ACP_CALENDAR_MAX_PERIOD_LENGTH'			=> 'maximum period length in days',
	'ACP_CALENDAR_MAX_PERIOD_LENGTH_EXPLAIN'	=> '',
	'ACP_CALENDAR_MAX_PERIODS_EXPLAIN'	=> 'setting to zero disables attaching calendar periods to topics.',

	'ACP_CALENDAR_MIN_GAP'				=> 'minimum gap between periods of one topic in days',
	'ACP_CALENDAR_MIN_GAP_EXPLAIN'		=> '',

	'ACP_CALENDAR_MAX_GAP'				=> 'maximum gap between periods of one topic in days',
	'ACP_CALENDAR_MAX_GAP_EXPLAIN'		=> '',

	'ACP_CALENDAR_PRESET'				=> 'show topics in calendar (preset)',
	'ACP_CALENDAR_PRESET_EXPLAIN'		=> '',

	'ACP_CALENDAR_PAGE_FORUMS_DISPLAY'	=> 'Forums display on Calendar Page.',

	'MAX_PERIODS'			=> 'max. periods',
	'MAX_PERIOD_LENGTH'		=> 'max. period length',
	'MIN_START'				=> 'min. start',
	'MAX_START'				=> 'max. start',
	'MIN_GAP'				=> 'min. gap',
	'MAX_GAP'				=> 'max. gap',
	'PRESET'				=> 'preset',

));
