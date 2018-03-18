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

	'ACP_CALENDARINPUT_SETTING_SAVED'	=> 'Settings have been saved successfully!',

// repository link

	'ACP_CALENDARINPUT_REPO_LINK'		=> 'Enable link to the Calendar extension repository in copyright footer',

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

	'ACP_CALENDARINPUT_INPUT_FORUMS'			=> 'Input forums',
	'ACP_CALENDARINPUT_INPUT_FORUMS_EXPLAIN'	=> 'Select which in forums topics can be calendarinput events.',
	'ACP_CALENDARINPUT_INPUT_FORUMS_ENABLED'	=> 'Enabled',
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
