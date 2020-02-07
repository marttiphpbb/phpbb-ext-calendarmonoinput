<?php
/**
* phpBB Extension - marttiphpbb calendarmonoinput
* @copyright (c) 2014 - 2020 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarmonoinput\event;

use phpbb\event\data as event;
use marttiphpbb\calendarmono\util\cnst as mono_cnst;
use marttiphpbb\calendarmonoinput\service\posting;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	protected $posting;

	public function __construct(
		posting $posting
	)
	{
		$this->posting = $posting;
	}

	static public function getSubscribedEvents():array
	{
		return [
			'core.posting_modify_submission_errors'
				=> 'posting_modify_submission_errors',
			'core.posting_modify_submit_post_before'
				=> 'posting_modify_submit_post_before',
			'core.posting_modify_template_vars'
				=> 'posting_modify_template_vars',
			'core.submit_post_modify_sql_data'
				=> 'submit_post_modify_sql_data',
		];
	}

	public function posting_modify_submission_errors(event $event):void
	{
		$post_data = $event['post_data'];
		$error = $event['error'];
		$forum_id = $event['forum_id'];
		$mode = $event['mode'];
		$post_id = $event['post_id'];

		if (!$this->is_first_post($mode, $post_id, $post_data['topic_first_post_id']))
		{
			return;
		}

		$this->posting->process_submit($forum_id);

		$error = array_merge($error, $this->posting->get_submit_errors($forum_id, $post_data));

		$post_data[mono_cnst::COLUMN_START] = $this->posting->get_start_jd();
		$post_data[mono_cnst::COLUMN_END] = $this->posting->get_end_jd();

		$event['error'] = $error;
		$event['post_data'] = $post_data;
	}

	public function posting_modify_submit_post_before(event $event):void
	{
		$post_data = $event['post_data'];
		$data = $event['data'];

		$data[mono_cnst::COLUMN_START] = $post_data[mono_cnst::COLUMN_START];
		$data[mono_cnst::COLUMN_END] = $post_data[mono_cnst::COLUMN_END];

		$event['data'] = $data;
	}

	public function posting_modify_template_vars(event $event):void
	{
		$post_data = $event['post_data'];
		$mode = $event['mode'];
		$post_id = $event['post_id'];

		if (!$this->is_first_post($mode, $post_id, $post_data['topic_first_post_id']))
		{
			return;
		}

		$page_data = $event['page_data'];

		$page_data = array_merge($page_data, $this->posting->get_template_vars($event['forum_id'], $post_data));
		$event['page_data'] = $page_data;
	}

	public function submit_post_modify_sql_data(event $event):void
	{
		$sql_data = $event['sql_data'];
		$data = $event['data'];
		$sql_data[TOPICS_TABLE]['sql'][mono_cnst::COLUMN_START] = $data[mono_cnst::COLUMN_START];
		$sql_data[TOPICS_TABLE]['sql'][mono_cnst::COLUMN_END] = $data[mono_cnst::COLUMN_END];
		$event['sql_data'] = $sql_data;
	}

	private function is_first_post(string $mode, int $post_id, $first_post_id):bool
	{
		if ($mode === 'edit' && $post_id !== (int) $first_post_id)
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
