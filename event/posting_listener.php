<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2015 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\event;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\config\db_text as config_text;
use phpbb\controller\helper;
use phpbb\template\template;
use phpbb\user;

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

	/* @var template */
	protected $template;

	/* @var user */
	protected $user;

	/**
	* @param auth		$auth
	* @param config		$config
	* @param config		$config_text
	* @param helper		$helper
	* @param string		$php_ext
	* @param template	$template
	* @param user		$user
	*/
	public function __construct(
		auth $auth,
		config $config,
		config_text $config_text,
		helper $helper,
		$php_ext,
		template $template,
		user $user
	)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->config_text = $config_text;
		$this->helper = $helper;
		$this->php_ext = $php_ext;
		$this->template = $template;
		$this->user = $user;
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

	}

	public function posting_modify_submit_post_before($event)
	{

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
		$mode = $event['mode'];
		$submit = $event['submit'];
		$preview = $event['preview'];
		$load = $event['load'];
		$save = $event['save'];
		$refresh = $event['refresh'];
		$forum_id = $event['forum_id'];

		if ($mode == 'post'
			&& !$submit && !$preview && !$load && !$save && !$refresh)
		//	&& $this->config_text->get('marttiphpbb_calendar_forum[' . $forum_id . ']')
		{
			$input = true;
		}

		$user_lang = $this->user->lang['USER_LANG'];
		if (strpos($user_lang, '-x-') !== false)
		{
			$user_lang = substr($user_lang, 0, strpos($user_lang, '-x-'));
		}
		list($user_lang_short) = explode('-', $user_lang);

		$this->template->assign_vars(array(
			'S_CALENDAR_USER_LANG_SHORT'	=> $user_lang_short,
			'S_CALENDAR_INPUT'				=> isset($input),
		));
		$this->user->add_lang_ext('marttiphpbb/calendar', 'posting');
	}

}
