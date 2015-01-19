<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\event;

use phpbb\auth\auth;
use phpbb\config\config;
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
	* @param helper		$helper
	* @param string		$php_ext
	* @param template	$template
	* @param user		$user
	*/
	public function __construct(
		auth $auth,
		config $config,
		helper $helper,
		$php_ext,
		template $template,
		user $user
	)
	{
		$this->auth = $auth;
		$this->config = $config;
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

	public function posting_modify_template_vars($event)
	{

	}

}
