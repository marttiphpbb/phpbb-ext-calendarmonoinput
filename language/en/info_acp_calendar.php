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

	'ACP_MARTTIPHPBB_CALENDARMONOINPUT'				=> 'Calendar Mono Input',
	'ACP_MARTTIPHPBB_CALENDARMONOINPUT_RANGE'		=> 'Range',
	'ACP_MARTTIPHPBB_CALENDARMONOINPUT_DATE_FORMAT'
		=> 'Date Format',
	'ACP_MARTTIPHPBB_CALENDARMONOINPUT_FORUMS'		=> 'Forums',
	'ACP_MARTTIPHPBB_CALENDARMONOINPUT_PLACEMENT'	=> 'Placement',
	'ACP_MARTTIPHPBB_CALENDARMONOINPUT_PLACEHOLDER'
		=> 'Placeholder',

]);
