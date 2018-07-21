<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\event;

use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb\language\language;
use phpbb\extension\manager;
use phpbb\event\data as event;
use marttiphpbb\calendarinput\util\cnst;
use marttiphpbb\calendarmono\util\cnst as mono_cnst;
use marttiphpbb\calendarinput\service\posting;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	protected $request;
	protected $template;
	protected $user;
	protected $language;
	protected $posting;

	public function __construct(
		request $request,
		template $template,
		user $user,
		language $language,
		posting $posting
	)
	{
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->language = $language;
		$this->posting = $posting;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.modify_posting_parameters'
				=> 'modify_posting_parameters',
			'core.posting_modify_cannot_edit_conditions'
				=> 'posting_modify_cannot_edit_conditions',
			'core.posting_modify_submission_errors'
				=> 'posting_modify_submission_errors',
			'core.posting_modify_submit_post_before'
				=> 'posting_modify_submit_post_before',
			'core.posting_modify_submit_post_after'
				=> 'posting_modify_submit_post_after',
			'core.posting_modify_template_vars'
				=> 'posting_modify_template_vars',
			'core.submit_post_modify_sql_data'
				=> 'submit_post_modify_sql_data',
			'core.submit_post_end'
				=> 'submit_post_end',
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
		$error = $event['error'];

		if (!$this->is_first_post($event['mode'], $event['post_id'], $post_data['topic_first_post_id']))
		{
			return;
		}

		if (!$this->posting->get_ext_enabled())
		{
			return;
		}

		if (!$this->posting->get_forum_enabled($event['forum_id']))
		{
			return;
		}

		$this->language->add_lang('posting', cnst::FOLDER);

		$atom_start = $this->request->variable('alt_calendarinput_date_start', '');
		$atom_end = $this->request->variable('alt_calendarinput_date_end', '');

		if (!$this->validate_atom_date($atom_start))
		{
			$error[] = $this->language->lang(cnst::L . '_START_DATE_ERROR');
		}

		if (!$this->validate_atom_date($atom_end))
		{
			$error[] = $this->language->lang(cnst::L . '_END_DATE_ERROR');
		}

		$post_data[mono_cnst::COLUMN_START] = $this->atom_date_to_jd($atom_start);
		$post_data[mono_cnst::COLUMN_END] = $this->atom_date_to_jd($atom_end);

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

		if (!$this->posting->get_ext_enabled())
		{
			return;
		}

		$data[mono_cnst::COLUMN_START] = $post_data[mono_cnst::COLUMN_START];
		$data[mono_cnst::COLUMN_END] = $post_data[mono_cnst::COLUMN_END];

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

		if (!$this->posting->get_ext_enabled())
		{
			return;
		}

		$this->posting->assign_template_vars($event['forum_id'], $post_data);
		$this->language->add_lang('posting', cnst::FOLDER);
	}

	public function submit_post_modify_sql_data(event $event)
	{
		$sql_data = $event['sql_data'];
		$data = $event['data'];
		$sql_data[TOPICS_TABLE]['sql'][mono_cnst::COLUMN_START] = $data[mono_cnst::COLUMN_START];
		$sql_data[TOPICS_TABLE]['sql'][mono_cnst::COLUMN_END] = $data[mono_cnst::COLUMN_END];
		$event['sql_data'] = $sql_data;
	}

	public function submit_post_end(event $event)
	{
		$data = $event['data'];
		$mode = $event['mode'];
	}

	private function is_first_post(string $mode, int $post_id, $first_post_id):bool
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

	private function validate_atom_date(string $atom_date):bool
	{
		$parts = [];

		if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $atom_date, $parts) === 1)
		{
			return checkdate($parts[2], $parts[3], $parts[1]);
		}

		return false;
	}

	private function atom_date_to_jd(string $atom_date):int
	{
		list($y, $m, $d) = explode('-', $atom_date);
		return cal_to_jd(CAL_GREGORIAN, (int) $m, (int) $d, (int) $y);
	}
}
