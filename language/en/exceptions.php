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

	'EXCEPTION_FIELD_MISSING'		=> 'required field missing',
	'EXCEPTION_INVALID_ARGUMENT'	=> 'Invalid argument specified for `%1$s`. Reason: %2$s.',
	'EXCEPTION_OUT_OF_BOUNDS'		=> 'The field `%1$s` received data beyond its bounds',

]);
