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
class main_listener implements EventSubscriberInterface
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
			'core.user_setup'						=> 'load_language_on_setup',
			'core.page_header'						=> 'add_calendar_link',
			'core.viewonline_overwrite_location'	=> 'add_viewonline',
		);
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'marttiphpbb/calendar',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function add_calendar_link($event)
	{
		$this->template->assign_vars(array(
			'U_CALENDAR'					=> $this->helper->route('marttiphpbb_calendar_defaultview_controller'),

//			'S_CALENDAR_CAN_VIEW'			=> $this->auth->acl_get('u_viewcalendar'),
			'S_CALENDAR_MENU_QUICK'			=> $this->config['calendar_menu_quick'],
			'S_CALENDAR_MENU_HEADER'		=> $this->config['calendar_menu_header'],
			'S_CALENDAR_MENU_FOOTER'		=> $this->config['calendar_menu_footer'],
			'S_CALENDAR_HIDE_GITHUB_LINK'	=> $this->config['calendar_hide_github_link'],

		));
	}

	public function add_viewonline($event)
	{
		if (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/calendar') === 0)
		{
			$event['location'] = $this->user->lang('CALENDAR_VIEWING');
			$event['location_url'] = $this->helper->route('marttiphpbb_calendar_defaultview_controller');
		}
	}

}
