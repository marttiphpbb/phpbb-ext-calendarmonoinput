<?php
/**
* @package phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2016 marttiphpbb <info@martti.be>
* @license http://opensource.org/licenses/MIT
*/

namespace marttiphpbb\calendar\util;

use phpbb\user;
use phpbb\language\language;

/*
 * derives a timeformat from the user's dateformat.
 */

class dateformat
{
	protected static $translate_ary = [];

	protected static $ignore_types = '|aABgGhHisuveOPZcrUDlNwWtLo';

	protected static $transform_types = [
		'd'	=> 'djSz',
		'm'	=> 'FmMn',
		'Y'	=> 'Yy',
	];

	protected static $template_ary = [];

	protected static $transform_ary = [];

	protected static $type_ary = [];

	protected static $separator = ' - ';

	/* @var user */
	protected $user;

	/* @var language */
	protected $language;

	/*
	 * @param user $user
	 */
	public function __construct(user $user, language $language)
	{
		$this->user = $user;
		$this->language = $language;

		self::$translate_ary = array_filter($language->get_lang_array('datetime'), 'is_string');

		foreach (self::$transform_types as $type => $str)
		{
			self::$type_ary += array_fill_keys(str_split($str), $type);
		}

		$ignore_ary = array_fill_keys(str_split(self::$ignore_types), true);

		$dateformat = $user->data['user_dateformat'];

		$dateformat_ary = str_split($dateformat);

		$transform_ary_complete = false;

		foreach ($dateformat_ary as $type)
		{
			if ($ignore_ary[$type])
			{
				continue;
			}

			if (self::$type_ary[$type])
			{
				if (isset(self::$transform_ary[self::$type_ary[$type]]))
				{
					self::$transform_ary[self::$type_ary[$type]] .= $type;
				}
				else
				{
					self::$transform_ary[self::$type_ary[$type]] = $type;
					self::$template_ary[] = self::$type_ary[$type];
				}

				if (count(self::$transform_ary) == 3)
				{
					$transform_ary_complete = true;
				}

				continue;
			}
			else if ($transform_ary_complete)
			{
				break;
			}

			if (count(self::$type_ary))
			{
				self::$template_ary[] = $type;
			}
		}
	}

	/*
	 * @param int $start
	 * @param int $end
	 * @return string
	 */
	public function get_period(int $start, int $end)
	{
		$s = $e = [];

		foreach (self::$transform_ary as $key => $val)
		{
			$s[$key] = gmdate($val, $start);
			$e[$key] = gmdate($val, $end);

			if ($key == 'm')
			{
				if ($val == 'M')
				{
					$s['m'] = $s['m'] == 'May' ? 'May_short' : $s['m'];
					$e['m'] = $e['m'] == 'May' ? 'May_short' : $e['m'];
				}

				$s['m'] = strtr($s['m'], self::$translate_ary);
				$e['m'] = strtr($e['m'], self::$translate_ary);
			}
		}

		$out_s = $out_e = '';

		if ($s['Y'] != $e['Y'])
		{
			foreach (self::$template_ary as $ch)
			{
				$out_s .= $s[$ch] ?? $ch;
				$out_e .= $e[$ch] ?? $ch;
			}

			return $out_s . self::$separator . $out_e;
		}

		if ($s['m'] != $e['m'])
		{
			$out_before = $out_after = '';

			$after_md  = false;

			$count_md = 0;

			foreach (self::$template_ary as $ch)
			{
				if ($ch == 'd' || $ch == 'm')
				{
					$count_md++;
				}

				if ($after_md)
				{
					$out_after .= $s[$ch] ?? $ch;
				}
				else if ($count_md)
				{
					$out_s .= $s[$ch] ?? $ch;
					$out_e .= $e[$ch] ?? $ch;

					$after_md = $count_md == 2 ? true : false;
				}
				else
				{
					$out_before .= $s[$ch] ?? $ch;
				}
			}

			return $out_before . $out_s . self::$separator . $out_e . $out_after;
		}

		if ($s['d'] != $e['d'])
		{
			$out_before = $out_after = '';

			$after_d  = false;

			foreach (self::$template_ary as $ch)
			{
				if ($after_d)
				{
					$out_after .= $s[$ch] ?? $ch;
				}
				else if ($ch == 'd')
				{
					$out_s .= $s[$ch] ?? $ch;
					$out_e .= $e[$ch] ?? $ch;

					$after_d = true;
				}
				else
				{
					$out_before .= $s[$ch] ?? $ch;
				}
			}

			return $out_before . $out_s . self::$separator . $out_e . $out_after;
		}

		foreach (self::$template_ary as $ch)
		{
			$out_s .= $s[$ch] ?? $ch;
		}

		return $out_s;
	}
}
