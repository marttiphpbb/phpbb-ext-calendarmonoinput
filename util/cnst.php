<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\util;

class cnst
{
	const FOLDER = 'marttiphpbb/calendarinput';
	const ID = 'marttiphpbb_calendarinput';
	const CACHE_ID = '_' . self::ID;
	const PREFIX = self::ID . '_';
	const L = 'MARTTIPHPBB_JQUERYUIDATEPICKER';
	const L_ACP = 'ACP_' . self::L;
	const L_MCP = 'MCP_' . self::L;
	const TPL = '@' . self::ID . '/';
	const EXT_PATH = 'ext/' . self::FOLDER . '/';
}
