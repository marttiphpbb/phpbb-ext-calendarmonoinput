<?php
/**
* @package phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2017 marttiphpbb <info@martti.be>
* @license http://opensource.org/licenses/MIT
* adapted from Stephen A. Zarkos, Calculate Moon Phase Data with PHP
* http://www.obsid.org/2008/05/calculate-moon-phase-data-with-php.html
*/

namespace marttiphpbb\calendar\util;

use marttiphpbb\calendar\core\timespan;

class moonphase_calculator
{

	static protected $moon_name_ary = [
		0	=> 'new',
		1	=> 'q1',
		2	=> 'full',
		3	=> 'q3',
	];

	static private $moon_icon_ary = [
		0 	=> 'fa-circle',
		1	=> 'fa-adjust fa-rotate-180',
		2	=> 'fa-circle-o',
		3	=> 'fa-adjust',
	];

	public function __construct()
	{
	}

	/*
	* Find phases of the moon between two unix times.
	* @param 	timespan $timespan
	* @return 	array
	* 				phase 	0: new moon, 1: first quarter, 2: full moon, 3: 3th quarter
	* 				time  	unix time
	* 				name 	new q1 full q3
	*/
	public function find(timespan $timespan)
	{
		$phases = [];

		// Convert to Julian time
		$start_jd = ($timespan->get_start() / 86400) + 2440587.5;
		$end_jd = ($timespan->get_end() / 86400) + 2440587.5;

		$synodic_month_length = 29.53058868;
		$deg = pi() / 180;
		$max_moon_cycles = 100;

		$day_floor = $start_jd;

		if ($day_floor >= 2299161)
		{
			$alpha = floor(($day_floor - 1867216.25) / 36524.25);
			$day_floor = $day_floor + 1 + $alpha - floor($alpha / 4);
		}

		$day_floor += 1524;

		$year = floor(($day_floor - 122.1) / 365.25);
		$month = floor(($day_floor - floor(365.25 * $year)) / 30.6001);

		$month = ($month < 14) ? $month - 1 : $month - 13;
		$year = ($month > 2) ? $year - 4716 : $year - 4715;

		$syn_month = floor(($year + (($month - 1) * (1 / 12)) - 1900) * 12.3685) - 2;  // before :: -2

		for ($max_loop = $syn_month + $max_moon_cycles; $syn_month < $max_loop; $syn_month += 0.25)
		{
			$phase = $syn_month - floor($syn_month);

			$jc_time = $syn_month / 1236.85;		// time in Julian centuries from 1900 January 0.5
			$jc_time2 = $jc_time * $jc_time;		// square for frequent use
			$jc_time3 = $jc_time2 * $jc_time;		// cube for frequent use

			// mean time of phase
			$phase_time = 2415020.75933
				+ $synodic_month_length * $syn_month
				+ 0.0001178 * $jc_time2
				- 0.000000155 * $jc_time3
				+ 0.00033 * sin((166.56 + 132.87 * $jc_time - 0.009173 * $jc_time2) * $deg);

			// Sun's mean anomaly
			$sun_anom = 359.2242
				+ 29.10535608 * $syn_month
				- 0.0000333 * $jc_time2
				- 0.00000347 * $jc_time3;

			// Moon's mean anomaly
			$moon_anom = 306.0253
				+ 385.81691806 * $syn_month
				+ 0.0107306 * $jc_time2
				+ 0.00001236 * $jc_time3;

			// Moon's argument of latitude
			$moon_lat = 21.2964
				+ 390.67050646 * $syn_month
				- 0.0016528 * $jc_time2
				- 0.00000239 * $jc_time3;

			if (($phase < 0.01) || (abs($phase - 0.5) < 0.01))
			{
				// Corrections for New and Full Moon.
				$phase_time += (0.1734 - 0.000393 * $jc_time) * sin($sun_anom * $deg)
					+ 0.0021 * sin(2 * $sun_anom * $deg)
					- 0.4068 * sin($moon_anom * $deg)
					+ 0.0161 * sin(2 * $moon_anom * $deg)
					- 0.0004 * sin(3 * $moon_anom * $deg)
					+ 0.0104 * sin(2 * $moon_lat * $deg)
					- 0.0051 * sin(($sun_anom + $moon_anom) * $deg)
					- 0.0074 * sin(($sun_anom - $moon_anom) * $deg)
					+ 0.0004 * sin((2 * $moon_lat + $sun_anom) * $deg)
					- 0.0004 * sin((2 * $moon_lat - $sun_anom) * $deg)
					- 0.0006 * sin((2 * $moon_lat + $moon_anom) * $deg)
					+ 0.0010 * sin((2 * $moon_lat - $moon_anom) * $deg)
					+ 0.0005 * sin(($sun_anom + 2 * $moon_anom) * $deg);
			}
			else if ((abs($phase - 0.25) < 0.01 || (abs($phase - 0.75) < 0.01)))
			{
				$phase_time += (0.1721 - 0.0004 * $jc_time) * sin($sun_anom * $deg)
					+ 0.0021 * sin(2 * $sun_anom * $deg)
					- 0.6280 * sin($moon_anom * $deg)
					+ 0.0089 * sin(2 * $moon_anom * $deg)
					- 0.0004 * sin(3 * $moon_anom * $deg)
					+ 0.0079 * sin(2 * $moon_lat * $deg)
					- 0.0119 * sin(($sun_anom + $moon_anom) * $deg)
					- 0.0047 * sin(($sun_anom - $moon_anom) * $deg)
					+ 0.0003 * sin((2 * $moon_lat + $sun_anom) * $deg)
					- 0.0004 * sin((2 * $moon_lat - $sun_anom) * $deg)
					- 0.0006 * sin((2 * $moon_lat + $moon_anom) * $deg)
					+ 0.0021 * sin((2 * $moon_lat - $moon_anom) * $deg)
					+ 0.0003 * sin(($sun_anom + 2 * $moon_anom) * $deg)
					+ 0.0004 * sin(($sun_anom - 2 * $moon_anom) * $deg)
					- 0.0003 * sin((2 * $sun_anom + $moon_anom) * $deg);

				if ($phase < 0.5)
				{
					// First quarter correction.
					$phase_time += 0.0028 - (0.0004 * cos($sun_anom * $deg)) + (0.0003 * cos($moon_anom * $deg));
				}
				else
				{
					// Last quarter correction.
					$phase_time += -0.0028 + (0.0004 * cos($sun_anom * $deg)) - (0.0003 * cos($moon_anom * $deg));
				}
			}

			if ($phase_time >= $end_jd)
			{
				return $phases;
			}

			if ($phase_time >= $start_jd)
			{
				$phase = (int) floor(4 * $phase);

				$phases[] = [
					'phase'	=> $phase,
					'time'	=> round(($phase_time - 2440587.5) * 86400),
					'name'	=> moonphase_calculator::$moon_name_ary[$phase],
					'icon'	=> moonphase_calculator::$moon_icon_ary[$phase],
				];
			}
		}
	}
}
