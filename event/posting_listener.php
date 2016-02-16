<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2016 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\event;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\config\db_text as config_text;
use phpbb\controller\helper;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;

use marttiphpbb\calendar\model\include_files;
use marttiphpbb\calendar\model\input_settings;
use marttiphpbb\calendar\manager\event;

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

	/* @var config */
	protected $config_text;

	/* @var helper */
	protected $helper;

	/* @var php_ext */
	protected $php_ext;

	/* @var request */
	protected $request;

	/* @var template */
	protected $template;

	/* @var user */
	protected $user;

	/* @var include_files */
	protected $include_files;

	/* @var input_settings */
	protected $input_settings;

	/* @var event */
	protected $event;

	/**
	* @param auth		$auth
	* @param config		$config
	* @param config		$config_text
	* @param event		$event;
	* @param helper		$helper
	* @param string		$php_ext
	* @param request	$request
	* @param template	$template
	* @param user		$user
	* @param include_files	$include_files
	* @param input_settings	$input_settings
	*/
	public function __construct(
		auth $auth,
		config $config,
		config_text $config_text,
		helper $helper,
		$php_ext,
		request $request,
		template $template,
		user $user,
		include_files $include_files,
		input_settings $input_settings,
		event $event
	)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->config_text = $config_text;
		$this->helper = $helper;
		$this->php_ext = $php_ext;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->include_files = $include_files;
		$this->input_settings = $input_settings;
		$this->event = $event;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.modify_posting_parameters'				=> 'modify_posting_parameters',
			'core.posting_modify_cannot_edit_conditions'	=> 'posting_modify_cannot_edit_conditions',
			'core.posting_modify_submission_errors'			=> 'posting_modify_submission_errors',
			'core.posting_modify_submit_post_before'		=> 'posting_modify_submit_post_before',
			'core.posting_modify_submit_post_after'			=> 'posting_modify_submit_post_after',
			'core.posting_modify_template_vars'				=> 'posting_modify_template_vars',
			'core.submit_post_modify_sql_data'				=> 'submit_post_modify_sql_data',
		);
	}

	public function modify_posting_parameters($event)
	{

	}

	public function posting_modify_cannot_edit_conditions($event)
	{

	}

	public function posting_modify_submission_errors($event)
	{
		$post_data = $event['post_data'];
		$mode = $event['mode'];
		$post_id = $event['post_id'];
		$topic_id = $event['topic_id'];
		$forum_id = $event['forum_id'];
		$submit = $event['submit'];
		$error = ['error'];
		$post_id = $event['post_id'];
		$mode = $event['mode'];

		if (!($mode == 'post'
			|| ($mode == 'edit' && $post_id == $post_data['topic_first_post_id'])))
		{
			return;
		}

		$input = $this->input_settings->get($forum_id);

		if (!$input['max_event_count'])
		{
			return;
		}

		$post_data['topic_calendar_start'] = $this->request->variable('calendar_date_start', '');
		$post_data['topic_calendar_end'] = $this->request->variable('calendar_date_end', '');

		//

		$event['post_data'] = $post_data;
		return;

		//

		if (substr_count($post_data['topic_calendar_start'], '-') == 2)
		{
			list($start_year, $start_month, $start_day) = explode('-', $post_data['topic_calendar_start']);

			if (!checkdate($start_month, $start_day, $start_year))
			{
				$error[] = $this->user->lang['CALENDAR_ERROR_START_DATE'];
			}
		}
		else
		{
			$error[] = $this->user->lang['CALENDAR_ERROR_START_DATE'];
		}

		if (substr_count($post_data['topic_calendar_end'], '-') == 2)
		{
			list($end_year, $end_month, $end_day) = explode('-', $post_data['topic_calendar_end']);

			if (!checkdate($end_month, $end_day, $end_year))
			{
				$error[] = $this->user->lang['CALENDAR_ERROR_END_DATE'];
			}
		}
		else
		{
			$error[] = $this->user->lang['CALENDAR_ERROR_END_DATE'];
		}

/*
		$post_data['topic_calendar_start'] = gmmktime(12, 0, 0, $start_month, $start_day, $start_year);
		$post_data['topic_calendar_end'] = gmmktime(12, 0, 0, $end_month, $end_day, $end_year);
*/
		$event['error'] = $error;
		$event['post_data'] = $post_data;
	}

	public function posting_modify_submit_post_before($event)
	{
		$post_data = $event['post_data'];
		$data = $event['data'];
		$post_id = $event['post_id'];
		$mode = $event['mode'];

		if (!($mode == 'post'
			|| ($mode == 'edit' && $post_id == $post_data['topic_first_post_id'])))
		{
			return;
		}

		$input = $this->input_settings->get($forum_id);

		if (!$input['max_event_count'])
		{
			return;
		}

		list($start_year, $start_month, $start_day) = explode('-', $post_data['topic_calendar_start']);
		list($end_year, $end_month, $end_day) = explode('-', $post_data['topic_calendar_end']);

		$start = gmmktime(12, 0, 0, $start_month, $start_day, $start_year);
		$end = gmmktime(12, 0, 0, $end_month, $end_day, $end_year);

		$data['topic_calendar_start'] = $start;
		$data['topic_calendar_end'] = $end;

		$data['topic_calendar_count'] = 1;
		$data['topic_calendar_pos'] = 1;

		// todo
		$data['topic_calendar_event_id'] = 0;

		$event['data'] = $data;
	}

	public function posting_modify_submit_post_after($event)
	{

	}

	/*
	 *
	 */
	public function posting_modify_template_vars($event)
	{
		$page_data = $event['page_data'];
		$post_data = $event['post_data'];
		$post_id = $event['post_id'];
		$mode = $event['mode'];
		$submit = $event['submit'];
		$preview = $event['preview'];
		$load = $event['load'];
		$save = $event['save'];
		$refresh = $event['refresh'];
		$forum_id = $event['forum_id'];

		$input = $this->input_settings->get($forum_id);

		if (($mode == 'post'
			|| ($mode == 'edit' && $post_id == $post_data['topic_first_post_id']))
			&& $input['max_event_count'])
		{
			$calendar_input = true;
		}

		$user_lang = $this->user->lang['USER_LANG'];

		if (strpos($user_lang, '-x-') !== false)
		{
			$user_lang = substr($user_lang, 0, strpos($user_lang, '-x-'));
		}

		list($user_lang_short) = explode('-', $user_lang);

		$this->template->assign_vars(array(
			'CALENDAR_USER_LANG_SHORT'		=> $user_lang_short,
			'S_CALENDAR_INPUT'				=> isset($calendar_input),
			'S_CALENDAR_TO_INPUT'			=> true,
			'S_CALENDAR_REQUIRED'			=> ($input['required']) ? true : false,
			'CALENDAR_GRANULARITY'			=> $input['granularity'],
			'CALENDAR_LOWER_LIMIT'			=> $input['lower_limit'],
			'CALENDAR_UPPER_LIMIT'			=> $input['upper_limit'],
			'CALENDAR_MIN_DURATION'			=> $input['min_duration'],
			'CALENDAR_MAX_DURATION'			=> $input['max_duration'],
			'CALENDAR_DEFAULT_DURATION'		=> $input['default_duration'],
			'S_CALENDAR_FIXED_DURATION'		=> ($input['fixed_duration']) ? true : false,
			'CALENDAR_MIN_GAP'				=> $input['min_gap'],
			'CALENDAR_MAX_GAP'				=> $input['max_gap'],
			'CALENDAR_MAX_EVENT_COUNT'		=> $input['max_event_count'],
			'CALENDAR_DATE_FORMAT'			=> 'yyyy-mm-dd',
			'CALENDAR_DATE_START'			=> (isset($post_data['topic_calendar_start'])) ? gmdate('Y-m-d', $post_data['topic_calendar_start']) : '', //(isset($post_data['topic_calendar_start'])) ? gmdate('Y-M-d', $post_data['topic_calendar_start']) : '',
			'CALENDAR_DATE_END'				=> (isset($post_data['topic_calendar_end'])) ? gmdate('Y-m-d', $post_data['topic_calendar_end']) : '', //(isset($post_data['topic_calendar_end'])) ? gmdate('Y-M-d', $post_data['topic_calendar_end']) : '',
		));
		$this->include_files->assign_template_vars();
		$this->user->add_lang_ext('marttiphpbb/calendar', 'posting');
	}

	public function submit_post_modify_sql_data($event)
	{
		//////// post topic data.
		$sql_data = $event['sql_data'];
		$data = $event['data'];

		$sql_data[TOPICS_TABLE]['sql']['topic_calendar_start'] = $data['topic_calendar_start'];
		$sql_data[TOPICS_TABLE]['sql']['topic_calendar_end'] = $data['topic_calendar_end'];
		$sql_data[TOPICS_TABLE]['sql']['topic_calendar_pos'] = $data['topic_calendar_pos'];
		$sql_data[TOPICS_TABLE]['sql']['topic_calendar_count'] = $data['topic_calendar_count'];

//		$sql_data[TOPICS_TABLE]['sql']['topic_calendar_id'] = 0;

		$event['sql_data'] = $sql_data;
	}

}
