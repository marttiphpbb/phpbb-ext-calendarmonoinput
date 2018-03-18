<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2017 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\event;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb\language\language;
use phpbb\event\data as event;

use marttiphpbb\calendar\render\include_assets;
use marttiphpbb\calendar\render\input_settings;
use marttiphpbb\calendar\render\posting;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class posting_listener implements EventSubscriberInterface
{
	/* @var auth */
	protected $auth;

	/* @var config */
	protected $config;

	/* @var helper */
	protected $helper;

	/* @var request */
	protected $request;

	/* @var template */
	protected $template;

	/* @var user */
	protected $user;

	/* @var language */
	protected $language;

	/* @var include_assets */
	protected $include_assets;

	/* @var input_settings */
	protected $input_settings;

	/* @var posting */
	private $posting;

	/**
	* @param auth		$auth
	* @param config		$config
	* @param event		$event;
	* @param helper		$helper
	* @param request	$request
	* @param template	$template
	* @param user		$user
	* @param language	$language
	* @param include_assets	$include_assets
	* @param input_settings	$input_settings
	* @param posting $posting
	*/
	public function __construct(
		auth $auth,
		config $config,
		helper $helper,
		request $request,
		template $template,
		user $user,
		language $language,
		include_assets $include_assets,
		input_settings $input_settings,
		posting $posting
	)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->helper = $helper;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->language = $language;
		$this->include_assets = $include_assets;
		$this->input_settings = $input_settings;
		$this->posting = $posting;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.modify_posting_parameters'				=> 'modify_posting_parameters',
			'core.posting_modify_cannot_edit_conditions'	=> 'posting_modify_cannot_edit_conditions',
			'core.posting_modify_submission_errors'			=> 'posting_modify_submission_errors',
			'core.posting_modify_submit_post_before'		=> 'posting_modify_submit_post_before',
			'core.posting_modify_submit_post_after'			=> 'posting_modify_submit_post_after',
			'core.posting_modify_template_vars'				=> 'posting_modify_template_vars',
			'core.submit_post_modify_sql_data'				=> 'submit_post_modify_sql_data',
			'core.submit_post_end'							=> 'submit_post_end',
		];
	}

	public function modify_posting_parameters(event $event)
	{

	}

	public function posting_modify_cannot_edit_conditions(event $event)
	{

	}

	public function posting_modify_submission_errors(event $event)
	{
		$post_data = $event['post_data'];

		if (!$this->is_first_post($event['mode'], $event['post_id'], $post_data['topic_first_post_id']))
		{
			return;
		}

		if (!$this->input_settings->get_enabled($event['forum_id']))
		{
			return;
		}

		$error = ['error'];		

		$post_data['topic_calendar_start'] = $this->request->variable('calendar_date_start', '');
		$post_data['topic_calendar_end'] = $this->request->variable('calendar_date_end', '');

		$event['post_data'] = $post_data;

		return;

		if (substr_count($post_data['topic_calendar_start'], '-') == 2)
		{
			list($start_year, $start_month, $start_day) = explode('-', $post_data['topic_calendar_start']);

			if (!checkdate($start_month, $start_day, $start_year))
			{
				$error[] = $this->language->lang('CALENDAR_START_DATE_ERROR');
			}
		}
		else
		{
			$error[] = $this->language->lang('CALENDAR_START_DATE_ERROR');
		}

		if (substr_count($post_data['topic_calendar_end'], '-') == 2)
		{
			list($end_year, $end_month, $end_day) = explode('-', $post_data['topic_calendar_end']);

			if (!checkdate($end_month, $end_day, $end_year))
			{
				$error[] = $this->language->lang('CALENDAR_END_DATE_ERROR');
			}
		}
		else
		{
			$error[] = $this->language->lang('CALENDAR_END_DATE_ERROR');
		}

/*
		$post_data['topic_calendar_start'] = gmmktime(12, 0, 0, $start_month, $start_day, $start_year);
		$post_data['topic_calendar_end'] = gmmktime(12, 0, 0, $end_month, $end_day, $end_year);
*/
		$event['error'] = $error;
		$event['post_data'] = $post_data;
	}

	public function posting_modify_submit_post_before(event $event)
	{
		$post_data = $event['post_data'];
		$data = $event['data'];

		if (!$this->is_first_post($event['mode'], $event['post_id'], $post_data['topic_first_post_id']))
		{
			return;
		}		

		$input = $this->input_settings->get($event['forum_id']);

// todo: checking according to settings

		list($start_year, $start_month, $start_day) = explode('-', $post_data['topic_calendar_start']);
		list($end_year, $end_month, $end_day) = explode('-', $post_data['topic_calendar_end']);

		$start = gmmktime(12, 0, 0, $start_month, $start_day, $start_year);
		$end = gmmktime(12, 0, 0, $end_month, $end_day, $end_year);

		$data['topic_calendar_start'] = $start;
		$data['topic_calendar_end'] = $end;

		$event['data'] = $data;
	}

	public function posting_modify_submit_post_after(event $event)
	{
		$post_data = $event['post_data'];
		$data = $event['data'];
		$mode = $event['mode'];
		$page_title = $event['page_title'];
		$post_id = $event['post_id'];
		$topic_id = $event['topic_id'];
		$forum_id = $event['forum_id'];
		$post_author_name = $event['post_author_name'];
		$update_message = $event['update_message'];
		$update_subject = $event['update_subject'];
	}

	public function posting_modify_template_vars(event $event)
	{
		$post_data = $event['post_data'];

		if (!$this->is_first_post($event['mode'], $event['post_id'], $post_data['topic_first_post_id']))
		{
			return;
		}

		$this->posting->assign_template_vars($event['forum_id'], $post_data);
	}

	public function submit_post_modify_sql_data(event $event)
	{
		$sql_data = $event['sql_data'];
		$data = $event['data'];

		$sql_data[TOPICS_TABLE]['sql']['topic_calendar_start'] = $data['topic_calendar_start'];
		$sql_data[TOPICS_TABLE]['sql']['topic_calendar_end'] = $data['topic_calendar_end'];

		$event['sql_data'] = $sql_data;
	}

	public function submit_post_end(event $event)
	{
		$data = $event['data'];
		$mode = $event['mode'];
	}

	private function is_first_post(string $mode, int $post_id, int $first_post_id):bool
	{
		if ($mode === 'edit' && $post_id !== $first_post_id)
		{
			return false;
		}

		if (!in_array($mode, ['post', 'edit']))
		{
			return false;
		}

		return true;
	}
}
