<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2016 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\event;

use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\template\template;

use marttiphpbb\calendar\util\dateformat;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class suffix_listener implements EventSubscriberInterface
{
	/* @var config */
	protected $config;

	/* @var helper */
	protected $helper;

	/* @var php_ext */
	protected $php_ext;

	/* @var template */
	protected $template;

	/* @var dateformat */
	protected $dateformat;

	/**
	* @param config		$config
	* @param helper		$helper
	* @param string		$php_ext
	* @param template	$template
	* @param dateformat	$dateformat
	*/
	public function __construct(
		config $config,
		helper $helper,
		$php_ext,
		template $template,
		dateformat $dateformat
	)
	{
		$this->config = $config;
		$this->helper = $helper;
		$this->php_ext = $php_ext;
		$this->template = $template;
		$this->dateformat = $dateformat;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.viewtopic_assign_template_vars_before'
					=> 'core_viewtopic_assign_template_vars_before',
			'core.viewforum_modify_topicrow'
					=> 'core_viewforum_modify_topicrow',
		];
	}

	public function core_viewforum_modify_topicrow($event)
	{
		$row = $event['row'];
		$topic_row = $event['topic_row'];

		$start = $row['topic_calendar_start'];
		$end = $row['topic_calendar_end'];

		if ($start)
		{
			$year = gmdate('Y', $start);
			$month = gmdate('n', $start);

			$topic_row['CALENDAR_SUFFIX_URL'] = $this->helper->route('marttiphpbb_calendar_monthview_controller', ['year' => $year, 'month' => $month]);
			$topic_row['CALENDAR_SUFFIX'] = $this->dateformat->get_period($start, $end);
		}

		$event['topic_row'] = $topic_row;
	}

	public function core_viewtopic_assign_template_vars_before($event)
	{
		$topic_data = $event['topic_data'];

		$start = $topic_data['topic_calendar_start'];
		$end = $topic_data['topic_calendar_end'];

		if ($start)
		{
			$year = gmdate('Y', $start);
			$month = gmdate('n', $start);

			$this->template->assign_vars([
				'CALENDAR_SUFFIX_URL'	=> $this->helper->route('marttiphpbb_calendar_monthview_controller', ['year' => $year, 'month' => $month]),
				'CALENDAR_SUFFIX'		=> $this->dateformat->get_period($start, $end),
			]);
		}
	}

}
