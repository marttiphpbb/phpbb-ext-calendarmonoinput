<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2016 marttiphpbb <info@martti.be>
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

use marttiphpbb\calendar\model\include_assets;
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

	/* @var language */
	protected $language;

	/* @var include_assets */
	protected $include_assets;

	/* @var input_settings */
	protected $input_settings;

	/* @var event */
	protected $event;

	/**
	* @param auth		$auth
	* @param config		$config
	* @param event		$event;
	* @param helper		$helper
	* @param string		$php_ext
	* @param request	$request
	* @param template	$template
	* @param user		$user
	* @param language	$language
	* @param include_assets	$include_assets
	* @param input_settings	$input_settings
	*/
	public function __construct(
		auth $auth,
		config $config,
		helper $helper,
		$php_ext,
		request $request,
		template $template,
		user $user,
		language $language,
		include_assets $include_assets,
		input_settings $input_settings,
		event $event
	)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->helper = $helper;
		$this->php_ext = $php_ext;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->language = $language;
		$this->include_assets = $include_assets;
		$this->input_settings = $input_settings;
		$this->event = $event;
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

		if (!($mode == 'post' || ($mode == 'edit' && $post_id == $post_data['topic_first_post_id'])))
		{
			return;
		}

		if (!$this->input_settings->get_enabled($forum_id))
		{
			return;
		}

		$post_data['topic_calendar_start'] = $this->request->variable('calendar_date_start', '');
		$post_data['topic_calendar_end'] = $this->request->variable('calendar_date_end', '');

		$event['post_data'] = $post_data;

		return;

		if (substr_count($post_data['topic_calendar_start'], '-') == 2)
		{
			list($start_year, $start_month, $start_day) = explode('-', $post_data['topic_calendar_start']);

			if (!checkdate($start_month, $start_day, $start_year))
			{
				$error[] = $this->language->lang('CALENDAR_ERROR_START_DATE');
			}
		}
		else
		{
			$error[] = $this->language->lang('CALENDAR_ERROR_START_DATE');
		}

		if (substr_count($post_data['topic_calendar_end'], '-') == 2)
		{
			list($end_year, $end_month, $end_day) = explode('-', $post_data['topic_calendar_end']);

			if (!checkdate($end_month, $end_day, $end_year))
			{
				$error[] = $this->language->lang('CALENDAR_ERROR_END_DATE');
			}
		}
		else
		{
			$error[] = $this->language->lang('CALENDAR_ERROR_END_DATE');
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

// todo: checking according to settings

		list($start_year, $start_month, $start_day) = explode('-', $post_data['topic_calendar_start']);
		list($end_year, $end_month, $end_day) = explode('-', $post_data['topic_calendar_end']);

		$start = gmmktime(12, 0, 0, $start_month, $start_day, $start_year);
		$end = gmmktime(12, 0, 0, $end_month, $end_day, $end_year);

		$data['topic_calendar_start'] = $start;
		$data['topic_calendar_end'] = $end;

		$event['data'] = $data;
	}

	public function posting_modify_submit_post_after($event)
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

		$enabled = $this->input_settings->get_enabled($forum_id);
		$required = $this->input_settings->get_required($forum_id);

		if (($mode == 'post'
			|| ($mode == 'edit' && $post_id == $post_data['topic_first_post_id']))
			&& $enabled)
		{
			$calendar_input = true;
		}

		$input_settings = $this->input_settings->get();

		$user_lang = $this->language->lang('USER_LANG');

		if (strpos($user_lang, '-x-') !== false)
		{
			$user_lang = substr($user_lang, 0, strpos($user_lang, '-x-'));
		}

		list($user_lang_short) = explode('-', $user_lang);

		$this->template->assign_vars([
			'CALENDAR_USER_LANG_SHORT'		=> $user_lang_short,
			'S_CALENDAR_INPUT'				=> isset($calendar_input),
			'S_CALENDAR_TO_INPUT'			=> $input_settings['max_duration'] ? true : false,
			'S_CALENDAR_REQUIRED'			=> $required,
			'CALENDAR_LOWER_LIMIT'			=> $input_settings['lower_limit'],
			'CALENDAR_UPPER_LIMIT'			=> $input_settings['upper_limit'],
			'CALENDAR_MIN_DURATION'			=> $input_settings['min_duration'],
			'CALENDAR_MAX_DURATION'			=> $input_settings['max_duration'],
			'CALENDAR_DATE_FORMAT'			=> 'yyyy-mm-dd',
			'CALENDAR_DATE_START'			=> isset($post_data['topic_calendar_start']) ? gmdate('Y-m-d', $post_data['topic_calendar_start']) : '', 
			'CALENDAR_DATE_END'				=> isset($post_data['topic_calendar_end']) ? gmdate('Y-m-d', $post_data['topic_calendar_end']) : '',
			'CALENDAR_DATEPICKER_THEME'		=> $this->config['calendar_datepicker_theme'],
		]);

		$this->include_assets->assign_template_vars();
		$this->language->add_lang('posting', 'marttiphpbb/calendar');
	}

	public function submit_post_modify_sql_data($event)
	{
		$sql_data = $event['sql_data'];
		$data = $event['data'];

		$sql_data[TOPICS_TABLE]['sql']['topic_calendar_start'] = $data['topic_calendar_start'];
		$sql_data[TOPICS_TABLE]['sql']['topic_calendar_end'] = $data['topic_calendar_end'];

		$event['sql_data'] = $sql_data;
	}

	public function submit_post_end($event)
	{
		$data = $event['data'];
		$mode = $event['mode'];

	}
}
