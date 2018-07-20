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

	'ACP_CALENDARINPUT'						=> 'Calendar Input',
	'ACP_CALENDARINPUT_INPUT_RANGE'			=> 'Input range',
	'ACP_CALENDARINPUT_INPUT_FORMAT'		=> 'Input format',
	'ACP_CALENDARINPUT_INPUT_FORUMS'		=> 'Input forums',
]);
