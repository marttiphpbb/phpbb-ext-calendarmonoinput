<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2015 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\model;

use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\template\template;
use phpbb\user;

class pagination
{

	/* @var config */
	protected $config;

	/* @var helper */
	protected $helper;

	/* @var template */
	protected $template;

	/* @var user */
	protected $user;

	static protected $month_abbrev = array(
		1	=> 'Jan',
		2	=> 'Feb',
		3	=> 'Mar',
		4	=> 'Apr',
		5	=> 'May_short',
		6	=> 'Jun',
		7	=> 'Jul',
		8	=> 'Aug',
		9	=> 'Sep',
		10	=> 'Oct',
		11	=> 'Nov',
		12	=> 'Dec',
	);

	/**
	* @param config		$config
	* @param helper 	$helper
	* @param template	$template
	* @param user		$user
	* @return pagination
	*/
	public function __construct(
		config $config,
		helper $helper,
		template $template,
		user $user
	)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->template = $template;
		$this->user = $user;
	}

	/*
	 * @return pagination
	 */
	public function render($year, $month)
	{
		$this->template->assign_block_vars('pagination', array(
			'S_IS_PREV'		=> true,
			'PAGE_URL'		=> $this->helper->route('marttiphpbb_calendar_monthview_controller', array(
				'year' 	=> ($month == 1) ? $year - 1 : $year,
				'month'	=> ($month == 1) ? 12 : $month - 1,
			)),
		));

		for ($i = -2; $i < 3; $i++)
		{
			$pag_month = $month + $i;
			$pag_year = $year;

			if ($pag_month < 1)
			{
				$pag_year--;
				$pag_month += 12;
			}
			else if ($pag_month > 12)
			{
				$pag_year++;
				$pag_month -= 12;
			}

			$this->template->assign_block_vars('pagination', array(
				'S_IS_CURRENT'	=> ($i) ? false : true,
				'PAGE_NUMBER'	=> $this->user->lang['datetime'][pagination::$month_abbrev[$pag_month]],
				'PAGE_URL'		=> $this->helper->route('marttiphpbb_calendar_monthview_controller', array(
					'year' 	=> $pag_year,
					'month'	=> $pag_month,
				)),
			));
		}

		$this->template->assign_block_vars('pagination', array(
			'S_IS_NEXT'		=> true,
			'PAGE_URL'		=> $this->helper->route('marttiphpbb_calendar_monthview_controller', array(
				'year' 	=> ($month == 12) ? $year + 1 : $year,
				'month'	=> ($month == 12) ? 1 : $month + 1,
			)),
		));

		return $this;
	}
}
