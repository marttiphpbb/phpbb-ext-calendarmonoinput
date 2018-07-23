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

	'ACP_MARTTIPHPBB_CALENDARINPUT'				=> 'Calendar Input',
	'ACP_MARTTIPHPBB_CALENDARINPUT_RANGE'		=> 'Range',
	'ACP_MARTTIPHPBB_CALENDARINPUT_DATE_FORMAT'
		=> 'Date Format',
	'ACP_MARTTIPHPBB_CALENDARINPUT_FORUMS'		=> 'Forums',
	'ACP_MARTTIPHPBB_CALENDARINPUT_PLACEMENT'	=> 'Placement',
	'ACP_MARTTIPHPBB_CALENDARINPUT_PLACEHOLDER'
		=> 'Placeholder',

]);
